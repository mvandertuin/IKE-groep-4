<?php
useLib('graph');
global $session;
$g = new UserGraph($session['loginID']);
$n = $g->getHighestRatedNode();
$top10 = $g->getHighestRatedNodes();
$flop10 = $g->getLowestRatedNodes();
$c=0;
$edges = array();
$nodes = array();
$rank = 1;
function intop10($arr, $id){
	foreach($arr as $top){
		if($top[1]->getID() == $id){
			return true;
		}
	}
	return false;
}
foreach($top10 as $top){
	$naam = $top[1]->getName();
	$nodes[] = '"'.str_replace("-","",str_replace(" ", "", $naam)).'"'.':{"color":"#A67B00","shape":"rectangle","label":"'.$rank.'. '.$naam.'"}';

	//Get connections and max connections
	$edge = $top[1]->getConnections();
	if(count($edge)>3){
		$max = 3;
	}else{
		$max = count($edge);
	}
	
	if($max > 0){
		$connection = '"'.str_replace("-","",str_replace(" ", "", $naam)).'"'.":{ ";
		$komma = "";
		$i = 0;
		$opgenomen = 0;
		while($opgenomen<$max && $i<count($edge)){
			$connects = $edge[$i]->otherNode($top[1]);
			if(!intop10($flop10, $connects->getID())){
				$length = rand(1,1000);
				if($length>100){
					$length = 100;
				}
				if(!intop10($top10, $connects->getID())){
					$nodes[] = '"'.str_replace("-","",str_replace(" ", "", $connects->getName())).'"'.':{"color":"#949494","shape":"rectangle","label":"'.$connects->getName().'"}';
				}
				$connection .= $komma.'"'.str_replace("-","",str_replace(" ", "", $connects->getName())).'"'.':{"length":'.$length .'}';
				$komma = ", ";
				$opgenomen++;
			}
			$i++;
		}
		$connection .= " }";
		$edges[] = $connection;
	}
	$rank++;
}

$nodes = array_unique($nodes);
print('{"nodes":{');
//print json parsed nodes
$kom = "";
foreach($nodes as $node){
	print($kom.$node);
	$kom = ",";
}
print('},"edges":{');
$kom = "";
foreach($edges as $edge){
	print($kom.$edge);
	$kom = ",";
}
print("}}");
?>