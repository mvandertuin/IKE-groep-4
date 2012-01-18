<?php
useLib('htmlpage');
useLib('graph');
useLib('musicbrainz');
useLib('audioscrobbler');
useLib('votes');
//global $session;

//useLib('musicbrainz');
global $db;
global $session;
//generate page header
$votedon = getVotedOn($db, $session['loginID']);
$new = isset($_POST['newtrack']);
extraItem($session['loginID'], $votedon, $new);

function extraItem($id, $voted_on, $n)
{
    $graph = new UserGraph($id);
    if ($n) {
        $node_array = $graph->getHighestRatedNodes(30);
    } else {
        $node_array = $graph->getHighestRatedNodes();
    }
    $tagarr = array();
    foreach ($node_array as $recom) {
        $tagarr[] = $recom[1]->getName();
    }
    $random = array();
    if ($n) {
        $random[] = $tagarr[rand(10, 29)];
    } else {
        $random[] = $tagarr[array_rand($tagarr, 1)];
    }

    $artists = getArtistsByTag($random, count($random));
    foreach ($artists as $mbid) {
        $name = getArtistName($mbid);
        //$album = getAlbumByArtist($mbid);
        //$image = getAlbumImage($album["mbid"]);
        $track = getTrackByArtist($mbid);
        $image = $track["image"];
        $rat = getRatingByMbid($voted_on, $mbid);
        $mbid2 = $mbid;
        if ($track["mbid"] != "") {
            $mbid = $track["mbid"];
        }
        addShown($mbid);
        $genr = gettags($mbid);
        ?>
    <div id="<?=$mbid ?>" class="contentbox">
        <div class="titlebox"><?=$track["name"] ?></div>
        <img src="<?=$image ?>"/>

        <div class="titlebox"><?=$name ?></div>
        <div class="genrebox">
            <?
            $kom = "";
            foreach ($genr as $genre) {
                print($kom . $genre);
                $kom = ", ";
            }
            ?>
        </div>
        <div id="<?=$mbid ?>_-1" class="neg ratingbutton <?=$mbid ?><?php if ($rat == -1) echo " votedon"; ?>">-1</div>
        <div id="<?=$mbid ?>_1" class="pos ratingbutton <?=$mbid ?><?php if ($rat == 1) echo " votedon"; ?>">+1</div>
        <div id="info" rel="#<?=$mbid ?>_overl" class="info ratingbutton">i</div>
    </div>

    <div class="simple_overlay" id="<?=$mbid ?>_overl">
        <div class="overlay_title"><?=$name ?> - <?=$track["name"] ?></div>
        <img src="<?=$image ?>"/>

        <div class="details">
            <ul>
                <?php
                $links = getLink($mbid2);
                foreach ($links as $name => $target) {
                    echo "<li><a target='_blank' href=" . $target . ">" . $name . "</a></li>";
                }
                ?>
            </ul>
        </div>
    </div>
    <?php
    }
}

?>