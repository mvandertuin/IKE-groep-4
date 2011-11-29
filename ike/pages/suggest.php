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
$r = new HttpRequest('http://musicbrainz.org/ws/2/artist/'.$mbid.'?inc=url-rels');
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

function getArtistsByTag($tags) {
try{
	
	// In deze forloop wordt per genre een query op de musicbrainz dataset gevuurd.
	for($i = 0; $i<3; $i++) {
		try {
			$r = new HttpRequest("http://ws.audioscrobbler.com/2.0/?method=tag.gettopartists&tag=".$tags[$i]."&api_key=184af8b6220039e4cb8167a5e2bb23e3");
			$h= $r->getHeaders();
			$h['User-Agent'] = 'IKE G4 0.1';
			$r->setHeaders($h);
			$r->send();
			if ($r->getResponseCode() == 200) {
				$xmlResponse = simplexml_load_string($r->getResponseBody());
				$response = $xmlResponse->children();
				$response = $response->children();
				$res=array();		
				foreach($response as $child){
					$res[]=$child->mbid;			
				}
				return $res;
			}
		} catch (HttpException $ex) {
		echo $ex;
	}
}

function getAlbumByArtist($artist) {
	//TODO Last.fm => TopAlbums

	//Return array met naam + mbid
}

function getAlbumImage($album) {
	// $album is mbid
	// TODO Last.fm
	
	// Return 1 image -> url
}

function getArtistName($artist) {
	// TODO
	
	// Via MusicBrainz. $artist == mbid.
	// Return string
}

function outputTags($tags) {
	$artists = getArtistByTag($tags);
	foreach($artists as $a){
		$mbid = $a;
		$name = getArtistName($artist);
		$album = getAlbumByArtist($artist);
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

	// De array met tags
	$tagbrei = explode(',',$_POST['sorted']);	
	$tags = array_slice($tagbrei, 0, 3);
	outputTags($tags);
	
	
// ALLES HIERONDER NOG UITZOEKEN!!!!!


	

// Nu middels een forloop het resultaat van de artiesten tonen aan de gebruiker.
// echo "<ul>";
// for ( $j = 0; $j < count($resultArray); $j++) {
	// echo "<li>".$resultArray[$j]["artistname"];
	// echo "<ul>";
	// $links = getLink($resultArray[$j]["id"]);
	// foreach($links as $name => $target){
		// echo "<li><a href=".$target.">".$name."</a></li>";
	// }
	// echo "</ul></li>";
	
	
// }



// echo "</ul>";
// echo "De artiesten die u leuk vind zijn:";
// $artists = explode(",", $_POST['artist']);
// echo "<ul>";
// foreach($artists as $artist){
	// echo "<li>".$artist."</li>";
// }
// echo "</ul>";
// echo "En de artiesten die daar op lijken zijn:";
// echo "<ul>";
// foreach($artists as $artist){
	// try {
		// $r = new HttpRequest("http://ws.audioscrobbler.com/2.0/?method=artist.getsimilar&artist=".$artist."&limit=2&api_key=184af8b6220039e4cb8167a5e2bb23e3");
		
		// $h= $r->getHeaders();
		// $h['User-Agent'] = 'php44';
		// $r->setHeaders($h);
		//sleep(10);
		// $r->send();
		// if ($r->getResponseCode() == 200) {
			// $xmlResponse = simplexml_load_string($r->getResponseBody());
			// $response = $xmlResponse->children();
			// $response = $response->children();
			//Voor elke artiest die hieruit volgt, wordt de rating opgehaald met de getRating functie.
			// foreach($response as $child){
				// $name=$child->name;
				// echo "<li>".$name;
				// $links = getLink($child->mbid);
				// echo "<ul>";
				// foreach($links as $name => $target){
					// echo "<li><a href=".$target.">".$name."</a></li>";
				// }
				// echo "</ul></li>";
				
			// }
		// }
	// } catch (HttpException $ex) {
		// echo $ex;
	// }
// }
// echo "</ul>";

// }catch(Exception $e){
	// var_dump($e);	
// }

fw_footer();
?>