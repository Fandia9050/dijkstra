<?php

namespace App\Http\Controllers;

use App\Models\DeliveryLocation;
use App\Models\LocationEdges;
use App\Services\Dijkstra;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){
        $edges = LocationEdges::all();
        $dijkstra = new Dijkstra($edges);
    
        // Misalnya cari rute dari Gudang A ke Toko E
        $start = DeliveryLocation::where('name', 'Gudang A')->value('id');
        $end   = DeliveryLocation::where('name', 'Toko E')->value('id');
    
        $result = $dijkstra->shortestPath($start, $end);
    
        // // Ambil detail lokasi dalam urutan rute
        // $locations = DeliveryLocation::whereIn('id', $result['path'])
        //     ->orderByRaw("FIELD(id, ".implode(',', $result['path']).")")
        //     ->get();

        $orderIds = array_map(fn($id) => "'{$id}'", $result['path']);

        $locations = DeliveryLocation::whereIn('id', $result['path'])
        ->orderByRaw("FIELD(id, " . implode(',', $orderIds) . ")")
        ->get();

    
       
        return view('vendor.adminlte.page', [
            'locations' => $locations,
            'total_distance' => $result['distance']
        ]);
    }
}
