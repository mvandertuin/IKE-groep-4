<?php
useLib('htmlpage');

global $db;

$blues = $_POST['blues'];
$classical = $_POST['classical'];
$country = $_POST['country'];
$dance = $_POST['dance'];
$electronic = $_POST['electronic'];
$hiphop = $_POST['hiphop'];
$house = $_POST['house'];
$jazz = $_POST['jazz'];
$pop = $_POST['pop'];
$punk = $_POST['punk'];
$reggae = $_POST['reggae'];
$rock = $_POST['rock'];
$soul = $_POST['soul'];
$techno = $_POST['techno'];
$world = $_POST['world'];

$genrescores = array(1=>$blues,558=>$classical,2=>$country,3=>$dance,4=>$electronic,9=>$hiphop,10=>$house,11=>$jazz,12=>$pop,13=>$punk,14=>$reggae,15=>$rock,17=>$soul,18=>$techno,20=>$world);

foreach( $genrescores as $id=>$score) {
	$query = $db->prepare("UPDATE ike_genrescore SET score = :score WHERE userID = :uID AND nodeID = :nodeID");
	$query-> bindParam(':uID', $session['loginID'], PDO::PARAM_INT);
	$query-> bindParam(':nodeID', $id);
	$query-> bindParam(':score', $score);
	$query->execute();
}


?>
