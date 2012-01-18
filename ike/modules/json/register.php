<?php
global $db;
$naam = $_POST['naam'];
$email = $_POST['email'];
$ww1 = $_POST['ww1'];
$ww2 = $_POST['ww2'];
function error($msg)
{
    die(json_encode(array('result' => 'fail', 'message' => $msg)));
}

if ($ww1 != $ww2) {
    error('Passwords do not match');
}
if (empty($ww1)) {
    error("Password can't be empty");
}
if (empty($naam)) {
    error("Name can't be empty");
}
if (empty($email)) {
    error("Email can't be empty");
}
try {
    $q0 = $db->prepare('SELECT count(uID) as num FROM ike_users WHERE email = :email');
    $q0->bindParam(':email', $email);
    $q0->execute();
    $q0->bindColumn('num', $num);
    if (!$q0->fetch()) error('Query 0 failed');
    if ($num != 0) {
        //$q0->close();
        error('User already exists');
    }
    //$q0->close();
    $q1 = $db->prepare('INSERT INTO ike_users(naam, email, wachtwoord, type, validated) VALUES(:naam, :email, :wachtwoord, :type, :conf)');
    $wwhash = '_';
    $type = 0;
    $confirmed = 1;
    $q1->bindParam(':naam', $naam);
    $q1->bindParam(':email', $email);
    $q1->bindParam(':wachtwoord', $wwhash);
    $q1->bindParam(':type', $type, PDO::PARAM_INT);
    $q1->bindParam(':conf', $confirmed, PDO::PARAM_INT);
    $q1->execute();
    //$q1->close();
    $q2 = $db->prepare('SELECT uID FROM ike_users WHERE email = :email');
    $q2->bindParam(':email', $email);
    $q2->execute();
    $q2->bindColumn('uID', $uid);
    if (!$q2->fetch()) error('Creating user failed');
    //$q2->close();
    $wwhash = hashPassword($uid, $ww1);
    $q3 = $db->prepare('UPDATE ike_users SET wachtwoord = :ww WHERE uID = :id');
    $q3->bindParam(':ww', $wwhash);
    $q3->bindParam(':id', $uid);
    $q3->execute();
    login($email, $ww1);
    die(json_encode(array('result' => 'succes', 'redirectURI' => $frameworkRoot . 'introduction/')));
    //$q3->close();
} catch (Exception $e) {
    error($e->getMessage());
}
?>