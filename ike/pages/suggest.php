<?php
useLib('htmlpage');
useLib('musicbrainz');

//generate page header
fw_header('Suggesties');
?>
<p>U heeft aangegeven muziekgenres leuk te vinden in de volgende volgorde: <?=$_POST['sorted']?></p>
<?php
error_reporting(E_ALL);
// Functie getRating zorgt voor het opvragen van de rating van een bepaalde artiest.
function getRating($mbid) {
	// De query voor het opvragen van de rating op MusicBrainz.
	$r = new HttpRequest('http://musicbrainz.org/ws/1/artist/'.$mbid.'?inc=ratings');
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
try{
// In deze eerste fase wordt er nog niets opgeslagen van een specifieke gebruiker. Vandaar is ervoor gekozen de database simpelweg elke keer leeg te gooien. Dit zal in latere versies uiteraard worden aangepast.
$truncate = $db->prepare("TRUNCATE TABLE artistrating");
$truncate->execute();

// Query opstellen waarbij de artiest id en de rating worden toegevoegd aan de database
$q=$db->prepare("INSERT INTO artistrating (id,rating) VALUES(:id,:rating)");
$q->bindParam(':id', $id, PDO::PARAM_STR);
$q->bindParam(':rating', $rating, PDO::PARAM_INT);

// De array met tags
$tagbrei = explode(',',$_POST['sorted']);
$tag = array_slice($tagbrei, 0, 5);
// In deze forloop wordt per genre een query op de musicbrainz dataset gevuurd.
for($i = 0; $i<5; $i++) {
	try {
		$r = new HttpRequest('http://musicbrainz.org/ws/2/artist/?query=tag:'.$tag[$i]);
		$h= $r->getHeaders();
		$h['User-Agent'] = 'IKE G4 0.1';
		$r->setHeaders($h);
		$r->send();
		if ($r->getResponseCode() == 200) {
			$xmlResponse = simplexml_load_string($r->getResponseBody());
			$response = $xmlResponse->children();
			$response = $response->children();
			// Voor elke artiest die hieruit volgt, wordt de rating opgehaald met de getRating functie.
			foreach($response as $child){
				$id=$child->attributes()->id;
				$rat=getRating($child->attributes()->id);
				// De rating wordt vervolgens gewogen.
				$rating=($rat+0)*(5-$i);
				// En de artiest - rating koppel in de database opgeslagen.
				//TODO : dubbele artiesten samen laten voegen!
				$q->execute();				
			}
		}
	} catch (HttpException $ex) {
		echo $ex;
	}
}
// Uit de database de 1e 10 rijen gesorteerd op waardering opvragen
$q=$db->prepare("SELECT id, rating FROM artistrating ORDER BY rating DESC LIMIT 10");
$q->execute();
$resultArray = $q->fetchAll();

// Vervolgens deze data koppelen met de data van artiesten die in de database zit. 
$query2= $db->prepare("SELECT * FROM artist WHERE mb_id=:id");
$query2->bindParam(":id", $id);
echo '2';
// Nu middels een forloop het resultaat van de artiesten tonen aan de gebruiker.
for ( $j = 0; $j < count($resultArray); $j++) {
	$id = $resultArray[$j]["id"];
	$query2->execute();
	$res = $query2->fetch();
	echo $res["artist_name"]." : ".$res["mb_id"]."<br/>";
}
}catch(Exception $e){
	var_dump($e);	
}

fw_footer();
?>