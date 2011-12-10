<?php
//Music genres tree

class GenreNode{
	private $gnId = 0;
	private $name = '';
	private $parent = null;
	private $children = array();
	function getName(){
		if(empty($this->name)){
			global $db;
			if(!isset($db)){
				throw new Exception('No database connection!', -1);
			}
			$stmt = $db->prepare("SELECT nodeName FROM ike_tree WHERE nodeID = :id");
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->bindColumn('name', $newName);
			if(!$stmt->fetch(PDO::FETCH_BOUND)){
				throw new Exception('Wasn\'t able to fetch name', -1);
			}
			$this->name = $newName;
		}
		return $this->name;
	}
	
	function getChildren(){
		return $this->children;
	}
	function hasChild(){
			return count($this->children)!=0;
	}
	function addChild(GenreNode $new){
		$this->children[] = $new;		
	}
	function getID(){
		return $this->gnId;
	}
	function __construct($id, $newName='', $parent = null){
		global $db;
		if(!isset($db)){
			throw new Exception('No database connection!', -1);
		}
		$this->gnId = $id;
		if($id==-1){
			$tq = $db->prepare("SELECT nodeID FROM ike_tree WHERE nodeName = :s");
			$tq->bindParam(":s", $newName);
			$tq->bindColumn('nodeID', $exId);
			$tq->execute();
			if($tq->fetch()){
				$this->gnId = $exId;		
			}else{
				$q = $db->prepare("INSERT INTO ike_tree(nodeName, parent) VALUES(:name, :parent)");
				//$q->bindParam(":id", $id, PDO::PARAM_INT);
				$q->bindParam(":parent", $parent, PDO::PARAM_INT);
				$q->bindParam(":name", $newName);
				$q->execute();
				$this->gnId = $db->lastInsertId();
			}
		}
		if(empty($newName)){
			$this->name = $this->getName();
		}else{
			$this->name = $newName;
		}
		$stmt = $db->prepare("SELECT nodeID, nodeName FROM ike_tree WHERE parent = :id");
		$stmt->bindParam(":id", $this->gnId, PDO::PARAM_INT);
		$stmt->bindColumn('nodeID', $subId);
		$stmt->bindColumn('nodeName', $subName);
		$stmt->execute();
		while($stmt->fetch()){
			$this->addChild(new GenreNode($subId, $subName, $this->getID()));			
		}		
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
				$from->addChild(new GenreNode(-1, filter_uri($row['y']), $from->getID()));
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