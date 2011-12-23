<?php
//Graph classes
class Node{
	private $name = '';
	private $displayName = '';
	private $value = 100;
	private $connections = array();
	private $modified = false;
	private $id = -1;
	
	/**
	 * Create a new Node object
	 */
	function __construct($id, $name = '', $displayName = '', $value = 100){
			$this->id = $id;
			$this->name = $name;
			$this->displayName = $displayName;
			$this->value = $value;
	}
	
	/*
	 * Add a connection to another node to this node
	 */
	function addConnection(Edge $e){
		$this->connections[] = $e;
	}
	
	/*
	 * Returns the value of this node
	 */
	function getValue(){
		return $this->value;
	}
	
	/*
	 * Changes the value of this node. If this value isn't specified, it will be reset to the default
	 */
	function changeValue($newValue = 100){
		$this->value = $newValue;
		$this->modified = true;
	}
	
	/*
	 * Returns the name of the node. By default the displayname is returned.
	 */
	function getName($displayName = true){
		if($displayName){
			return $this->displayName;
		}else{
			return $this->name;
		}		
	}
	
	/*
	 * Returns the ID of this node
	 */
	function getID(){
		return $this->id;
	}
	
	/*
	 * Changes the displayname of this node. The internal name cannot be modified
	 */
	function changeName($newName){
		$this->displayName = $newName;
		$this->modified = true;
	}
	
	/*
	 * Returns true if this node is connected to other nodes
	 */
	function hasConnections(){
		return count($this->connections)!=0;
	}
	
	/*
	 * Returns an array of connections to other nodes (or an empty array)
	 */
	function getConnections(){
		return $this->connections;
	}
	
	function isModified(){
		return $this->modified;
	}
	
	function toString(){
		$r='[Node'.$this->id.'{name: \''.$this->getName().'\', edges: (';
		$f = true;
		foreach($this->getConnections() as $c){
			if($f){
				$f= false;
			}else{
				$r.=',';
			}
			$r.= $c->getID();
		}
		$r.=')}]';
		return $r;
		
	}
	
	/*
	 * Returns true if a similar Node can be found in the database
	 */
	static function exists($name){
		$q = "SELECT nodeID FROM ike_graph WHERE nodeName = :name";
		global $db;
		if(!isset($db)){
			throw new Exception('Database connection required!', -1);
		}
		$qa = $db->prepare($q);
		$qa->bindParam(':name', $name);
		$qa->bindColumn('nodeID', $id);
		$qa->execute();
		if($qa->fetch()){
			return $id;
		}else{
			return false;
		}
	}
	
	/*
	 * Returns true if a similar Node can be found in the database, but less strict than exists
	 */
	static function find($name){
		$q = "SELECT nodeID FROM ike_graph WHERE displayName LIKE :name";
		global $db;
		if(!isset($db)){
			throw new Exception('Database connection required!', -1);
		}
		$qa = $db->prepare($q);
		$qa->bindParam(':name', $name);
		$qa->bindColumn('nodeID', $id);
		$qa->execute();
		if($qa->fetch()){
			return $id;
		}else{
			return false;
		}
	}
	
	/*
	 * Creates a nwe node, saves its data in the database and returns the new node as a Node object
	 */
	static function create($name, $displayName, $value){
		global $db;
		if(!isset($db)){
			throw new Exception('Database connection required!', -1);
		}
		$q = "INSERT INTO ike_graph(nodeName, displayName, value) VALUES (:name, :dispname, :value)";
		$qn = $db->prepare($q);
		$qn->bindParam(':name', $name);
		$qn->bindParam(':dispname', $displayName);
		$qn->bindParam(':value', $value, PDO::PARAM_INT);
		$qn->execute();
		$id = $db->lastInsertId();
		return new Node($id, $name, $displayName, $value);
	}
}

class Edge{
	private $left;
	private $right;
	private $weight = 1;
	private $modified = false;
	private $id = -1;
	function __construct($id, Node $left, Node $right, $weight){
		$this->left = $left;
		$this->right = $right;
		$this->weight = $weight;
		$this->id = $id;
	}
	function getWeight(){
		return $this->weight;
	}
	function getNodes(){
		return array($this->left, $this->right);
	}
	function changeWeight($newWeight = 100){
		$this->weight = $newWeight;
		$this->modified = true;
	}
	function getID(){
		return $this->id;
	}
	function isModified(){
		return $this->modified;
	}
	function otherNode(Node $c){
		//echo $this->toString();
		if($this->left->getID()==$c->getID()){
			//echo 'r';
			return $this->right;
		}else{
			//echo 'l';
			return $this->left;
		}
	}
	function toString(){
		return '[Edge'.$this->id.'{left: \''.$this->left->getName().'('.$this->left->getID().')\', right: \''.$this->right->getName().'('.$this->right->getID().')\'}]';
	}
	static function create(Node $left, Node $right, $weight){
		global $db;
		if(!isset($db)){
			throw new Exception('Database connection required!', -1);
		}
		$q = "INSERT INTO ike_edges(node1, node2, weight) VALUES(:n1, :n2, :w)";	
		$qn = $db->prepare($q);
		$n1 = min($left->getID(), $right->getID());
		$n2 = max($left->getID(), $right->getID());
		$qn->bindParam(':n1', $n1, PDO::PARAM_INT);
		$qn->bindParam(':n2', $n2, PDO::PARAM_INT);
		$qn->bindParam(':w', $weight, PDO::PARAM_INT);
		$qn->execute();
		$id = $db->lastInsertId();
		return new Edge($id, $left, $right, $weight);
	}
	static function exists(Node $left, Node $right){
		global $db;
		if(!isset($db)){
			throw new Exception('Database connection required!', -1);
		}
		$n1 = min($left->getID(), $right->getID());
		$n2 = max($left->getID(), $right->getID());
		$q = "SELECT edgeID FROM ike_edges WHERE node1 = :n1 AND node2 = :n2";
		$qa = $db->prepare($q);
		$qa->bindParam(':n1', $n1, PDO::PARAM_INT);
		$qa->bindParam(':n2', $n2, PDO::PARAM_INT);
		$qa->bindColumn('edgeID', $id);
		$qa->execute();
		if($qa->fetch()){
			return $id;
		}else{
			return false;
		}
		
	}
}

class Graph{
	protected $nodes = array();
	protected $edges = array();
	function __construct(){
		global $db;
		if(!isset($db)){
			throw new Exception('Database connection required!', -1);
		}
		//Step 1: load all nodes from database
		$q = "SELECT nodeID, nodeName, displayName, value FROM ike_graph";
		$qn = $db->prepare($q);
		$qn->bindColumn('nodeID', $id);
		$qn->bindColumn('nodeName', $name);
		$qn->bindColumn('displayName', $dispName);
		$qn->bindColumn('value', $value);
		$qn->execute();
		while($qn->fetch()){
			$this->nodes[$id] = new Node($id, $name, $dispName, $value);			
		}
		
		//Step 2: load all edges from database
		$q = "SELECT edgeID, node1, node2, weight FROM ike_edges";
		$qe = $db->prepare($q);
		$qe->bindColumn('edgeID', $id);
		$qe->bindColumn('node1', $n1);
		$qe->bindColumn('node2', $n2);
		$qe->bindColumn('weight', $weight);
		$qe->execute();
		while($qe->fetch()){
			$node1 = $this->nodes[$n1];
			$node2 = $this->nodes[$n2];
			$e = new Edge($id, $node1, $node2, $weight);
			$this->edges[$id] = $e;
			$node1->addConnection($e);
			$node2->addConnection($e);			
		}
	}
	
	function getNodes(){
		return $this->nodes;
	}
	
	function getEdges(){
		return $this->edges;
	}
	
	function build($printStatus = false){
		$changes = 0;
		$sparql = sparql_connect( "http://dbpedia.org/sparql");
		foreach($this->nodes as $node){
			if($printStatus) print($node->getID().'>'.$node->getName()."\r\n");
			$en = uri_prep($node->getName(false));
			$pre = "PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX foaf: <http://xmlns.com/foaf/0.1/>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX : <http://dbpedia.org/resource/>
PREFIX dbpedia2: <http://dbpedia.org/property/>
PREFIX dbpedia: <http://dbpedia.org/>
PREFIX skos: <http://www.w3.org/2004/02/skos/core#>";
			$q1 = "SELECT ?y WHERE { ?y dbpedia-owl:stylisticOrigin ".$en."}";
			//var_dump($q1);
			$result = $sparql->query($pre."\r\n".$q1);
			if( !$result ) { 
				throw new Exception($sparql->errno() . ": " . $sparql->error(). "\n");
			}
			while( $row = $result->fetch_array( $result ) ){
				//var_dump($row);exit;
				$newName = filter_uri($row['y']);
				
				//Check if the node is in the graph
				$id = Node::exists($newName);
				if($id===false){
					$newNode = Node::create($newName, $newName, 100);
					$this->nodes[$newNode->getID()] = $newNode;
					$changes++;
				}else{
					$newNode = $this->nodes[$id];
				}
				
				//Check if this edge is in the graph
				$eid = Edge::exists($node, $newNode);
				if($eid===false){
					$newEdge = Edge::create($node, $newNode, 1);
					$this->edges[$newEdge->getID()] = $newEdge;
					$node->addConnection($newEdge);
					$newNode->addConnection($newEdge);
					$changes++;
				}
			}
		}
		return $changes;
	}
	/**
	 * Experimental function to export a graph to a dot file (GraphViz file)
	**/
	function export($destination){
		$lines = array();
		$lines[] = "Graph G{";
		foreach($this->nodes as $node){
			$lines[] = 'n'.$node->getID().' [label="'.$node->getName().'"];';
		}
		foreach($this->edges as $edge){
			$ns = $edge->getNodes();
			$lines[] = 'n'.$ns[0]->getID().' -- n'.$ns[1]->getID().';';	
		}
		$lines[] = 'splines=true;';
		$lines[] = '}';
		$fileHandle = fopen($destination, 'w');
		foreach($lines as $line){
			fwrite($fileHandle, $line."\n");
		}
		fclose($fileHandle);
	}
}

function filter_uri($uri){
	$p1 = str_replace('http://dbpedia.org/resource/', '', $uri);
	return str_replace('_', ' ', $p1);
}
function uri_prep($tag){
	return '<http://dbpedia.org/resource/'.str_replace(' ', '_', $tag).'>';
}
class UserGraph extends Graph{
	/**
	 * Constructs a personalized graph
	 */
	function __construct($userID){
		parent::__construct();
		global $db;
		if(!isset($db)){
			throw new Exception('Database connection required!', -1);
		}
		$q = "SELECT nodeID, rating, album_id FROM user_album_rating JOIN ike_mbid_node ON album_id = mbid WHERE user_id = :uid ORDER BY album_id";
		$mq = $db->prepare($q);
		$mq->bindParam(':uid', $userID);
		$mq->bindColumn('nodeID', $nodeID);
		$mq->bindColumn('rating', $rating);
		$mq->bindColumn('album_id', $albumID);
		$mq->execute();
		$prevAlbum = '';
		$changeEdge = 10;//dummy, will be replaced but wont trigger adding a new edge
		$list = array();
		while($mq->fetch()){
			//Apply userrating to a node and its edges
			if($albumID != $prevAlbum){
				$prevAlbum = $albumID;
				if($changeEdge<1){
					for($i = 0; $i<count($list)-1; $i++){
						for($j = $i+1; $j<count($list); $j++){
							if(!$this->hasEdge($this->nodes[$list[$i]], $this->nodes[$list[$j]])){
								$nid = max(array_keys($this->edges))+1;
								$e = new Edge($nid, $this->nodes[$list[$i]], $this->nodes[$list[$j]], 100*$changeEdge);
								$this->edges[$nid] = $e;
								$this->nodes[$list[$i]]->addConnection($e);
								$this->nodes[$list[$j]]->addConnection($e);
							}					
						}
					}
				}
				$list = array();
			}
			$list[] = $nodeID;
			if($rating>0){
				$changeNode = 1.1;
				$changeEdge = 0.9;
			}else{
				$changeNode = 0.9;
				$changeEdge = 1.1;
			}
			$node = $this->nodes[$nodeID];
			$edges = $node->getConnections();
			$node->changeValue($node->getValue()*$changeNode);
			foreach($edges as $edge){
				$edge->changeWeight($edge->getWeight()*$changeEdge);
			}
			
		}
	}
	
	/**
	 * Checks if a connection is in this graph (not in the database!)
	 */
	function hasEdge($from, $to){
		$pos = $this->nodes[$from->getID()]->getConnections();
		foreach($pos as $edge){
			$nodes = $edge->getNodes();
			if($nodes[0]==$to||$nodes[1]==$to){
				return true;
			}
		}
		return false;
	}	
	
	private function getEdge($from, $to){
		$pos = $this->nodes[$from->getID()]->getConnections();
		foreach($pos as $edge){
			$nodes = $edge->getNodes();
			if($nodes[0]==$to||$nodes[1]==$to){
				return $edge;
			}
		}
		return null;		
	}
	
	/**
	 * Returns the node with the highest user-rating
	 */
	function getHigestRatedNode(){
		$w = 0;
		$max = null;
		foreach($this->nodes as $node){
			if($node->getValue()>$w){
				$max = $node;
				$w = $max->getValue();
			}
		}
		return $max;
	}
	function getHigestRatedNodes(){
		$result = array();
		$best = $this->getHigestRatedNode();
		$result[] = array($best->getValue(), $best);
		for($i=1;$i<10;$i++){
			$result[] = $this->findNextHighest($result);
		}
		return $result;
	}
	private function findNextHighest($from){
		$connectedNodes = array();
		foreach($from as $node){
			$edges = $node[1]->getConnections();
			foreach($edges as $edge){
				//echo $edge->toString();
				$connectedNode = $edge->otherNode($node[1]);
				//$connectedNode->toString();
				//$connectedNode->changeValue($connectedNode->getValue()-$edge->getWeight());//Distance penalty
				$connectedNodes[] = array($connectedNode->getValue()-$edge->getWeight(), $connectedNode);
			}
		}
		usort($connectedNodes, create_function('$a,$b','return $b[0] - $a[0];'));
		$i = 0;
		while(isInList($from, $connectedNodes[$i][1])){
			$i++;
		}
		return $connectedNodes[$i];
	}
}
function isInList($list, $element){
	foreach($list as $e){
		if($e[1]->getID()==$element->getID()){
			return true;
		}
	}
	return false;
}