<?php
/*
 *	Basic checks and functions
*/
global $pathparts;
global $path;

function reconstructPath()
{
    global $frameworkRoot;
    global $pathparts;
    global $path;
    $rew = $_SERVER['REQUEST_URI'];
    if (strpos($rew, $frameworkRoot) === false) {
        die("Configuration error: path is different");
    }
    $rew = str_replace($frameworkRoot, '', $rew);
    $broken = explode('/', $rew);
    $pathparts = array();
    foreach ($broken as $part) {
        if ((strpos($part, ':') === false) && ($part != '')) {
            $pathparts[] = $part;
        }
    }
    if (isset($pathparts[0])) {
        if (strpos($pathparts[0], '#') === 0) {
            $pathparts[0] = substr($pathparts[0], 1);
        }
    }
    $path = implode('/', $pathparts);
}

function broken($code, $message = '')
{
    if (file_exists('./error/' . $code . '.php')) {
        include('./error/' . $code . '.php');
    } else {
        include('./error/unknown.php');
    }
    die();
}

function useLib($library)
{
    if (file_exists('./framework/' . $library . '.php')) {
        require_once('./framework/' . $library . '.php');
    } else {
        broken('900', 'Internal error, page requested an unknown library (' . $library . ').');
    }
}

function hashPassword($uid, $password)
{
    return md5('+' . $uid . '+' . $password . '+');
}

function generatePassword($length = 9, $strength = 0)
{
    $vowels = 'aeuy';
    $consonants = 'bdghjmnpqrstvz';
    if ($strength & 1) {
        $consonants .= 'BDGHJLMNPQRSTVWXZ';
    }
    if ($strength & 2) {
        $vowels .= "AEUY";
    }
    if ($strength & 4) {
        $consonants .= '23456789';
    }
    if ($strength & 8) {
        $consonants .= '@#$%';
    }

    $password = '';
    $alt = time() % 2;
    for ($i = 0; $i < $length; $i++) {
        if ($alt == 1) {
            $password .= $consonants[(rand() % strlen($consonants))];
            $alt = 0;
        } else {
            $password .= $vowels[(rand() % strlen($vowels))];
            $alt = 1;
        }
    }
    return $password;
}

?>