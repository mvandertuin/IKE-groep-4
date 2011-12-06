

<?php
//Empty example page
global $scripts;
global $db;
global $db_tableprefix;
//add names of JS files to $scripts array if needed

//Include helper functions for generating an HTML page
useLib('htmlpage');

//generate page header
//fw_header('Inloggen');



//Check for login
if($session['loginID'] > 0){
	//Check if Post is valid
	if(isset($_POST["rhash"]) && isset($_POST["mbid"]) && isset($_POST["albumname"]) && isset($_POST["rat"])){
				//Insert rating
		$q1 = $db->prepare("SELECT * FROM user_album_rating WHERE user_id = :uid AND album_id = :aid;");
		$q1->bindParam(':uid', $session['loginID']);
		$q1->bindParam(':aid', $_POST["mbid"]);
		
		if($q1->execute()){
			if($q1->fetch() != false){
				$q = $db->prepare("UPDATE user_album_rating SET rating = :rat WHERE user_id = :uid AND album_id = :aid LIMIT 1 ;");
				//Insert rating
				$q->bindParam(':uid', $session['loginID']);
				$q->bindParam(':aid', $_POST["mbid"]);
				$q->bindParam(':rat', $_POST["rat"]);
			
				//Check if query is executed
				if($q->execute()){
					print "U had al gestemd, uw stem is geupdate.";
				}else{
					print("SQL ERROR: <br />");
					print $q->errorCode();
					print_r($q->errorInfo());
				}
			}else{
				//Insert rating
				$q = $db->prepare("INSERT INTO user_album_rating (user_id, album_id, rating) VALUES (:uid, :aid, :rating)");
				$q->bindParam(':uid', $session['loginID']);
				$q->bindParam(':aid', $_POST["mbid"]);
				$q->bindParam(':rating', $_POST["rat"]);
			
				//Check if query is executed
				if($q->execute()){
					print "Uw stem is toegevoegd";
				}else{
					print("SQL ERROR: <br />");
					print $q->errorCode();
					print_r($q->errorInfo());
				}
			}
		}
	}else{
		var_dump($_POST);
		print("Error: Een van de postvariabelen is niet meegegeven.");
	}
}else{
	print("Error: U bent niet ingelogd");
}
	
//fw_footer();
?>