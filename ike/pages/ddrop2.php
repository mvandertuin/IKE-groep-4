<?php
useLib('htmlpage');
?>
<html>
<head>
	<title>dragdrop test</title>

<base href="<?=$frameworkRoot?>"></base>
<script type="text/javascript" src="javascript/jquery-1.7.js"></script>
<script type="text/javascript" src="javascript/jquery-ui-1.8.16.custom.js"></script>
<script type="text/javascript" src="javascript/jquery.ui.core.js"></script>
<script type="text/javascript" src="javascript/jquery.ui.sortable.js"></script>
<script type="text/javascript" src="javascript/jquery.tools.min.js"></script>
<script type="text/javascript" src="javascript/ratingscript.js"></script>
<script type="text/javascript" src="javascript/dragdrop.js"></script>

<link rel="stylesheet" href="template/newstyle.css" />
<?php
global $scripts;
foreach($scripts as $script){
	?>
	<script type="text/javascript" src="javascript/<?=$script?>.js"></script>
	<?php	
}
?>
</head>
<body>
<div class="header">SongRecommend<a class="pref" href="introduction/">Bewerk voorkeuren</a></div>
<div class="wrapper" id="wrapper">
	<div class="deletebox" id="del">
	<p class="valign" >delete</p>
	</div>




<?php

//useLib('musicbrainz');
global $db;
//generate page header
//fw_header('Suggesties');

$query = $db->prepare("SElECT * FROM ike_voorkeur WHERE uID = ".$session['loginID']);
$query->execute();
if($query->rowCount()==0) { 
	if(!isset($_POST['sorted'])) {
	header('location: '.$frameworkRoot.'introduction/introduction.html');
	}
	else {
	// De array met tags
	$tagbrei = explode(',',$_POST['sorted']);	
	$tags = array_slice($tagbrei, 0, 5);
	$query2 = $db->prepare("INSERT INTO ike_voorkeur (uID, genre1, genre2, genre3, genre4, genre5, artiesten) VALUES(:uID, :genre1, :genre2, :genre3, :genre4, :genre5,:artiesten)");
	print $_POST['sorted'];
	$query2-> bindParam(':uID', $session['loginID'], PDO::PARAM_INT);
	$query2-> bindParam(':genre1', $tags[0]);
	$query2-> bindParam(':genre2', $tags[1]);
	$query2-> bindParam(':genre3', $tags[2]);
	$query2-> bindParam(':genre4', $tags[3]);
	$query2-> bindParam(':genre5', $tags[4]);
	$query2-> bindParam(':artiesten', $_POST['artist']);
	$query2-> execute();
	$artists = $_POST['artist'];
	
	}
}
else { 
	$results = $query->fetch(); 
	if(isset($_POST['sorted'])) {
		echo "edit";
		$tagbrei = explode(',',$_POST['sorted']);	
		$tags = array_slice($tagbrei, 0, 5);
		$query2 = $db->prepare("UPDATE ike_voorkeur SET genre1 = :genre1, genre2 = :genre2, genre3 = :genre3, genre4 = :genre4, genre5 = :genre5, artiesten = :artiesten WHERE uID = :uID");
		$query2-> bindParam(':uID', $session['loginID'], PDO::PARAM_INT);
		$query2-> bindParam(':genre1', $tags[0]);
		$query2-> bindParam(':genre2', $tags[1]);
		$query2-> bindParam(':genre3', $tags[2]);
		$query2-> bindParam(':genre4', $tags[3]);
		$query2-> bindParam(':genre5', $tags[4]);
		$query2-> bindParam(':artiesten', $_POST['artist']);
		$query2-> execute();
		$query2-> errorCode();
		$artists = $_POST['artist'];
	}
	
	else {	
		$tags = array( $results['genre1'], $results['genre2'],$results['genre3'],$results['genre4'],$results['genre5']);
		$artists = $results['artiesten'];
	}
}
	$votedon = getVotedOn($db, $session['loginID']);

	outputTags($tags, $votedon);
	$artists = explode(",", $artists);
	//outputSimilar($artists);



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
$r = new HttpRequest('http://wwww.chl43.nl:3000/ws/2/artist/'.$mbid.'?inc=url-rels');
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
function getArtistsByTag($tags) {
	try{
		// In deze forloop wordt per genre een query op de musicbrainz dataset gevuurd.
		$res=array();
		for($i = 0; $i<3; $i++) {
			try {
				$r = new HttpRequest("http://ws.audioscrobbler.com/2.0/?method=tag.gettopartists&tag=".$tags[$i]."&limit=2&api_key=184af8b6220039e4cb8167a5e2bb23e3");
				$h= $r->getHeaders();
				$h['User-Agent'] = 'IKE G4 0.1';
				$r->setHeaders($h);
				$r->send();
				if ($r->getResponseCode() == 200) {
					$xmlResponse = simplexml_load_string($r->getResponseBody());
					$response = $xmlResponse->children();
					$response = $response->children();		
					foreach($response as $child){
						$res[]=$child->mbid;			
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

function getArtistName($artist) {
	try {
		$r = new HttpRequest("http://www.chl43.nl:3000/ws/2/artist/".$artist);
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

function outputTags($tags, $voted_on) {
	$artists = getArtistsByTag($tags);

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
	?>
	<div id="<?=$mbid ?>" class="contentbox">
		<div class="titlebox"><?=$track["name"] ?></div>
		<img src="<?=$image ?>" />
		<div class="titlebox"><?=$name ?></div>
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
							echo "<li><a href=".$target.">".$name."</a></li>";
						}
					?>
				</ul>	
		</div>
	</div>

	
	
	
	
	
	
	

	<?php
	}
}
?>
	<div class="simple_overlay" id="alertbox">
		bla
	</div>
	<div class="more">Add Recommendations</div>
</div>
</body>
</html>
