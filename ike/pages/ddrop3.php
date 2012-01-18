<?php
useLib('htmlpage');
useLib('graph');
useLib('musicbrainz');
useLib('audioscrobbler');
useLib('votes');
global $session;
global $scripts;
$scripts[] = 'ratingscript';
$scripts[] = 'dragdrop';
//generate page header
fw_header('SongRecommend');
?>
<div class="header">SongRecommend<a class="pref" href="introduction/">Bewerk voorkeuren</a> <a class="pref"
                                                                                               href="nodez/">Statistieken</a>
</div>
<div class="wrapper" id="wrapper">

    <div class="deletebox" id="del">

        <p class="valign">delete</p>
    </div>
    <div class="wrapper2">
        <?php
        $votedon = getVotedOn($db, $session['loginID']);
        buildPage($tags, $votedon, $session['loginID']);

        function buildPage($tags, $voted_on, $id)
        {
            $artists1 = getArtistsByTag($tags, 3);
            $graph = new UserGraph($id);
            $node_array = $graph->getHighestRatedNodes();
            $tagarr = array();
            $i = 0;
            foreach ($node_array as $recom) {
                $tagarr[$i] = $recom[1]->getName();
                $i++;
            }
            $artists2 = getArtistsByTag($tagarr, count($tagarr));
            $artists = array_merge($artists1, $artists2);
            $artists = array_merge($artists, getAllVotedArtists($id));
            $artists = array_unique($artists);
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
                    <div id="<?=$mbid ?>_-1"
                         class="neg ratingbutton <?=$mbid ?><?php if ($rat == -1) echo " votedon"; ?>">-1
                    </div>
                    <div id="<?=$mbid ?>_1"
                         class="pos ratingbutton <?=$mbid ?><?php if ($rat == 1) echo " votedon"; ?>">+1
                    </div>
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
    </div>
    <div class="simple_overlay" id="alertbox">
        bla
    </div>
    <div class="more">Add Recommendations</div>
</div>

