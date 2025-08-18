<?php

namespace App\Http\Controllers;

use App\Models\DeliveryLocation;
use App\Models\LocationEdges;
use App\Services\Dijkstra;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){
        $locations = DeliveryLocation::all();
        
       
        return view('home.index', ['locations' => $locations]);
    }

    public function location(Request $request)
    {
        // Jika request via AJAX
        if ($request->ajax()) {
            $query = $request->get('q', ''); // ambil query pencarian (misal dari Select2)
    
            $locations = DeliveryLocation::when($query, function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->orderBy('name', 'asc')
                ->limit(10)
                ->get(['id', 'name']); // hanya ambil kolom yang diperlukan
    
            return response()->json($locations);
        }
    
        // Kalau bukan AJAX → kirim ke view
        $locations = DeliveryLocation::all();
        return view('locations.index', [
            'locations' => $locations
        ]);
    }

    public function locationForm(){
        $deliveryLocation = new DeliveryLocation();
        return view('locations.form', [
            'deliveryLocation' => $deliveryLocation
        ]);
    }

    public function locationStore(Request $request){
        $request->validate([
            'name' => 'required',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        DeliveryLocation::create($request->all());
        return redirect('/locations')->with('success', 'Successfully added location');
    }

    public function locationShow(string $delivery_location){
        $deliveryLocation = DeliveryLocation::find($delivery_location);
        return view('locations.form', [
            'deliveryLocation' => $deliveryLocation
        ]);
    }

    public function locationUpdate(Request $request, string $location){
        $deliveryLocations = DeliveryLocation::findOrFail($location);
        $request->validate([
            'name' => 'required',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $deliveryLocations->update($request->all());
        return redirect('/locations')->with('success', 'Successfully updated location');
    }

    public function locationDelete(string $location){
        $location = DeliveryLocation::findOrFail($location);
        $location->delete();
        return redirect('/locations')->with('success', 'Successfully deleted location');
    }
}
