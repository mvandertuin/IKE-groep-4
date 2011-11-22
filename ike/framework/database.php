<?php
function dbStart(){
	global $db_pass;
	global $db_user;
	global $db_host;
	global $db_name;
	global $db;
	/*$db = new mysqli($db_host, $db_user, $db_pass, $db_name);
	if (mysqli_connect_errno()) {
    	broken('901', "Database connect failed: ". mysqli_connect_error());
	}*/
	try {
    $db = new PDO('mysql:host='.$db_host.';dbname='.$db_name, $db_user, $db_pass);
		
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
}

function dbEnd(){
	global $db;
	//$db->close();
}
