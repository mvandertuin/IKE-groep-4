<?php
function getLikedTracks($track) {

	try {
		$loved_artists = array();
		$r = new HttpRequest("http://ws.audioscrobbler.com/2.0/?method=track.getTopFans&mbid=".$track."&limit=1&api_key=184af8b6220039e4cb8167a5e2bb23e3");
		$h= $r->getHeaders();
		$h['User-Agent'] = 'php44';
		$r->setHeaders($h);
		$r->send();
		if ($r->getResponseCode() == 200) {
			$xmlResponse = simplexml_load_string($r->getResponseBody());
			$response = $xmlResponse->children();
			$response = $response->children();	
			if (count($response) > 30 ) { $stop = 30; }
			else { $stop = count($response); }
			for($i = 0;  $i < $stop; $i++) {
				$child = $response[$i];
				$r2 = new HttpRequest("http://ws.audioscrobbler.com/2.0/?method=user.getTopArtists&user=".$child->name."&limit=10&api_key=184af8b6220039e4cb8167a5e2bb23e3");
				$h2= $r->getHeaders();
				$h2['User-Agent'] = 'php44';
				$r2->setHeaders($h);
				$r2->send();
				if ($r2->getResponseCode() == 200) {
					$xmlResponse = simplexml_load_string($r2->getResponseBody());
					$response2 = $xmlResponse->children();
					$response2 = $response2->children();	
					foreach($response2 as $child2) {
						$name = (string)$child2->name;
						if (isset($loved_artists[$name])) { 
							$loved_artists[$name]= $loved_artists[$name] +1;
						}
						else { 
							$loved_artists[$name] = 1;
						}
					}
				}
			}
		}
		return arsort($loved_artists);
	} catch (HttpException $ex) {
		echo $ex;
	}
}
?>
