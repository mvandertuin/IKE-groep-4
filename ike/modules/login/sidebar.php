<?php
global $loginModule;
global $session;
global $frameworkRoot;
if($loginModule){
	?><div class="sidebox" id="sideloginbox"><?php
	if($session['loginID']==0){
		//User isn't logged in
		?>
<h3>Inloggen</h3>
<form action="<?= $frameworkRoot ?>session/open/" method="POST">
	<label>Emailadres:<input type="text" id="loginEmail" name="loginEmail" /></label>
    <label>Wachtwoord:<input type="password" id="loginPass" name="loginPass" /></label>
    <input type="submit" value="Inloggen" />
</form>
	<?php
	}else{
		//
		?>
<h3>Beheren</h3>
<?php
if($session['userType']==0){
	?>
<p><a href="<?= $frameworkRoot ?>bedrijf/tonen.html">Uw gegevens</a></p>
<?php
}else{
	?>
	<p><a href="<?= $frameworkRoot ?>partners/tonen.html">Bedrijvenoverzicht</a></p>
	<p><a href="<?= $frameworkRoot ?>facturen/tonen.html">Facturenoverzicht</a></p>
	<?php
}?>
<p><a href="<?= $frameworkRoot ?>session/close/">Uitloggen</a></p>
        <?php
	}
	?></div><?php
}else{
	broken('900', 'Module login not loaded');
}