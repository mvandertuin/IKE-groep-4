<?php
useLib('htmlpage');
//useLib('musicbrainz');

//generate page header
fw_header('Suggesties');
?>
<p>U heeft aangegeven muziekgenres leuk te vinden in de volgende volgorde: <?=$_POST['sorted']?></p>
<p>Dit levert de volgende lijst met aanbevolen artiesten op:</p>
<?php
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
				if($child->attributes()->size == "medium"){
					return $child;
				}
			}
			return "../ike/pages/no.png";
		}
		else{ return "../pages/no.png"; }
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
					$res[]= array((string)$child->mbid,(string)$child->name);			
				}
			}
		}
		return $res;
	} catch (HttpException $ex) {
		echo $ex;
	}
}

function outputTags($tags) {
	$artists = getArtistsByTag($tags);
	foreach($artists as $mbid){
		$name = getArtistName($mbid);
		$album = getAlbumByArtist($mbid);
		$image = getAlbumImage($album["mbid"]);
	?>
	<table border="1">
		<tr>
			<th colspan="2"><?php echo $name ?> - <?php echo $album["name"] ?></th>
		</tr>
		<tr>
			<td><img src="<?=$image ?>"></td>
			<td>
				<ul>
					<?php 
						$links = getLink($mbid);
						foreach($links as $name => $target){
							echo "<li><a href=".$target.">".$name."</a></li>";
						}
					?>
				</ul>	
			</td>
		</tr>
	</table>
	<?php
	}
}

function outputSimilar($artists) {
		$simartists = getSimilarArtists($artists);
		foreach($simartists as $a){
		if($a[0] != ""){
			$mbid = $a[0];
			$name = $a[1];
			$album = getAlbumByArtist($a);
			$image = getAlbumImage($album["mbid"]);
		}else{
			$name = $a[1];
			$album["name"] = "unknown";
			$image = "";
			$mbid = "";
		}
		?>
	<table border="1">
		<tr>
			<th colspan="2"><?php echo $name ?> - <?php echo $album["name"] ?></th>
		</tr>
		<tr>
			<td><img src="<?=$image ?>"></td>
			<td>
				<ul>
					<?php 
						$links = getLink($mbid);
						if(isset($links)){
							foreach($links as $name => $target){
								echo "<li><a href=".$target.">".$name."</a></li>";
							}
						}
					?>
				</ul>	
			</td>
		</tr>
	</table>
	<?php
	}
}

// De array met tags
$tagbrei = explode(',',$_POST['sorted']);	
$tags = array_slice($tagbrei, 0, 3);
echo "Suggesties adhv genres <br>" ;
outputTags($tags);
$artists = explode(",", $_POST['artist']);
echo "Suggesties adhv artiesten <br>";
outputSimilar($artists);

fw_footer();
?>