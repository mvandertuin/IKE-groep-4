<?php

//Functions for connection with musicbrainz

global $mbServer;
$mbServer = "http://192.168.0.84:3000";


// Functie getRating zorgt voor het opvragen van de rating van een bepaalde artiest.
function getRating($mbid)
{
    // De query voor het opvragen van de rating op MusicBrainz.
    global $mbServer;
    $r = new HttpRequest($mbServer . '/ws/2/artist/' . $mbid . '?inc=ratings');
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

function getTags($mbid)
{
    try {
        global $mbServer;
        $r = new HttpRequest($mbServer . "/ws/2/artist/" . $mbid . "?inc=tags");
        $h = $r->getHeaders();
        $h['User-Agent'] = 'IKE G4 0.1';
        $r->setHeaders($h);
        $r->send();
        if ($r->getResponseCode() == 200) {
            $xmlResponse = simplexml_load_string($r->getResponseBody());
            $ret = array();
            if (isset($xmlResponse->artist->{"tag-list"})) {
                foreach ($xmlResponse->artist->{"tag-list"}->children() as $tag) {
                    if (!empty($tag->name)) {
                        $ret[] = strval($tag->name);
                    }
                }
            }
            return $ret;
        }
        else {
            print($r->getResponseCode());
            return null;
        }
    } catch (HttpException $ex) {
        echo $ex;
    }
}

function getRatingByMbid($array, $mbid)
{
    if (!empty($array)) {
        foreach ($array as $row) {
            if ($row[0] == $mbid) {
                return $row[1];
            }
        }
    }
    return 0;
}

//Functie getLink geeft een 2dimensionale Array terug met alle links en bijbehorende namen.
function getLink($mbid)
{
    global $mbServer;
    $r = new HttpRequest($mbServer . '/ws/2/artist/' . $mbid . '?inc=url-rels');

    try {
        $r->send();
        if ($r->getResponseCode() == 200) {
            // Ouput is een XML bestand, die wordt ingelezen dmv de SimpleXML methode.
            $xmlResponse = simplexml_load_string($r->getResponseBody());
            $response = $xmlResponse->children();
            $response = $response->children();
            // Vraag de lijst met URLs op
            $response = $response->{"relation-list"};
            // Maak nieuwe array aan waar het resultaat in komt te staan
            $res = array();
            // In foreachloop type en target opvragen en in $res zetten.
            foreach ($response->relation as $relation) {
                $name = "" . $relation->attributes()->type; // Cast naar String
                $res[$name] = $relation->target;
            }
            return $res;
        }
    } catch (HttpException $ex) {
        return null;
    }
}

function getArtistName($artist)
{

    try {
        global $mbServer;
        $r = new HttpRequest($mbServer . "/ws/2/artist/" . $artist);
        $h = $r->getHeaders();
        $h['User-Agent'] = 'IKE G4 0.1';
        $r->setHeaders($h);
        $r->send();
        if ($r->getResponseCode() == 200) {
            $xmlResponse = simplexml_load_string($r->getResponseBody());
            return (string)$xmlResponse->artist[0]->name;
        }
        else {
            return null;
        }
    } catch (HttpException $ex) {
        echo $ex;
    }
}