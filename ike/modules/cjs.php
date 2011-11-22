<?php
global $path;
$min = '';
if(strpos($path, '?')){
	$path = substr($path, 0, strpos($path, '?'));
}
if((file_exists('./cache/'.basename($path)))&&(filemtime('./cache/'.basename($path))>filemtime('./media/'.basename($path)))){
	$min = file_get_contents('./cache/'.basename($path));
}/*elseif(basename($path)=='prototype.js'){
	$min = file_get_contents('./media/'.basename($path));
}*/else{
	$script = file_get_contents('./media/'.basename($path));
	useLib('jsminplus');
	$min = JSMinPlus::minify($script);
	file_put_contents('./cache/'.basename($path), $min);
}
echo $min;
die();
?>