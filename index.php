<?php
//Olaf Maas - 2011
//Create database connection
$user="root";
$host="localhost";
$dbname="musicdat";

//Execute first query and fetch results
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=UTF-8", $user);
$q=$conn->prepare("SELECT MAX(ownid) FROM artist");
$q->execute();
$resultArray = $q->fetchAll();
$randomIndex = rand(0,$resultArray[0][0]);

//Execute second query
$q=$conn->prepare("SELECT * FROM artist WHERE ownid = :randi");
$q->bindParam(":randi", $randomIndex);
$q->execute();
$resultArray = $q->fetchAll();

//Set 
$r = new HttpRequest('http://musicbrainz.org/ws/2/release?artist='.$resultArray[0][1], HttpRequest::METH_GET);
//$r->setHeaders(array('User-Agent' => 'TUDMusicBrowseUnit'.rand(0,100)));

try {
    $r->send();
    if ($r->getResponseCode() == 200) {
				$xmlResponse = simplexml_load_string($r->getResponseBody());
				$hallo = $xmlResponse->children();
				$hallo = $hallo->children();
				print("<b>".$resultArray[0][3]."</b><br />");
				foreach($hallo as $child){
					print($child->title."<br />");
				}
    }
} catch (HttpException $ex) {
    echo $ex;
}



?>