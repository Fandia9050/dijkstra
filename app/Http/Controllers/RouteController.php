<?php

namespace App\Http\Controllers;

use App\Models\DeliveryLocation;
use App\Models\LocationEdges;
use App\Services\Dijkstra;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RouteController extends Controller
{
    public function index()
    {
        // semua lokasi untuk dropdown (bisa dipaginasi jika banyak)
        $locations = DeliveryLocation::select('id', 'name', 'latitude', 'longitude', 'address')->get();

        return view('route.index', [
            'locations' => $locations,
        ]);
    }

    // public function shortest(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'start' => 'required|exists:delivery_locations,id',
    //         'end'   => 'required|exists:delivery_locations,id|different:start',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()->first()], 422);
    //     }

    //     $start = $request->input('start');
    //     $end   = $request->input('end');

    //     // Ambil semua edges untuk graph
    //     $edges = LocationEdges::all();

    //     $dijkstra = new Dijkstra($edges);
    //     $result = $dijkstra->shortestPath($start, $end);

    //     if (empty($result) || empty($result['path'])) {
    //         return response()->json(['error' => 'No route found'], 404);
    //     }

    //     // Ambil lokasi sesuai urutan path
    //     // Pastikan id di path berupa string atau integer sesuai tipe di DB
    //     $orderIds = array_map(fn($id) => "'" . $id . "'", $result['path']);

    //     $locations = DeliveryLocation::whereIn('id', $result['path'])
    //         ->orderByRaw("FIELD(id, " . implode(',', $orderIds) . ")")
    //         ->get(['id', 'name', 'latitude', 'longitude']);

    //     // Map ke array sederhana untuk JS
    //     $coords = $locations->map(fn($loc) => [
    //         'id' => $loc->id,
    //         'name' => $loc->name,
    //         'lat' => (float) $loc->latitude,
    //         'lng' => (float) $loc->longitude,
    //     ])->toArray();

    //     return response()->json([
    //         'path' => $result['path'],
    //         'distance' => $result['distance'],
    //         'coords' => $coords,
    //     ]);
    // }

    public function shortest(Request $request)
{
    $validator = Validator::make($request->all(), [
        'start' => 'required|exists:delivery_locations,id',
        'destinations' => 'required|array|min:1',
        'destinations.*' => 'required|exists:delivery_locations,id|different:start',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()->first()], 422);
    }

    $start = $request->input('start');
    $destinations = $request->input('destinations');

    $edges = LocationEdges::all();
    $dijkstra = new Dijkstra($edges);

    $result = $dijkstra->multiStopPath($start, $destinations);

    if (empty($result['path'])) {
        return response()->json(['error' => 'No route found'], 404);
    }

    $orderIds = array_map(fn($id) => "'" . $id . "'", $result['path']);
    $locations = DeliveryLocation::whereIn('id', $result['path'])
        ->orderByRaw("FIELD(id, " . implode(',', $orderIds) . ")")
        ->get(['id', 'name', 'latitude', 'longitude']);

    $coords = $locations->map(fn($loc) => [
        'id' => $loc->id,
        'name' => $loc->name,
        'lat' => (float) $loc->latitude,
        'lng' => (float) $loc->longitude,
    ])->toArray();

    return response()->json([
        'path' => $result['path'],
        'distance' => $result['distance'],
        'coords' => $coords,
    ]);
}

    public function searchLocations(Request $request)
    {
        $q = $request->get('q', '');
        $rows = DeliveryLocation::when($q, fn($qq) => $qq->where('name','like',"%{$q}%"))
            ->orderBy('name')->limit(20)->get(['id','name','latitude','longitude']);

        return response()->json(
            $rows->map(fn($r)=>['id'=>$r->id,'text'=>$r->name,'lat'=>$r->latitude,'lng'=>$r->longitude])
        );
    }

    // Multi-destination shortest path
    public function shortestMulti(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start'        => 'required|exists:delivery_locations,id',
            'locations'    => 'required|array|min:1',
            'locations.*'  => 'exists:delivery_locations,id|different:start',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $start        = (string) $request->input('start');
        $destinations = array_values(array_unique(array_map('strval', $request->input('locations', []))));

        // (Opsional) Auto-generate edges jika tabel kosong
        if (LocationEdges::count() === 0) {
            $this->seedEdgesFromLocations();
        }

        // Ambil semua edges → kirim ke Dijkstra (pakai model objects, bukan array)
        $edges = LocationEdges::all();

        // Jalankan Dijkstra multi-tujuan
        $dijkstra = new Dijkstra($edges); // pastikan constructor kamu baca $edge->from_location_id, ->to_location_id, ->distance_km
        $result   = $dijkstra->shortestPathMultiple($start, $destinations);

        if (empty($result['path'])) {
            return response()->json(['error' => 'No route found'], 404);
        }

        // Urutkan lokasi sesuai urutan path dari Dijkstra
        $orderIds  = array_map(fn($id) => "'$id'", $result['path']);
        $locations = DeliveryLocation::whereIn('id', $result['path'])
            ->orderByRaw("FIELD(id, " . implode(',', $orderIds) . ")")
            ->get(['id','name','latitude','longitude']);


        // Format path sesuai request + tambah name
        $pathData = $locations->map(fn($loc)=>[
            'id'   => (string)$loc->id,
            'name' => $loc->name,
            'lat'  => (float)$loc->latitude,
            'lng'  => (float)$loc->longitude,
        ])->values()->toArray();

        // Ambil jalur asli dari OSRM (return [lng,lat] list)
        $routeCoords = $this->fetchOsrmRoute($pathData);

        return response()->json([
            'path'         => $pathData,                 // [{id, name, lat, lng}, ...]
            'distance'     => round((float)$result['distance'], 2),
            'route_coords' => $routeCoords,              // [[lng,lat], [lng,lat], ...]
        ]);
    }

    protected function fetchOsrmRoute(array $pathData): array
    {
        if (count($pathData) < 2) {
            return [];
        }

        $coords = collect($pathData)->map(fn($p) => "{$p['lng']},{$p['lat']}")->implode(';');
        $url    = "https://router.project-osrm.org/route/v1/driving/{$coords}";
        $query  = [
            'overview'    => 'full',
            'geometries'  => 'geojson',
            'steps'       => 'false',
            'annotations' => 'false',
        ];

        try {
            $resp = Http::timeout(10)->get($url, $query);
            if ($resp->successful()) {
                $json = $resp->json();
                return $json['routes'][0]['geometry']['coordinates'] ?? [];
            }
        } catch (\Throwable $e) {
            // bisa log error jika perlu
        }
        return [];
    }

    /**
     * (Opsional) Generate edges fully-connected antar semua location memakai jarak Haversine (km)
     */
    protected function seedEdgesFromLocations(): void
    {
        $locs = DeliveryLocation::get(['id','latitude','longitude']);
        foreach ($locs as $a) {
            foreach ($locs as $b) {
                if ($a->id === $b->id) continue;
                $distKm = $this->haversineKm($a->latitude, $a->longitude, $b->latitude, $b->longitude);
                LocationEdges::create([
                    'from_location_id' => $a->id,
                    'to_location_id'   => $b->id,
                    'distance_km'      => $distKm,
                ]);
            }
        }
    }

    protected function haversineKm($lat1,$lng1,$lat2,$lng2): float
    {
        $R = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2)**2 + cos(deg2rad($lat1))*cos(deg2rad($lat2))*sin($dLng/2)**2;
        return $R * 2 * asin(sqrt($a));
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}
