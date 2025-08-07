<?php

namespace App\Services;

class Dijkstra
{
    protected $graph = [];

    public function __construct($edges)
    {
        foreach ($edges as $edge) {
            $this->graph[$edge->from_location_id][$edge->to_location_id] = $edge->weight;
        }
    }

    public function shortestPath($start, $end)
    {
        $dist = [];
        $prev = [];
        $nodes = array_keys($this->graph);

        foreach ($nodes as $node) {
            $dist[$node] = INF;
            $prev[$node] = null;
        }

        $dist[$start] = 0;

        while (!empty($nodes)) {
            $minNode = null;
            foreach ($nodes as $node) {
                if ($minNode === null || $dist[$node] < $dist[$minNode]) {
                    $minNode = $node;
                }
            }

            if ($dist[$minNode] === INF) {
                break;
            }

            foreach ($this->graph[$minNode] ?? [] as $neighbor => $cost) {
                $alt = $dist[$minNode] + $cost;
                if ($alt < $dist[$neighbor]) {
                    $dist[$neighbor] = $alt;
                    $prev[$neighbor] = $minNode;
                }
            }

            $nodes = array_diff($nodes, [$minNode]);
        }

        $path = [];
        $u = $end;
        while (isset($prev[$u])) {
            array_unshift($path, $u);
            $u = $prev[$u];
        }

        if (!empty($path)) {
            array_unshift($path, $start);
        }

        return [
            'distance' => $dist[$end],
            'path' => $path
        ];
    }
}