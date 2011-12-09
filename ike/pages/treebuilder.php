<?php
set_time_limit(0);
useLib('genretree');
useLib('sparqllib');
$root = new GenreNode(0, 'root', null);
$nodes = array();
$nodes[] = $root;
$nodes = array_merge($nodes, $root->getChildren());
foreach($root->getChildren() as $new){
	$nodes[$new->getID()] = $new;
}
for($i = 0; $i<count($nodes); $i++){
	$node = $nodes[$i];
	echo $node->getID().'>'.$node->getName()."\r\n";
	foreach($node->getChildren() as $child){
		if($child->getID>$node->getID()){
			GenreNode::build($child);
			foreach($child->getChildren() as $new){
				$nodes[$new->getID()] = $new;
			}
		}
	}
}
buildFrom($root);

?>