<?php
//Empty example page
global $scripts;
//add names of JS files to $scripts array if needed
$scripts[] = 'register';

//Include helper functions for generating an HTML page
useLib('htmlpage');

//generate page header
fw_header('page_title');

//the page itself
?>
<div class="cpage">
    <div class="header">SongRecommend</div>
    <div class="cpagemain">
        <h2>Registratie</h2>

        <p>Om u te kunnen registreren voor SongRecommend, hebben we minstens enkele gegevens van u nodig.</p>

        <p> Het opgeven van een emailadres is niet verplicht, maar wordt wel aangeraden indien u verwacht feedback te
            geven over het systeem. Indien u niet uw emailadres wenst op te geven, vult u in het veld email uw gewenste
            inlognaam in, en kunt u in plaats van uw mailadres met die inlognaam inloggen.</p>

        <form method="post" action="#">
            <label>Naam:<input type="text" id="naam"/></label>
            <label>Email:<input type="text" id="inlog"/></label>
            <label>Wachtwoord:<input type="password" id="ww1"/></label>
            <label>Herhaal wachtwoord:<input type="password" id="ww2"/></label>
            <input type="submit" id="btnreg" onclick="ajaxregister();return false;" value="Registreer"/>
            <img id="imgreg" src="images/loader.gif" alt="loading"/>
        </form>
    </div>
</div>
<?php
//generate page footer
fw_footer();
?>