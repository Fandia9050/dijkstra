<?php

namespace App\Services;

class Dijkstra
{
    protected $graph = [];

    public function __construct($edges)
    {
        foreach ($edges as $edge) {
            // Pastikan semua node punya entry di graph
            if (!isset($this->graph[$edge->from_location_id])) {
                $this->graph[$edge->from_location_id] = [];
            }
            if (!isset($this->graph[$edge->to_location_id])) {
                $this->graph[$edge->to_location_id] = [];
            }

            // Tambahkan edge (dua arah, undirected graph)
            $this->graph[$edge->from_location_id][$edge->to_location_id] = $edge->distance_km;
            $this->graph[$edge->to_location_id][$edge->from_location_id] = $edge->distance_km;
        }
    }

    public function shortestPath($start, $end)
    {
        // Validasi node ada di graph
        if (!isset($this->graph[$start]) || !isset($this->graph[$end])) {
            return [
                'distance' => null,
                'path' => [],
                'error' => "Node $start atau $end tidak ada di graph"
            ];
        }

        $dist = [];
        $prev = [];
        $nodes = array_keys($this->graph);

        // Inisialisasi jarak semua node ke INF
        foreach ($nodes as $node) {
            $dist[$node] = INF;
            $prev[$node] = null;
        }

        $dist[$start] = 0;

        while (!empty($nodes)) {
            // Cari node dengan jarak minimum
            $minNode = null;
            foreach ($nodes as $node) {
                if ($minNode === null || $dist[$node] < $dist[$minNode]) {
                    $minNode = $node;
                }
            }

            if ($dist[$minNode] === INF) break;

            // Update jarak ke tetangga
            foreach ($this->graph[$minNode] ?? [] as $neighbor => $cost) {
                $alt = $dist[$minNode] + $cost;
                if ($alt < $dist[$neighbor]) {
                    $dist[$neighbor] = $alt;
                    $prev[$neighbor] = $minNode;
                }
            }

            // Hapus node yang sudah diproses
            $nodes = array_diff($nodes, [$minNode]);
        }

        // Rekonstruksi path
        $path = [];
        $u = $end;
        while (isset($prev[$u]) && $prev[$u] !== null) {
            array_unshift($path, $u);
            $u = $prev[$u];
        }

        if ($u === $start) {
            array_unshift($path, $start);
        }

        return [
            'distance' => $dist[$end] !== INF ? $dist[$end] : null,
            'path' => $dist[$end] !== INF ? $path : [],
            'error' => $dist[$end] === INF ? "Tidak ditemukan rute dari $start ke $end" : null
        ];
    }

    // Cari rute multi tujuan (start → dest1 → dest2 → ... → destN)
    public function shortestPathMultiple($start, array $destinations)
    {
        $fullPath = [];
        $totalDistance = 0;
        $currentStart = $start;

        foreach ($destinations as $destination) {
            $result = $this->shortestPath($currentStart, $destination);

            if (empty($result['path'])) {
                return [
                    'distance' => null,
                    'path' => [],
                    'error' => "Tidak ditemukan rute dari $currentStart ke $destination"
                ];
            }

            // Hindari duplikasi titik awal
            if (!empty($fullPath)) {
                array_shift($result['path']);
            }

            $fullPath = array_merge($fullPath, $result['path']);
            $totalDistance += $result['distance'];
            $currentStart = $destination;
        }

        return [
            'distance' => $totalDistance,
            'path' => $fullPath,
            'error' => null
        ];
    }

    // Debug: tampilkan isi graph
    public function printGraph()
    {
        foreach ($this->graph as $from => $neighbors) {
            echo "Node $from:\n";
            foreach ($neighbors as $to => $dist) {
                echo "  → $to : $dist km\n";
            }
        }
    }
}
