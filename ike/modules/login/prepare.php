<?php
global $loginModule;
global $session;
global $db;
global $db_tableprefix;
$loginModule = true;
$session = array();
$session['loginID'] = 0;// 0 - Not logged in
/* TODO: check login stuff here */

session_start();
$id = session_id();
$q = $db->prepare("SELECT * FROM ".$db_tableprefix."session WHERE sessID = :session LIMIT 1");
$q->bindParam(':session', $id);
$q->execute();
$q->bindColumn('iuID',$uid);
$q->bindColumn('sessID', $sessid);
$q->bindColumn('valid', $valid);
$q->bindColumn('storage', $storage);
if($q->fetch()){
	//$q->close();
	if(time()<$valid){
		$session['loginID'] = $uid;
		$session['storage'] = $storage;
		$q3 = $db->prepare("SELECT naam, type FROM ".$db_tableprefix."users WHERE bcpuID = :userid");
		$q3->bindParam(':userid', $uid);
		$q3->execute();
		$q3->bindColumn('naam',$naam);
		$q3->bindColumn('type',$type);
		$q3->fetch();
		$session['userName']=$naam;
		$session['userType']=$type;
		//$q3->close();
		$q2 = $db->prepare("UPDATE ".$db_tableprefix."session SET valid = :valid WHERE sessID = :session LIMIT 1");
		$valtime = time()+900;
		if($q2===false){
			echo mysqli_error($db);
		}
		$q2->bindParam(':valid', $valtime);
		$q2->bindParam(':session', $sessid);
	}else{
		$q2 = $db->prepare("DELETE FROM ".$db_tableprefix."session WHERE sessID = :session LIMIT 1");
		if($q2===false){
			echo mysqli_error($db);
		}
		$q2->bindParam(':session', $sessid);
	}
	
	$q2->execute();
	//$q2->close();
}

$session['sid'] = session_id();
function login($email, $pass){
	global $db;
	global $session;
	global $db_tableprefix;
	$q1 = $db->prepare("SELECT * FROM ".$db_tableprefix."users WHERE email = :email LIMIT 1");
	$q1->bindParam(':email', $email);
	if(!$q1->execute()){
	  var_dump($q1->errorInfo());
	}
	//$q1->store_result();
	$q1->bindColumn('uID',$uid);
	$q1->bindColumn('naam',$naam);
	$q1->bindColumn('email',$email);
	$q1->bindColumn('wachtwoord',$codewachtwoord);
	$q1->bindColumn('type',$type);
	$q1->bindColumn('validated',$validated);
	echo 'd';
	if(!$q1->fetch(PDO::FETCH_BOUND)){
		return false;
	}
	//$q1->close();
	echo 'b';
	if(hashPassword($uid, $pass)==$codewachtwoord){
		echo 'c';
		$q2 = $db->prepare("INSERT INTO ".$db_tableprefix."session (iuID, sessID, valid, storage) VALUES (:uid, :session, :valid, :storage)");
		$sid = $session['sid'];
		$valtime = time()+900;
		$storage = 'hoi';
		if(!$q2){
			print_r($db->errorInfo());
		}
		$q2->bindParam(':uid', $uid, PDO::PARAM_INT);
		$q2->bindParam(':session', $sid);
		$q2->bindParam(':valid', $valtime, PDO::PARAM_INT);
		$q2->bindParam(':storage', $storage);
		print($q2->queryString);
		//$q2->bind_param('isis', $uid, $sid, $valtime, $storage);
		$q2->execute();
		print $q2->errorCode();
		print $q2->errorInfo();
			//$q2->close();
			
			$session['loginID']=$uid;
			$session['userType']=$type;
			$session['userName']=$naam;
			return true;
		print_r($db->errorInfo());
	}
	return false;
}
function logout(){
	global $db;
	global $session;
	global $db_tableprefix;
	$q = $db->prepare("DELETE FROM ".$db_tableprefix."session WHERE sessID = :session LIMIT 1");
	$id = $session['sid'];
	$q->bindParam(':session', $id);
	$q->execute();
	$q->close();
}