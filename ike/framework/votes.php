<?php

function getVotedOn($db, $uid)
{
    global $db;
    $q1 = $db->prepare("SELECT album_id, rating FROM user_album_rating WHERE user_id = :uid");
    $q1->bindParam(':uid', $uid);

    if ($q1->execute()) {
        $ret = $q1->fetchAll();
        return $ret;
    } else {
        print_r($q->errorInfo());
    }
}

function addShown($mbid)
{
    global $session;
    global $db;

    $q1 = $db->prepare("SELECT * FROM ike_shown WHERE user_id = :uid AND mbid = :aid;");
    $q1->bindParam(':uid', $session['loginID']);
    $q1->bindParam(':aid', $mbid);
    if ($q1->execute()) {
        $arr = $q1->fetchAll();
        if (count($arr) < 1) {
            //Insert rating
            $q = $db->prepare("INSERT INTO ike_shown (user_id, mbid) VALUES (:uid, :aid)");
            $q->bindParam(':uid', $session['loginID']);
            $q->bindParam(':aid', $mbid);

            //Check if query is executed
            if (!$q->execute()) {
                print("SQL ERROR: <br />");
                print $q->errorCode();
                print_r($q->errorInfo());
            }
        }
    }
}

function getAllVotedArtists($id)
{
    global $db;
    $q = "SELECT  `album_id`  FROM  `user_album_rating` WHERE rating > -1 AND user_id = :id";
    $query = $db->prepare($q);
    $query->bindParam(':id', $id);
    if ($query->execute()) {
        $mbids = $query->fetchAll();
        $ret = array();
        foreach ($mbids as $mbid) {
            if ($mbid != "") {
                $ret[] = $mbid[0];
            }
        }
        return $ret;
    }
    return array();
}

function isShown($mbid)
{
    global $session;
    global $db;

    $q1 = $db->prepare("SELECT * FROM ike_shown WHERE user_id = :uid AND mbid = :aid;");
    $q1->bindParam(':uid', $session['loginID']);
    $q1->bindParam(':aid', $mbid);
    if ($q1->execute()) {
        $arr = $q1->fetchAll();
        if (count($arr) < 1) {
            return false;
        }
        return true;
    }
}