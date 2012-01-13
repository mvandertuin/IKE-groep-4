<!DOCTYPE html>

<html lang="en">
<head>
	<base href="<?=$frameworkRoot?>"></base>
	<?php
		useLib('graph');
		global $session;
		$start = microtime(true);
		$g = new UserGraph($session['loginID']);
		$start = microtime(true);
		$top10 = $g->getHighestRatedNodes();
		$flop10 = $g->getLowestRatedNodes();
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>SongRecommend</title>
	<link rel="stylesheet" href="template/newstyle.css" type="text/css">
		<script type="text/javascript" src="javascript/jquery-1.7.js"></script>
	<script type="text/javascript" src="javascript/jquery-ui-1.8.16.custom.js"></script>
	<script type="text/javascript" src="javascript/jquery.ui.core.js"></script>
	<script type="text/javascript" src="javascript/jquery.ui.sortable.js"></script>
	<script type="text/javascript" src="javascript/jquery.tools.min.js"></script>
	<script type="text/javascript" src="javascript/arbor.js"></script>
	<script type="text/javascript" src="javascript/atlas2.js" ></script>
	
</head>
<body>
	<div class="header">SongRecommend<a class="pref" href="introduction/">Bewerk voorkeuren</a></div>
  <canvas id="viewport" width="800" height="600"></canvas>
  <div class="infostat"><p>Links ziet u uw muzieksmaak, de top 10 genres die u volgens onze gegevens het meest bevallen zijn bruin gekleurd. De andere genres zijn lichter gekleurd en
	zijn gerelateerd aan uw top 10, deze genres kunt u ook leuk vinden. Hieronder ziet u uw top 10 en flop 10.</p>
	
	<h3>Top 10</h3>
	<ol>
		<?php
		foreach($top10 as $top){
			print "<li>".$top[1]->getName()."</li>";
		} 
		?>
	</ol>
	<h3>Flop 10</h3>
		<ol>
			<?php
			foreach($flop10 as $flop){
				print "<li>".$flop[1]->getName()."</li>";
			} 
			?>
		</ol>
	</div>



</body>
</html>