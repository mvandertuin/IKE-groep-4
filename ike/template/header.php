<!doctype html>
<html>
<head>
	<title><?=$title?></title>
</head>
<script type="text/javascript" src="<?=$frameworkRoot?>javascript/jquery-1.7.js"></script>
<script type="text/javascript" src="<?=$frameworkRoot?>javascript/jquery-ui-1.8.16.custom.js"></script>
<script type="text/javascript" src="<?=$frameworkRoot?>javascript/jquery.ui.core.js"></script>
<script type="text/javascript" src="<?=$frameworkRoot?>javascript/jquery.ui.sortable.js"></script>
<link rel="stylesheet" href="<?=$frameworkRoot?>/template/style.css" />
<?php
global $scripts;
foreach($scripts as $script){
	?>
	<script type="text/javascript" src="<?=$frameworkRoot?>javascript/<?=$script?>.js"></script>
	<?php	
}
?>
<body>
<h1><a href="<?=$frameworkRoot?>">IKE</a></h1>