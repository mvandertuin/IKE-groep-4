<?php
//Empty example page
global $scripts;
//add names of JS files to $scripts array if needed

//Include helper functions for generating an HTML page
useLib('htmlpage');

//generate page header
fw_header('Inloggen');

//the page itself
?>
<h2>Inloggen</h2>
<p>Log in om gebruik te maken van de suggesties.</p>
<form action="<?= $frameworkRoot ?>session/open/" method="POST">
	<label>Emailadres: <input type="text" id="loginEmail" name="loginEmail" /></label>
    <label>Wachtwoord: <input type="password" id="loginPass" name="loginPass" /></label>
    <input type="submit" value="Log in" />
</form>
<?php
//generate page footer
fw_footer();
?>