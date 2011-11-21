<?php
$genres = array("alternative"=>"Alternatief",
"blues"=>"Blues",
"country"=>"Country",
"dance"=>"Dance",
"electronic"=>"Electronic",
"hiphop"=>"Hip-hop",
"jazz"=>"Jazz",
"children"=>"Kindermuziek",
"classic"=>"Klassiek",
"metal"=>"Metal",
"pop"=>"Pop",
"rap"=>"Rap",
"reggae"=>"Reggae",
"rock"=>"Rock",
"world"=>"Wereldmuziek");
$count = 1;
$inputs = $list = '';
foreach($genres as $key=>$value){
	$list.='<li id="'.$key.'" class="sortable"><img src="img/arrow.png" alt="move" width="16" height="16" class="handle" />'.$value.'</li>';
	$count++;
}
?>
<html>
<head>
	<title>Geef uw muziekvoorkeur op</title>
</head>
<style> 
  #sortlist { list-style: none; padding: 0; margin: 0 40px; } 
  #sortlist li { margin: 0 0 1px 0; background: #9FF; padding: 2px; } 
  #sortlist .handle { float: left; margin-right: 10px; cursor: move; } 
</style>
<script type="text/javascript" src="js/jquery-1.7.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.js"></script>
<script type="text/javascript" src="js/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/jquery.ui.sortable.js"></script>
<script type="text/javascript">// When the document is ready set up our sortable with it's inherant function(s) 
$(document).ready(function() { 
  $("#sortlist").sortable({ 
    handle : '.handle', 
    update : function () { 
      $("#sorted").val($('#sortlist').sortable('toArray')); 
    } 
  }); 
}); 
</script>
<body>
<h1>Titel</h1>
<h2>Geef uw muziekvookeur op</h2>
<p>Sleep de genres hieronder in de volgorde waarin u deze graag luisterd. Plaats uw favoriete genre bovenaan en de minst favoriete van onder.</p>
<ul id="sortlist">
<?=$list?>
</ul>
<form action="suggest.php" method="post">
<input type="hidden" id="sorted" name="sorted" />
<input type="submit" value="Opslaan" />
</form>
</body>
</html>