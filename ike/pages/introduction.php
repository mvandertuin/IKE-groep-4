<?php
useLib('constants');
useLib('htmlpage');
fw_header('Welkom');

global $db;
$query = $db->prepare("SELECT score, nodeID FROM ike_genrescore WHERE userID=:uID ORDER BY nodeID ASC");
$query-> bindParam(':uID', $session['loginID'], PDO::PARAM_INT);
$query->execute();
$result = $query -> fetchAll();

$checked = array('blues'=>array("", "unchecked","unchecked","unchecked","unchecked","unchecked"),'classical'=>array("", "unchecked","unchecked","unchecked","unchecked","unchecked"),'country'=>array("", "unchecked","unchecked","unchecked","unchecked","unchecked"),'dance'=>array("", "unchecked","unchecked","unchecked","unchecked","unchecked"),'electronic'=>array("", "unchecked","unchecked","unchecked","unchecked","unchecked"),'hiphop'=>array("", "unchecked","unchecked","unchecked","unchecked","unchecked"),'house'=>array("", "unchecked","unchecked","unchecked","unchecked","unchecked"),'jazz'=>array("", "unchecked","unchecked","unchecked","unchecked","unchecked"),'pop'=>array("", "unchecked","unchecked","unchecked","unchecked","unchecked"),'punk'=>array("", "unchecked","unchecked","unchecked","unchecked","unchecked"),'reggae'=>array("", "unchecked","unchecked","unchecked","unchecked","unchecked"),'rock'=>array("", "unchecked","unchecked","unchecked","unchecked","unchecked"),'soul'=>array("", "unchecked","unchecked","unchecked","unchecked","unchecked"),'techno'=>array("", "unchecked","unchecked","unchecked","unchecked","unchecked"),'world'=>array("", "unchecked","unchecked","unchecked","unchecked","unchecked"));

$genres = array(1=>'blues',2=>'country',3=>'dance',4=>'electronic',9=>'hiphop',10=>'house',11=>'jazz',12=>'pop',13=>'punk',14=>'reggae',15=>'rock',17=>'soul',18=>'techno',20=>'world', 558=>'classical');


if($result) {
	$count = 0;
	foreach ($genres as $id=>$name) {
		if ($result[$count] && $result[$count]['nodeID'] == $id && $result[$count]['score']<=5) {
			$checked[$name][$result[$count]['score']] = "checked";
		}	
		else{
			$query = $db->prepare("INSERT INTO ike_genrescore (userID, nodeID, score) VALUES(:uID, :nodeID, 3)");
			$query-> bindParam(':uID', $session['loginID'], PDO::PARAM_INT);
			$query-> bindParam(':nodeID', $id);
			$query->execute();
			$checked[$name][3]="checked";
		}
		$count++;
	}
}
else { 
	foreach($genres as $id=>$name) {
		$query = $db->prepare("INSERT INTO ike_genrescore (userID, nodeID, score) VALUES(:uID, :nodeID, 3)");
		$query-> bindParam(':uID', $session['loginID'], PDO::PARAM_INT);
		$query-> bindParam(':nodeID', $id);
		$query->execute();
		$checked[$name][3]="checked";
	}
}
?>
<h2>Geef uw muziekvookeur op</h2>
<p>Geef voor elk genre een waardering tussen de 1 (slecht) en 5 (heel goed)</p>
<form action="<?=$frameworkRoot?>voorkeurenverwerker/" method="post">
<table>
<tr>
<td><label>Blues:</label></td>
<td><input type="radio" name="blues" value="1" <?php echo $checked['blues'][1]; ?>> 1
<input type="radio" name="blues" value="2" <?php echo $checked['blues'][2]; ?>> 2
<input type="radio" name="blues" value="3" <?php echo $checked['blues'][3]; ?>> 3
<input type="radio" name="blues" value="4" <?php echo $checked['blues'][4]; ?>> 4
<input type="radio" name="blues" value="5" <?php echo $checked['blues'][5]; ?>> 5 </td>
</tr>
<tr>
<td><label>Classical:</label></td>
<td><input type="radio" name="classical" value="1" <?php echo $checked['classical'][1]; ?>> 1
<input type="radio" name="classical" value="2" <?php echo $checked['classical'][2]; ?>> 2
<input type="radio" name="classical" value="3" <?php echo $checked['classical'][3]; ?>> 3
<input type="radio" name="classical" value="4" <?php echo $checked['classical'][4]; ?>> 4
<input type="radio" name="classical" value="5" <?php echo $checked['classical'][5]; ?>> 5 </td>
</tr>
<tr>
<td><label>Country:</label></td>
<td><input type="radio" name="country" value="1" <?php echo $checked['country'][1]; ?>> 1
<input type="radio" name="country" value="2" <?php echo $checked['country'][2]; ?>> 2
<input type="radio" name="country" value="3" <?php echo $checked['country'][3]; ?>> 3
<input type="radio" name="country" value="4" <?php echo $checked['country'][4]; ?>> 4
<input type="radio" name="country" value="5" <?php echo $checked['country'][5]; ?>> 5 </td>
</tr>
<tr>
<td><label>Dance:</label></td>
<td><input type="radio" name="dance" value="1" <?php echo $checked['dance'][1]; ?>> 1
<input type="radio" name="dance" value="2" <?php echo $checked['dance'][2]; ?>> 2
<input type="radio" name="dance" value="3" <?php echo $checked['dance'][3]; ?>> 3
<input type="radio" name="dance" value="4" <?php echo $checked['dance'][4]; ?>> 4
<input type="radio" name="dance" value="5" <?php echo $checked['dance'][5]; ?>> 5 </td>
</tr>
<tr>
<td><label>Electronic:</label></td>
<td><input type="radio" name="electronic" value="1" <?php echo $checked['electronic'][1]; ?>> 1
<input type="radio" name="electronic" value="2" <?php echo $checked['electronic'][2]; ?>> 2
<input type="radio" name="electronic" value="3" <?php echo $checked['electronic'][3]; ?>> 3
<input type="radio" name="electronic" value="4" <?php echo $checked['electronic'][4]; ?>> 4
<input type="radio" name="electronic" value="5" <?php echo $checked['electronic'][5]; ?>> 5 </td>
</tr>
<tr>
<td><label>Hip-Hop:</label></td>
<td><input type="radio" name="hiphop" value="1" <?php echo $checked['hiphop'][1]; ?>> 1
<input type="radio" name="hiphop" value="2" <?php echo $checked['hiphop'][2]; ?>> 2
<input type="radio" name="hiphop" value="3" <?php echo $checked['hiphop'][3]; ?>> 3
<input type="radio" name="hiphop" value="4" <?php echo $checked['hiphop'][4]; ?>> 4
<input type="radio" name="hiphop" value="5" <?php echo $checked['hiphop'][5]; ?>> 5 </td>
</tr>

<tr>
<td><label>House:</label></td>
<td><input type="radio" name="house" value="1" <?php echo $checked['house'][1]; ?>> 1
<input type="radio" name="house" value="2"  <?php echo $checked['house'][2]; ?>> 2
<input type="radio" name="house" value="3" <?php echo $checked['house'][3]; ?>> 3
<input type="radio" name="house" value="4" <?php echo $checked['house'][4]; ?>> 4
<input type="radio" name="house" value="5" <?php echo $checked['house'][5]; ?>> 5 </td>
</tr>
<tr>
<td><label>Jazz:</label></td>
<td><input type="radio" name="jazz" value="1"  <?php echo $checked['jazz'][1]; ?>> 1
<input type="radio" name="jazz" value="2" <?php echo $checked['jazz'][2]; ?>> 2
<input type="radio" name="jazz" value="3" <?php echo $checked['jazz'][3]; ?>> 3
<input type="radio" name="jazz" value="4" <?php echo $checked['jazz'][4]; ?>> 4
<input type="radio" name="jazz" value="5" <?php echo $checked['jazz'][5]; ?>> 5 </td>
</tr>
<tr>
<td><label>Pop:</label></td>
<td><input type="radio" name="pop" value="1" <?php echo $checked['pop'][1]; ?>> 1
<input type="radio" name="pop" value="2" <?php echo $checked['pop'][2]; ?>> 2
<input type="radio" name="pop" value="3" <?php echo $checked['pop'][3]; ?>> 3
<input type="radio" name="pop" value="4" <?php echo $checked['pop'][4]; ?>> 4
<input type="radio" name="pop" value="5" <?php echo $checked['pop'][5]; ?>> 5 </td>
</tr>
<tr>
<td><label>Punk:</label></td>
<td><input type="radio" name="punk" value="1" <?php echo $checked['punk'][1]; ?>> 1
<input type="radio" name="punk" value="2" <?php echo $checked['punk'][2]; ?>> 2
<input type="radio" name="punk" value="3" <?php echo $checked['punk'][3]; ?>> 3
<input type="radio" name="punk" value="4" <?php echo $checked['punk'][4]; ?>> 4
<input type="radio" name="punk" value="5" <?php echo $checked['punk'][5]; ?>> 5 </td>
</tr>
<tr>
<td><label>Reggae:</label></td>
<td><input type="radio" name="reggae" value="1" <?php echo $checked['reggae'][1]; ?>> 1
<input type="radio" name="reggae" value="2" <?php echo $checked['reggae'][2]; ?>> 2
<input type="radio" name="reggae" value="3" <?php echo $checked['reggae'][3]; ?>> 3
<input type="radio" name="reggae" value="4" <?php echo $checked['reggae'][4]; ?>> 4
<input type="radio" name="reggae" value="5" <?php echo $checked['reggae'][5]; ?>> 5 </td>
</tr>
<tr>
<td><label>Rock:</label></td>
<td><input type="radio" name="rock" value="1" <?php echo $checked['rock'][1]; ?>> 1
<input type="radio" name="rock" value="2" <?php echo $checked['rock'][2]; ?>> 2
<input type="radio" name="rock" value="3" <?php echo $checked['rock'][3]; ?>> 3
<input type="radio" name="rock" value="4" <?php echo $checked['rock'][4]; ?>> 4
<input type="radio" name="rock" value="5" <?php echo $checked['rock'][5]; ?>> 5 </td>
</tr>
<tr>
<td><label>Soul:</label></td>
<td><input type="radio" name="soul" value="1"  <?php echo $checked['soul'][1]; ?>> 1
<input type="radio" name="soul" value="2" <?php echo $checked['soul'][2]; ?>> 2
<input type="radio" name="soul" value="3"  <?php echo $checked['soul'][3]; ?>> 3
<input type="radio" name="soul" value="4" <?php echo $checked['soul'][4]; ?>> 4
<input type="radio" name="soul" value="5" <?php echo $checked['soul'][5]; ?>> 5 </td>
</tr>
<tr>
<td><label>Techno:</label></td>
<td><input type="radio" name="techno" value="1" <?php echo $checked['techno'][1]; ?>> 1
<input type="radio" name="techno" value="2" <?php echo $checked['techno'][2]; ?>> 2
<input type="radio" name="techno" value="3" <?php echo $checked['techno'][3]; ?>> 3
<input type="radio" name="techno" value="4" <?php echo $checked['techno'][4]; ?>> 4
<input type="radio" name="techno" value="5" <?php echo $checked['techno'][5]; ?>> 5 </td>
</tr>
<tr>
<td><label>World:</label></td>
<td><input type="radio" name="world" value="1" <?php echo $checked['world'][1]; ?>> 1
<input type="radio" name="world" value="2" <?php echo $checked['world'][2]; ?>> 2
<input type="radio" name="world" value="3" <?php echo $checked['world'][3]; ?>> 3
<input type="radio" name="world" value="4" <?php echo $checked['world'][4]; ?>> 4
<input type="radio" name="world" value="5" <?php echo $checked['world'][5]; ?>> 5 </td>
</tr>
</table>
<input type="submit" value="Verder" />
</form>
<?php
fw_footer();
?>