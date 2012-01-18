<?php
useLib('htmlpage');
useLib('graph');
global $session;
$g = new UserGraph($session['loginID']);
$top10 = $g->getHighestRatedNodes();
$flop10 = $g->getLowestRatedNodes();
global $scripts;
$scripts[] = 'arbor';
$scripts[] = 'atlas2';
fw_header('SongRecommend');
?>
<div class="header">SongRecommend<a class="pref" href="introduction/">Bewerk voorkeuren</a><a class="pref"
                                                                                              href="ddrop3/">Suggesties</a>
</div>
<canvas id="viewport" width="800" height="600"></canvas>
<div class="infostat"><p>Links ziet u uw muzieksmaak, de top 10 genres die u volgens onze gegevens het meest bevallen
    zijn bruin gekleurd. De andere genres zijn lichter gekleurd en
    zijn gerelateerd aan uw top 10, deze genres kunt u ook leuk vinden. Hieronder ziet u uw top 10 en flop 10.</p>

    <h3>Top 10</h3>
    <ol>
        <?php
        foreach ($top10 as $top) {
            print "<li>" . $top[1]->getName() . "</li>";
        }
        ?>
    </ol>
    <h3>Flop 10</h3>
    <ol>
        <?php
        foreach ($flop10 as $flop) {
            print "<li>" . $flop[1]->getName() . "</li>";
        }
        ?>
    </ol>
</div>
<?php fw_footer(); ?>