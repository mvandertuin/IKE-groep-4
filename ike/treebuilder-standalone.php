<?php
set_time_limit(0);
include('framework/config.php');
include('framework/basic.php');
useLib('database');
dbStart();
include('framework/graph.php');
include('framework/sparqllib.php');
$graph = new Graph();
//var_dump($graph);
do{
	$changes = $graph->build(true);
}while($changes!=0);
//DO NOT var_dump! cycles...
//var_dump($graph);
?>