<?php
useLib('htmlpage');

//generate page header
fw_header('Suggesties');
?>
<p>U heeft aangegeven muziekgenres leuk te vinden in de volgende volgorde: <?=$_POST['sorted']?></p>
<?php
fw_footer();
?>