<?php
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

// Inloggegevens voor de database
$user="root";
$host="localhost";
$dbname="musicdat";

// Connectie maken met de database. 
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=UTF-8", $user);
// In deze eerste fase wordt er nog niets opgeslagen van een specifieke gebruiker. Vandaar is ervoor gekozen de database simpelweg elke keer leeg te gooien. Dit zal in latere versies uiteraard worden aangepast.
$truncate = $conn->prepare("TRUNCATE TABLE artistrating");
$truncate->execute();

// Query opstellen waarbij de artiest id en de rating worden toegevoegd aan de database
$q=$conn->prepare("INSERT INTO artistrating (id,rating) VALUES(:id,:rating)");
$q->bindParam(':id', $id, PDO::PARAM_STR);
$q->bindParam(':rating', $rating, PDO::PARAM_INT);

// De array met tags
$tag=array(0=>"country",2=>"blues",3=>"pop",4=>"alternative",5=>"rock");

// In deze forloop wordt per genre een query op de musicbrainz dataset gevuurd.
for($i = 1; $i<=5; $i++) {
	$r = new HttpRequest('http://musicbrainz.org/ws/2/artist/?query=tag:'.$tag[$i]);
	try {
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
$q=$conn->prepare("SELECT id, rating FROM artistrating ORDER BY rating DESC LIMIT 10");
$q->execute();
$resultArray = $q->fetchAll();

// Vervolgens deze data koppelen met de data van artiesten die in de database zit. 
$query2= $conn->prepare("SELECT * FROM artist WHERE mb_id=:id");
$query2->bindParam(":id", $id);

// Nu middels een forloop het resultaat van de artiesten tonen aan de gebruiker.
for ( $j = 0; $j < count($resultArray); $j++) {
	$id = $resultArray[$j]["id"];
	$query2->execute();
	$res = $query2->fetch();
	echo $res["artist_name"]." : ".$res["mb_id"]."<br/>";
}

?>