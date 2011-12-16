<?php
//Music genres tree

class GenreMan{
	static $edges = array();
	static $nodes = array();
}

class GenreEdge{
	private $edgeID = 0;
	private $node1 = null;
	private $node2 = null;
	private $weight = 1;
	function __construct($id = -1, $n1 = null, $n2 = null, $weight = 1){
		$this->edgeID = $id;
		$this->node1 = $n1;
		$this->node2 = $n2;	
		$this->weight = $weight;
		if($this->edgeID==-1){
			//$this->
			global $db;
			if(!isset($db)){
				throw new Exception('No database connection!', -1);
			}
			$stmt = $db->prepare("INSERT INTO ike_edges(node1, node2, weight) VALUES(:n1, :n2, :w)");
			$i1 =  $this->node1->getID();
			$i2 = $this->node2->getID();
			$stmt->bindParam(':n1', $i1, PDO::PARAM_INT);
			$stmt->bindParam(':n2', $i2, PDO::PARAM_INT);
			$stmt->bindParam(':w', $this->weight, PDO::PARAM_INT);
			$this->edgeID = $db->lastInsertId();
		}
		if(isset(GenreMan::$edges[$this->edgeID])){
			throw new Exception('New edge exists already!');
		}
		print_r($this);
		GenreMan::$edges[(int)$this->edgeID] = (object)$this;
		//print("Edges: ".count(GenreEdge::$list)."\r\n");
		/*if(!$n1->connectedTo($n2)){
			$n1->addConnection($n1, $weight);
		}
		if(!$n2->connectedTo($n1)){
			$n1->addConnection($n2, $weight);
		}*/
	}
	function getNodes(){
		return array($this->node1, $this->node2);
	}
	function otherNode($node){
		if($node1 === $node){
			return $node2;
		}else{
			return $node1;
		}
	}
	function getWeight(){
		return $this->weight;
	}
	function getID(){
		return $this->edgeID;
	}
	static function exists($node1, $node2){
		$id1 = $node1->getID();
		$id2 = $node2->getID();
		$low = min($id1, $id2);
		$hi = max($id1, $id2);
		global $db;
		if(!isset($db)){
			throw new Exception('No database connection!', -1);
		}		
		$q = $db->prepare("SELECT edgeID FROM ike_edges WHERE node1 = :n1 AND node2 = :n2");
		$q->bindParam(':n1', $low, PDO::PARAM_INT);
		$q->bindParam(':n2', $hi, PDO::PARAM_INT);
		$q->bindColumn('edgeID', $id);
		$q->execute();
		if($q->fetch()){
			//print_r(GenreEdge::$list);
			if(!isset(GenreMan::$edges[$id])){
				$e = new GenreEdge($id, ($node1->getID()==$low)? $node1 : $node2, ($node1->getID()!=$low)? $node1 : $node2, 1);
			}
			//GenreEdge::$list[$id];
			return $id;
		}else{
			return -1;
		}
	}
	static function getEdge($id){
		return GenreMan::$edges[$id];
	}
		
}

class GenreNode{
	private $gnId = 0;
	private $name = '';
	private $dispName = '';
	private $edges = array();
	function getName($displayName = true){
		if(empty($this->name)){
			global $db;
			if(!isset($db)){
				throw new Exception('No database connection!', -1);
			}
			$stmt = $db->prepare("SELECT nodeName, displayName FROM ike_graph WHERE nodeID = :id");
			$id = $this->gnId;
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->bindColumn('nodeName', $newName);
			$stmt->bindColumn('displayName', $display);
			$stmt->execute();
			if(!$stmt->fetch(PDO::FETCH_BOUND)){
				throw new Exception('Wasn\'t able to fetch name: '.print_r($db->errorInfo(), true), -1);
			}
			$this->name = $newName;
			$this->dispName = $display;
		}
		return ($displayName)? $this->dispName : $this->name;
	}
	
	function getEdges(){
		return $this->edges;
	}
	function addConnection(GenreNode $new, $w){
		if(!$this->connectedTo($new)){
			$eid = GenreEdge::exists($this, $new);
			if($eid!=-1){
				$this->edges[] = GenreEdge::getEdge($eid);
			}else{				
				$this->edges[] = new GenreEdge(-1, $this, $new, $w);
			}
		}
	}
	function connectedTo(GenreNode $o){
		foreach($this->edges as $e){
			if($e->getOther($this) === $o){
				return true;
			}
		}
		return false;
	}
	function getID(){
		return $this->gnId;
	}
	function __construct($id, $newName='', $displayName = ''){
		global $db;
		if(!isset($db)){
			throw new Exception('No database connection!', -1);
		}
		$this->gnId = $id;
		if($id==-1){
			$tq = $db->prepare("SELECT nodeID FROM ike_graph WHERE nodeName = :s");
			$tq->bindParam(":s", $newName);
			$tq->bindColumn('nodeID', $exId);
			$tq->execute();
			if($tq->fetch()){
				$this->gnId = $exId;		
			}else{
				$q = $db->prepare("INSERT INTO ike_graph(nodeName, displayName) VALUES(:name, :disname)");
				//$q->bindParam(":id", $id, PDO::PARAM_INT);
				$q->bindParam(":disname", $displayName);
				$q->bindParam(":name", $newName);
				$q->execute();
				$this->gnId = $db->lastInsertId();
				/*$q2 = $db->prepare("INSERT INTO ike_edges(node1, node2) VALUES(:n1, :n2)");
				$q2->bindParam(':n1', $this->gnId);
				foreach($parent as $p){
					$q2->bindParam(':n2', $p->getID());
					$q2->execute();
				}*/
			}
		}
		if(empty($newName)){
			$this->name = $this->getName(false);
		}else{
			$this->name = $newName;
			$this->dispName = $displayName;
		}
		GenreMan::$nodes[$this->gnId] = $this;
		//print("Nodes: ".count(GenreNode::$list)."\r\n");
		
		$stmt = $db->prepare("SELECT edgeID, node2, weight FROM ike_edges WHERE node1 = :id");
		$stmt->bindParam(":id", $this->gnId, PDO::PARAM_INT);
		$stmt->bindColumn('node2', $other);
		$stmt->bindColumn('edgeID', $edge);
		$stmt->bindColumn('weight', $w);
		$stmt->execute();
		while($stmt->fetch()){
			//echo $other;
			if(isset(GenreMan::$nodes[$other])){
				$n =  GenreMan::$nodes[$other];
			}else{
				$n = new GenreNode($other, '', '');
			}
			$e = GenreEdge::exists($this, $n);
			if($e===-1){
				$this->addConnection($n, $w);
			}else{
				$this->edges[] = $e;
			}
		}
		/*
		$stmt = $db->prepare("SELECT nodeID, nodeName FROM ike_graph WHERE parent = :id");
		$stmt->bindParam(":id", $this->gnId, PDO::PARAM_INT);
		$stmt->bindColumn('nodeID', $subId);
		$stmt->bindColumn('nodeName', $subName);
		$stmt->execute();
		while($stmt->fetch()){
			$this->addChild(new GenreNode($subId, $subName, $this->getID()));			
		}	
		*/	
	}
	
	static function build(GenreNode $from){
		try{
			$sparql = sparql_connect( "http://dbpedia.org/sparql");
			$en = uri_prep($from->getName());
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
			$n = new GenreNode(-1, filter_uri($row['y']), filter_uri($row['y']));
			$from->addConnection($n, 1);
			}
		}catch(Exception $e){		
			throw($e);
		}
	}
}
function filter_uri($uri){
	$p1 = str_replace('http://dbpedia.org/resource/', '', $uri);
	return str_replace('_', ' ', $p1);
}
function uri_prep($tag){
	return '<http://dbpedia.org/resource/'.str_replace(' ', '_', $tag).'>';
}