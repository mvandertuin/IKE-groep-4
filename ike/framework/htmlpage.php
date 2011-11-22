<?php
function fw_header($title){
	global $theme;
	global $frameworkRoot;
	include('./template/header.php');
}

function fw_footer(){
	global $theme;
	include('./template/footer.php');
}

?>