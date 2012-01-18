<?php

//Functions for connection with Last.fm

global $asServer;
$asServer = "http://ws.audioscrobbler.com";

// Functie getArtistsByTag zoekt artiesten aan de hand van tags
function getArtistsByTag($tags, $max)
{
    global $asServer;
    try {
        // In deze forloop wordt per genre een query op de musicbrainz dataset gevuurd.
        $res = array();
        for ($i = 0; $i < $max; $i++) {
            try {
                $r = new HttpRequest($asServer . "/2.0/?method=tag.gettopartists&tag=" . str_replace(" ", "%20", $tags[$i]) . "&limit=2&api_key=184af8b6220039e4cb8167a5e2bb23e3");
                $h = $r->getHeaders();
                $h['User-Agent'] = 'IKE G4 0.1';
                $r->setHeaders($h);
                $r->send();
                if ($r->getResponseCode() == 200) {
                    $xmlResponse = simplexml_load_string($r->getResponseBody());
                    $response = $xmlResponse->children();
                    $response = $response->children();
                    foreach ($response as $child) {
                        if ($child->mbid != "") {
                            $res[] = $child->mbid;
                        }
                    }
                }
            } catch (HttpException $ex) {
                return null;
            }

        }
        return $res;
    } catch (HttpException $ex) {
        return null;
    }
}

function getTrackByArtist($artist)
{
    global $asServer;
    try {
        $r = new HttpRequest($asServer . "/2.0/?method=artist.gettoptracks&mbid=" . $artist . "&limit=1&api_key=184af8b6220039e4cb8167a5e2bb23e3");
        $h = $r->getHeaders();
        $h['User-Agent'] = 'IKE G4 0.1';
        $r->setHeaders($h);
        $r->send();
        if ($r->getResponseCode() == 200) {
            $xmlResponse = simplexml_load_string($r->getResponseBody());
            $response = $xmlResponse->children();
            $response = $response->children();
            $res = array();
            $res["name"] = (string)$response->track->name;
            $res["mbid"] = (string)$response->track->id;
            foreach ($response->track->image as $child) {
                if ($child->attributes()->size == "large") {
                    $res["image"] = $child;
                }
            }
            if (!isset($res["image"])) {
                $res["image"] = "images/no.png";
            }
            return $res;
        }
    } catch (HttpException $ex) {
        echo $ex;
    }
}

function getAlbumByArtist($artist)
{
    global $asServer;
    try {
        $r = new HttpRequest($asServer . "/2.0/?method=artist.gettopalbums&mbid=" . $artist . "&limit=1&api_key=184af8b6220039e4cb8167a5e2bb23e3");
        $h = $r->getHeaders();
        $h['User-Agent'] = 'IKE G4 0.1';
        $r->setHeaders($h);
        $r->send();
        if ($r->getResponseCode() == 200) {
            $xmlResponse = simplexml_load_string($r->getResponseBody());
            $response = $xmlResponse->children();
            $response = $response->children();
            $res = array();

            $res["name"] = (string)$response->album->name;
            $res["mbid"] = (string)$response->album->mbid;
            return $res;
        }
    } catch (HttpException $ex) {
        echo $ex;
    }
}

function getAlbumImage($album)
{
    global $asServer;
    try {
        $r = new HttpRequest($asServer . "/2.0/?method=album.getinfo&mbid=" . $album . "&api_key=184af8b6220039e4cb8167a5e2bb23e3");

        $h = $r->getHeaders();
        $h['User-Agent'] = 'IKE G4 0.1';
        $r->setHeaders($h);
        $r->send();
        if ($r->getResponseCode() == 200) {
            $xmlResponse = simplexml_load_string($r->getResponseBody());
            $response = $xmlResponse->children()->xpath("image");
            foreach ($response as $child) {
                if ($child->attributes()->size == "large") {
                    return $child;
                }
            }
        }
        else {
            return "images/no.png";
        }
    } catch (HttpException $ex) {
        echo $ex;
    }
}

function getSimilarArtists($artists)
{
    global $asServer;
    try {
        $res = array();
        foreach ($artists as $artist) {
            $r = new HttpRequest($asServer . "/2.0/?method=artist.getsimilar&artist=" . str_replace(" ", "%20", $artist) . "&limit=2&api_key=184af8b6220039e4cb8167a5e2bb23e3");
            $h = $r->getHeaders();
            $h['User-Agent'] = 'php44';
            $r->setHeaders($h);
            $r->send();
            if ($r->getResponseCode() == 200) {
                $xmlResponse = simplexml_load_string($r->getResponseBody());
                $response = $xmlResponse->children();
                $response = $response->children();
                foreach ($response as $child) {
                    $res[] = array("mbid" => (string)$child->mbid, "name" => (string)$child->name);
                }
            }
        }
        return $res;
    } catch (HttpException $ex) {
        echo $ex;
    }
}