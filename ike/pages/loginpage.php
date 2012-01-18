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
<div class="cpage">
    <div class="header">SongRecommend</div>
    <div class="cpagemain">
        <h2>Introductie: SongRecommend</h2>

        <p>SongRecommend is een systeem dat ontwikkeld is door drie studenten van TU Delft voor het Information and
            Knowledge Engineering project. Omdat dit systeem is gemaakt om persoonlijke suggesties te geven, is het
            nodig om een profiel aan te maken op deze site.</p>

        <p><a href="regstep1/">Naar het registratieformulier</a></p>

        <h2>Inloggen</h2>

        <p>Log in om gebruik te maken van de suggesties.</p>

        <form action="<?= $frameworkRoot ?>session/open/" method="POST">
            <label>Emailadres: <input type="text" id="loginEmail" name="loginEmail"/></label>
            <label>Wachtwoord: <input type="password" id="loginPass" name="loginPass"/></label>
            <input type="submit" value="Log in"/>
        </form>
    </div>
</div>
<?php
//generate page footer
fw_footer();
?>