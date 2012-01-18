<?php
/*
 *	Bright framework
 * 	copyright Ruben Visser / dcm360.nl 2011
*/
error_reporting(E_ALL);
include('framework/config.php');
if ($maintenance) die(include('pages/maintenance.php'));
include('framework/basic.php');

reconstructPath();
if (!(is_dir('./' . $path)) && (file_exists('./' . $path))) {
    include('framework/rawfile.php');
    //rawfile will complete the execution of this script
    die();
}

useLib('module');
useLib('database');
dbStart();
prepareModule("login");
if ($path == '') {
    $pathparts[0] = 'loginpage';
}
if ($isModulePage) {
    execModule();
} elseif (file_exists('./pages/' . $pathparts[0] . '.php')) {
    include('./pages/' . $pathparts[0] . '.php');
} else {
    broken('404', "I'm sorry, I wasn't able to find the page $path :'(");
}
dbEnd();
?>