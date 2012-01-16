
<?php
useLib('htmlpage');
useLib('graph');
//global $session;

//useLib('musicbrainz');
global $db;
global $servername;
global $session;
$servername = "http://wwww.chl43.nl:3000";
//generate page header
//fw_header('Suggesties');
$votedon = getVotedOn($db, $session['loginID']);
$new = isset($_POST['newtrack']);
outputTags($session['loginID'], $votedon, $new);

// Functie getRating zorgt voor het opvragen van de rating van een bepaalde artiest.
function getRating($mbid) {
	// De query voor het opvragen van de rating op MusicBrainz.
	$r = new HttpRequest('http://musicbrainz.org/ws/2/artist/'.$mbid.'?inc=ratings');
	try {
		$r->send();
		if ($r->getResponseCode() == 200) {
			// Ouput is een XML bestand, die wordt ingelezen dmv de SimpleXML methode.
			$xmlResponse = simplexml_load_string($r->getResponseBody());
			$response = $xmlResponse->children();
			$response = $response->children();
			// Uit het XML bestand wordt het onderdeel rating opgevraagd, deze wordt vervolgens teruggegeven.
			return $response->rating;
    }
	} catch (HttpException $ex) {
		return null;
	}
}

function getRatingByMbid($array, $mbid){
	if(!empty($array)){
		foreach($array as $row){
			if($row[0] == $mbid){
				return $row[1];
			}
		}
	}
	return 0;
}

function getVotedOn($db, $uid){
		$q1 = $db->prepare("SELECT album_id, rating FROM user_album_rating WHERE user_id = :uid");
		$q1->bindParam(':uid', $uid);
		
		if($q1->execute()){
			$ret = $q1->fetchAll();
			return $ret;
		}else{
			print_r($q->errorInfo());
		}
}
//Functie getLink geeft een 2dimensionale Array terug met alle links en bijbehorende namen.
function getLink($mbid) {
global $servername;
$r = new HttpRequest($servername.'/ws/2/artist/'.$mbid.'?inc=url-rels');
	
	try {
		$r->send();
		if ($r->getResponseCode() == 200) {
			// Ouput is een XML bestand, die wordt ingelezen dmv de SimpleXML methode.
			$xmlResponse = simplexml_load_string($r->getResponseBody());
			$response = $xmlResponse->children();
			$response = $response->children();
			// Vraag de lijst met URLs op
			$response = $response->{"relation-list"};
			// Maak nieuwe array aan waar het resultaat in komt te staan
			$res = array();
			// In foreachloop type en target opvragen en in $res zetten.
			foreach ($response->relation as $relation){
				$name = "".$relation->attributes()->type; // Cast naar String
				$res[$name]=$relation->target;
			}
			return $res;
    }
	} catch (HttpException $ex) {
		return null;
	}
}

// Functie getArtistsByTag zoekt artiesten aan de hand van tags
function getArtistsByTag($tags, $max) {
	try{
		// In deze forloop wordt per genre een query op de musicbrainz dataset gevuurd.
		$res=array();
		for($i = 0; $i<$max; $i++) {
			try {
				$r = new HttpRequest("http://ws.audioscrobbler.com/2.0/?method=tag.gettopartists&tag=".str_replace(" ","%20",$tags[$i])."&limit=10&api_key=184af8b6220039e4cb8167a5e2bb23e3");
				$h= $r->getHeaders();
				$h['User-Agent'] = 'IKE G4 0.1';
				$r->setHeaders($h);
				$r->send();
				if ($r->getResponseCode() == 200) {
					$xmlResponse = simplexml_load_string($r->getResponseBody());
					$response = $xmlResponse->children();
					$response = $response->children();		
					foreach($response as $child){
						if($child->mbid != ""){
							if(!isShown($child->mbid)){
								$res[]=$child->mbid;
								return $res;
							}
						}
					}
				}
			} catch (HttpException $ex) {
				echo $ex;
			}
			
		}
		return $res;
	} catch (HttpException $ex) {
		echo $ex;
	}
}


function getTrackByArtist($artist) {
	try {
			$r = new HttpRequest("http://ws.audioscrobbler.com/2.0/?method=artist.gettoptracks&mbid=".$artist."&limit=1&api_key=184af8b6220039e4cb8167a5e2bb23e3");
			$h= $r->getHeaders();
			$h['User-Agent'] = 'IKE G4 0.1';
			$r->setHeaders($h);
			$r->send();
			if ($r->getResponseCode() == 200) {
				$xmlResponse = simplexml_load_string($r->getResponseBody());
				$response = $xmlResponse->children();
				$response = $response->children();
				$res=array();
				$res["name"]=(string)$response->track->name;
				$res["mbid"]=(string)$response->track->id;
				foreach($response->track->image as $child){
					if($child->attributes()->size == "large"){
						$res["image"] = $child;
					}
				}
				if(!isset($res["image"])) { $res["image"]="images/no.png"; }
				return $res;
			}
		} catch (HttpException $ex) {
		echo $ex;
	}
}

function getAlbumByArtist($artist) {
	try {
			$r = new HttpRequest("http://ws.audioscrobbler.com/2.0/?method=artist.gettopalbums&mbid=".$artist."&limit=1&api_key=184af8b6220039e4cb8167a5e2bb23e3");
			$h= $r->getHeaders();
			$h['User-Agent'] = 'IKE G4 0.1';
			$r->setHeaders($h);
			$r->send();
			if ($r->getResponseCode() == 200) {
				$xmlResponse = simplexml_load_string($r->getResponseBody());
				$response = $xmlResponse->children();
				$response = $response->children();
				$res=array();
				
				$res["name"]=(string)$response->album->name;
				$res["mbid"]=(string)$response->album->mbid;
				return $res;
			}
		} catch (HttpException $ex) {
		echo $ex;
	}
}

function getAlbumImage($album) {
	try {
		$r = new HttpRequest("http://ws.audioscrobbler.com/2.0/?method=album.getinfo&mbid=".$album."&api_key=184af8b6220039e4cb8167a5e2bb23e3");
		
		$h= $r->getHeaders();
		$h['User-Agent'] = 'php44';
		$r->setHeaders($h);
		$r->send();
		if ($r->getResponseCode() == 200) {
			$xmlResponse = simplexml_load_string($r->getResponseBody());
			$response = $xmlResponse->children()->xpath("image");
			foreach($response as $child){
				if($child->attributes()->size == "large"){
					return $child;
				}
			}
			//return "images/no.png";
		}
		else{ return "images/no.png"; }
	} catch (HttpException $ex) {
		echo $ex;
	}
}
function isShown($mbid){
		global $session;
		global $db;
		
		$q1 = $db->prepare("SELECT * FROM ike_shown WHERE user_id = :uid AND mbid = :aid;");
		$q1->bindParam(':uid', $session['loginID']);
		$q1->bindParam(':aid', $mbid);
		if($q1->execute()){
			$arr = $q1->fetchAll();
			if(count($arr)<1){
				return false;
			}
			return true;
		}
}

function addShown($mbid){
		global $session;
		global $db;
		
		$q1 = $db->prepare("SELECT * FROM ike_shown WHERE user_id = :uid AND mbid = :aid;");
		$q1->bindParam(':uid', $session['loginID']);
		$q1->bindParam(':aid', $mbid);
		if($q1->execute()){
			$arr = $q1->fetchAll();
			if(count($arr)<1){
				//Insert rating
				$q = $db->prepare("INSERT INTO ike_shown (user_id, mbid) VALUES (:uid, :aid)");
				$q->bindParam(':uid', $session['loginID']);
				$q->bindParam(':aid', $mbid);
			
				//Check if query is executed
				if(!$q->execute()){
					print("SQL ERROR: <br />");
					print $q->errorCode();
					print_r($q->errorInfo());
				}
			}
		}
}
function getArtistName($artist) {

	try {
		global $servername;
		$r = new HttpRequest($servername."/ws/2/artist/".$artist);
		$h= $r->getHeaders();
		$h['User-Agent'] = 'IKE G4 0.1';
		$r->setHeaders($h);
		$r->send();
		if ($r->getResponseCode() == 200) {
			$xmlResponse = simplexml_load_string($r->getResponseBody());
			return (string)$xmlResponse->artist[0]->name;
		}
		else {
			return null;
		}
	} catch (HttpException $ex) {
		echo $ex;
	}
}

function getSimilarArtists($artists) { 
	try {
		$res = array();
		foreach($artists as $artist) {
			$r = new HttpRequest("http://ws.audioscrobbler.com/2.0/?method=artist.getsimilar&artist=".str_replace(" ","%20",$artist)."&limit=2&api_key=184af8b6220039e4cb8167a5e2bb23e3");
			$h= $r->getHeaders();
			$h['User-Agent'] = 'php44';
			$r->setHeaders($h);
			$r->send();
			if ($r->getResponseCode() == 200) {
				$xmlResponse = simplexml_load_string($r->getResponseBody());
				$response = $xmlResponse->children();
				$response = $response->children();	
				foreach($response as $child){
					$res[]= array("mbid"=>(string)$child->mbid,"name"=>(string)$child->name);			
				}
			}
		}
		return $res;
	} catch (HttpException $ex) {
		echo $ex;
	}
}

function getAllVotedArtists($id){
	global $db;
	$q = "SELECT  `album_id`  FROM  `user_album_rating` WHERE rating > -1 AND user_id = :id";
	$query = $db->prepare($q);
	$query->bindParam(':id', $id);
	if($query->execute()){
		$mbids = $query->fetchAll();
		$ret = array();
		foreach($mbids as $mbid){
			if($mbid != ""){
			 $ret[] = $mbid[0];
			}
		}
		return $ret;
	}
	return array();
}
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
			if(isset($xmlResponse->artist->{"tag-list"})){
				foreach($xmlResponse->artist->{"tag-list"}->children() as $tag){
					if(!empty($tag->name)){
						$ret[] = strval($tag->name);
					}
				}
			}
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


function outputTags($id, $voted_on, $n) {
	$graph = new UserGraph($id);
	if($n){
		$node_array = $graph->getHighestRatedNodes(30);
	}else{
		$node_array = $graph->getHighestRatedNodes();
	}
	$tagarr = array();
	foreach($node_array as $recom){
		$tagarr[] = $recom[1]->getName();
	}
	$random = array();
	if($n){
		$random[] = $tagarr[rand(10,29)];
	}else{
		$random[] = $tagarr[array_rand($tagarr,1)];
	}

	$artists = getArtistsByTag($random, count($random));
	foreach($artists as $mbid){
		$name = getArtistName($mbid);
		//$album = getAlbumByArtist($mbid);
		//$image = getAlbumImage($album["mbid"]);
		$track = getTrackByArtist($mbid);
		$image = $track["image"];
		$rat = getRatingByMbid($voted_on, $mbid);
		$mbid2 = $mbid;
		if($track["mbid"] != ""){
			$mbid=$track["mbid"];
		}
		addShown($mbid);
		$genr = gettags($mbid);
	?>
	<div id="<?=$mbid ?>" class="contentbox">
		<div class="titlebox"><?=$track["name"] ?></div>
		<img src="<?=$image ?>" />
		<div class="titlebox"><?=$name ?></div>
		<div class="genrebox">
			<?
				$kom = "";
				foreach($genr as $genre){
					print($kom.$genre);
					$kom = ", ";
				}
			?>
		</div>
		<div id="<?=$mbid ?>_-1" class="neg ratingbutton <?=$mbid ?><?php if($rat==-1) echo " votedon"; ?>">-1</div><div id="<?=$mbid ?>_1" class="pos ratingbutton <?=$mbid ?><?php if($rat==1) echo " votedon"; ?>">+1</div><div id="info" rel="#<?=$mbid ?>_overl" class="info ratingbutton">i</div>
	</div>	
	
	<div class="simple_overlay" id="<?=$mbid ?>_overl">
		<div class="overlay_title"><?=$name ?> - <?=$track["name"] ?></div>
		<img src="<?=$image ?>" />
		<div class="details">
				<ul>
					<?php 
						$links = getLink($mbid2);
						foreach($links as $name => $target){
							echo "<li><a target='_blank' href=".$target.">".$name."</a></li>";
						}
					?>
				</ul>	
		</div>
	</div>

	
	
	
	
	
	
	

	<?php
	}
}
?>

