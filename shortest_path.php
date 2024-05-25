<?php

class Graph {
    private $graph;
    private $distances;
    private $previous;
    private $queue;

    public function __construct($graph) {
        $this->graph = $graph;
    }

    public function dijkstra($start) {
        $this->distances = [];
        $this->previous = [];
        $this->queue = new SplPriorityQueue();

        foreach ($this->graph as $vertex => $neighbors) {
            $this->distances[$vertex] = INF;
            $this->previous[$vertex] = null;
            foreach ($neighbors as $neighbor => $cost) {
                $this->queue->insert($vertex, -INF);
            }
        }

        $this->distances[$start] = 0;
        $this->queue->insert($start, 0);

        while (!$this->queue->isEmpty()) {
            $current = $this->queue->extract();
            
            if ($this->distances[$current] == INF) {
                break;
            }

            foreach ($this->graph[$current] as $neighbor => $cost) {
                $alt = $this->distances[$current] + $cost;
                if ($alt < $this->distances[$neighbor]) {
                    $this->distances[$neighbor] = $alt;
                    $this->previous[$neighbor] = $current;
                    $this->queue->insert($neighbor, -$alt);
                }
            }
        }
    }

    public function shortestPath($target) {
        $path = [];
        while (isset($this->previous[$target])) {
            array_unshift($path, $target);
            $target = $this->previous[$target];
        }
        return $path;
    }

    public function getDistance($target) {
        return $this->distances[$target];
    }
}

$graph = [
    'Titiwangsa' => ['Chow Kit' => 1.5, 'PWTC' => 1],
    'Chow Kit' => ['Titiwangsa' => 1.5, 'Dang Wangi' => 3],
    'PWTC' => ['Titiwangsa' => 1, 'Medan Tuanku' => 1.5, 'Masjid Jamek' => 2.1],
    'Dang Wangi' => ['Chow Kit' => 3, 'Bukit Nanas' => 1.7, 'Hang Tuah' => 2.45, 'Bukit Bintang' => 1.4],
    'Bukit Nanas' => ['Dang Wangi' => 1.7, 'Bukit Bintang' => 1],
    'Hang Tuah' => ['Dang Wangi' => 2.45, 'KL Sentral' => 3.1],
    'Bukit Bintang' => ['Dang Wangi' => 1.4, 'Bukit Nanas' => 1, 'KL Sentral' => 3.5],
    'Medan Tuanku' => ['PWTC' => 1.5, 'Masjid Jamek' => 2.1],
    'Masjid Jamek' => ['PWTC' => 2.1, 'Medan Tuanku' => 2.1, 'KL Sentral' => 2.35],
    'KL Sentral' => ['Hang Tuah' => 3.1, 'Bukit Bintang' => 3.5, 'Masjid Jamek' => 2.35]
];

$graphObj = new Graph($graph);
$start = 'Titiwangsa';
$end = 'KL Sentral';

$graphObj->dijkstra($start);
$path = $graphObj->shortestPath($end);
$distance = $graphObj->getDistance($end);

array_unshift($path, $start); // Add the start node to the path

echo "<h2>Shortest path from $start to $end is:</h2>";
echo "<p>" . implode(' -> ', $path) . "</p>";
echo "<p>Distance: $distance KM</p>";
?>
