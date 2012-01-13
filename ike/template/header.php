<!doctype html>
<html>
<head>
	<title><?=$title?></title>
</head>
<base href="<?=$frameworkRoot?>"></base>
<script type="text/javascript" src="javascript/jquery-1.7.js"></script>
<script type="text/javascript" src="javascript/jquery-ui-1.8.16.custom.js"></script>
<script type="text/javascript" src="javascript/jquery.ui.core.js"></script>
<script type="text/javascript" src="javascript/jquery.ui.sortable.js"></script>
<link rel="stylesheet" href="template/style.css" />
<link rel="stylesheet" href="template/base/jquery.ui.all.css" />
<?php
global $scripts;
foreach($scripts as $script){
	?>
	<script type="text/javascript" src="javascript/<?=$script?>.js"></script>
	<?php	
}
?>
<body>
<h1><a href="<?=$frameworkRoot?>">IKE</a></h1>