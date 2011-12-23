

<?php
//Empty example page
global $scripts;
global $db;
global $db_tableprefix;
//add names of JS files to $scripts array if needed

//Include helper functions for generating an HTML page
useLib('htmlpage');
useLib('graph');

//generate page header
//fw_header('Inloggen');

function getTags($mbid){
	try {
		$r = new HttpRequest("http://www.chl43.nl:3000/ws/2/artist/".$mbid."?inc=tags");
		$h= $r->getHeaders();
		$h['User-Agent'] = 'IKE G4 0.1';
		$r->setHeaders($h);
		$r->send();
		if ($r->getResponseCode() == 200) {
			$xmlResponse = simplexml_load_string($r->getResponseBody());
			$ret = array();
			$i = 0;
			foreach($xmlResponse->artist->{"tag-list"}->children() as $tag){
				$ret[$i] = $tag->name;
				$i++;
			};
			return $ret;
		}
		else {
			print($r->getResponseCode());
			return null;
		}
	} catch (HttpException $ex) {
		echo $ex;
	}
}


//Check for login
if($session['loginID'] > 0){
	//Check if Post is valid
	if(isset($_POST["rhash"]) && isset($_POST["mbid"]) && isset($_POST["albumname"]) && isset($_POST["rat"])){
				//Insert rating
		$q1 = $db->prepare("SELECT * FROM user_album_rating WHERE user_id = :uid AND album_id = :aid;");
		$q1->bindParam(':uid', $session['loginID']);
		$q1->bindParam(':aid', $_POST["mbid"]);
		if($q1->execute()){
			$arr = $q1->fetchAll();
			if(count($arr)>0){
				$q = $db->prepare("UPDATE user_album_rating SET rating = :rat WHERE user_id = :uid AND album_id = :aid LIMIT 1 ;");
				//Insert rating
				$q->bindParam(':uid', $session['loginID']);
				$q->bindParam(':aid', $_POST["mbid"]);
				$q->bindParam(':rat', $_POST["rat"]);
			
				//Check if query is executed
				if($q->execute()){
					print "U had al gestemd, uw stem is geupdate.";
				}else{
					print("SQL ERROR: <br />");
					print $q->errorCode();
					print_r($q->errorInfo());
				}
			}else{
				//Insert rating
				$q = $db->prepare("INSERT INTO user_album_rating (user_id, album_id, rating) VALUES (:uid, :aid, :rating)");
				$q->bindParam(':uid', $session['loginID']);
				$q->bindParam(':aid', $_POST["mbid"]);
				$q->bindParam(':rating', $_POST["rat"]);
			
				//Check if query is executed
				if($q->execute()){
					print "Uw stem is toegevoegd";
				}else{
					print("SQL ERROR: <br />");
					print $q->errorCode();
					print_r($q->errorInfo());
				}
			}
			
			$tags = getTags($_POST["mbid"]);
			foreach($tags as $unit){
				$nodeId = Node::find($unit);
				if($nodeId != false){
					$q = "SELECT cID FROM ike_mbid_node WHERE nodeID = :name AND mbID = :mbid";
					if(!isset($db)){
						throw new Exception('Database connection required!', -1);
					}
					$qa = $db->prepare($q);
					$qa->bindParam(':name', $nodeId);
					$qa->bindParam(':mbid', $_POST["mbid"]);
					$qa->bindColumn('cID', $id);
					$qa->execute();
					if(!$qa->fetch()){
						$q = "INSERT INTO  `musicdat`.`ike_mbid_node` (`cID` ,`mbID` ,`nodeID`)VALUES (NULL ,  :mbid,  :NodeId);";
						$insq = $db->prepare($q);
						$insq->bindParam(':mbid', $_POST["mbid"]);
						$insq->bindParam(':NodeId', $nodeId);
						
						if($insq->execute()){
							print("");
						}else{
								print("SQL ERROR: <br />");
								print $insq->errorCode();
								print_r($insq->errorInfo());
						}
					}
				}
			}
		}
	}else{
		var_dump($_POST);
		print("Error: Een van de postvariabelen is niet meegegeven.");
	}
}else{
	print("Error: U bent niet ingelogd");
}
	
//fw_footer();
?>