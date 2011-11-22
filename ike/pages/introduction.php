<?php
useLib('constants');
$count = 1;
$inputs = $list = '';
foreach($genres as $key=>$value){
	$list.='<li id="'.$key.'" class="sortable"><img src="images/arrow.png" alt="move" width="16" height="16" class="handle" />'.$value.'</li>';
	$count++;
}
global $scripts;
$scripts[]='introduction';
useLib('htmlpage');
fw_header('Welkom');
?>
<h2>Geef uw muziekvookeur op</h2>
<p>Sleep de genres hieronder in de volgorde waarin u deze graag luisterd. Plaats uw favoriete genre bovenaan en de minst favoriete van onder.</p>
<ul id="sortlist">
<?=$list?>
</ul>
<form action="<?=$frameworkRoot?>suggest/" method="post">
<input type="hidden" id="sorted" name="sorted" />
<input type="submit" value="Verder" />
</form>
<?php
fw_footer();
?>