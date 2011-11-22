<?php
//Olaf Maas - 2011
//Create database connection


function getRating($mbid) {
	$r = new HttpRequest('http://musicbrainz.org/ws/1/artist/'.$mbid.'?inc=ratings');
	try {
    $r->send();
    if ($r->getResponseCode() == 200) {
				$xmlResponse = simplexml_load_string($r->getResponseBody());
				$hallo = $xmlResponse->children();
				$hallo = $hallo->children();
				return $hallo->rating;
    }
} catch (HttpException $ex) {
    return null;
}
}

$user="root";
$host="localhost";
$dbname="musicdat";

//Execute first query and fetch results
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=UTF-8", $user);
$q=$conn->prepare("INSERT INTO artistrating VALUES(:id,:rating, :number)");
$q->bindParam(':id', $id, PDO::PARAM_STR);
$q->bindParam(':rating', $rating, PDO::PARAM_INT );
$q->bindParam(':number', $i, PDO::PARAM_INT );
$tag=array(1=>"country",2=>"blues",3=>"pop",4=>"alternative",5=>"rock");
for($i = 1; $i<=5; $i++) {
	$r = new HttpRequest('http://musicbrainz.org/ws/2/artist/?query=tag:'.$tag[$i]);
	try {
		$r->send();
		if ($r->getResponseCode() == 200) {
			$xmlResponse = simplexml_load_string($r->getResponseBody());
			$hallo = $xmlResponse->children();
			$hallo = $hallo->children();
			foreach($hallo as $child){
				$id=$child->attributes()->id;
				echo $child->attributes()->id;
				$rating=getRating($child->attributes()->id);
				print($id." : ".$rating."<br/>");
				$q->execute();					
			}
		}
	} catch (HttpException $ex) {
		echo $ex;
	}
}

$q=$conn->prepare("SELECT id, rating*(5-number) AS rat FROM artistrating ORDER BY rat DESC LIMIT 10");
$q->execute();

$query2= $conn->prepare("SELECT artist_name FROM artist WHERE mb_id=:id");
$query2->bindParam(":id", $id);

$resultArray = $q->fetchAll();
for ( $j = 0; $j < count($resultArray); $j++) {
	$id = $resultArray[$j]["id"];
	$query2->execute();
	echo $query2->fetch();
}



?>