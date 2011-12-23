<?php
useLib('graph');
global $session;
$start = microtime(true);
$g = new UserGraph($session['loginID']);
$n = $g->getHigestRatedNode();
print "Request took ".(microtime(true)-$start).'s'."\r\n";
print $n->getID().': '.$n->getName()."\r\n";
$start = microtime(true);
$top10 = $g->getHigestRatedNodes();
print "Top10 took ".(microtime(true)-$start).'s'."\r\n";
$c=0;
foreach($top10 as $top){
	$c++;
	print $c.": ".$top[1]->getName()."(".$top[1]->getID().") with value ".$top[0]."\r\n";
}
?>