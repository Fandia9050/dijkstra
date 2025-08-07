<?php

namespace Database\Seeders;

use App\Models\DeliveryLocation;
use App\Models\LocationEdges;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['name' => 'Gudang A', 'latitude' => -6.200000, 'longitude' => 106.816666],
            ['name' => 'Toko B',   'latitude' => -6.210000, 'longitude' => 106.820000],
            ['name' => 'Toko C',   'latitude' => -6.220000, 'longitude' => 106.830000],
            ['name' => 'Toko D',   'latitude' => -6.230000, 'longitude' => 106.840000],
            ['name' => 'Toko E',   'latitude' => -6.240000, 'longitude' => 106.850000],
        ];

        foreach ($locations as $loc) {
            DeliveryLocation::create($loc);
        }

        // Ambil ID lokasi
        $locIds = DeliveryLocation::pluck('id', 'name');

        // 2️⃣ Buat koneksi antar lokasi (edges)
        $edges = [
            // from, to, distance (km)
            ['from' => 'Gudang A', 'to' => 'Toko B', 'distance_km' => 5],
            ['from' => 'Gudang A', 'to' => 'Toko C', 'distance_km' => 9],
            ['from' => 'Toko B',   'to' => 'Toko C', 'distance_km' => 3],
            ['from' => 'Toko B',   'to' => 'Toko D', 'distance_km' => 7],
            ['from' => 'Toko C',   'to' => 'Toko E', 'distance_km' => 2],
            ['from' => 'Toko D',   'to' => 'Toko E', 'distance_km' => 4],
        ];

        foreach ($edges as $edge) {
            LocationEdges::create([
                'from_location_id' => $locIds[$edge['from']],
                'to_location_id'   => $locIds[$edge['to']],
                'distance_km'           => $edge['distance_km'],
            ]);

            // Jika graf-nya dua arah, tambahkan balikannya
            LocationEdges::create([
                'from_location_id' => $locIds[$edge['to']],
                'to_location_id'   => $locIds[$edge['from']],
                'distance_km'           => $edge['distance_km'],
            ]);
        }

    }
}
