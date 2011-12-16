<?php
set_time_limit(0);
include('framework/config.php');
include('framework/basic.php');
useLib('database');
dbStart();
include('framework/genretree.php');
include('framework/sparqllib.php');
$root = new GenreNode(0, 'root', 'root');
print_r($root);
$nodes = array();
$nodes[] = $root;
//$nodes = array_merge($nodes, $root->getChildren());
print_r($root->getEdges());
foreach($root->getEdges() as $new){
	$nodes[$new->other($root)->getID()] = $new->other($root);
}
for($i = 0; $i<count($nodes); $i++){
	$node = $nodes[$i];
	echo $node->getID().'>'.$node->getName()."\r\n";
	foreach($node->getEdges() as $child){
		//if($child->other($node)->getID() > $node->getID()){
			GenreNode::build($child>other($node));
			foreach($child->other($node)->getEdges() as $new){
				$nodes[$new->other($child)->getID()] = $new>-other($child);
			}
		//}
	}
}

?>