<?php
useLib('htmlpage');
//useLib('musicbrainz');

//generate page header
fw_header('Suggesties');

function createBaseCookie($input_string){

	//Explode inputstring to an array with tags.
	$tags = explode(',',$input_string);	
	$index = count($tags);
	
	foreach($tags as $tag){
		$arr[tag] = $index;
		$index = $index - (6/abs($index))
	}
	
	setcookie("music_score", $arr);
}


createBaseCookie($_POST['sorted'])
var_dump($_COOKIE['music_score']);

fw_footer();
?>
