<?php
global $pathparts;
global $frameworkRoot;
if (isset($pathparts[1])) {
    if ($pathparts[1] == 'open') {
        if (isset($_POST['loginEmail']) && isset($_POST['loginPass'])) {
            if (login($_POST['loginEmail'], $_POST['loginPass'])) {
                header('location: ' . $frameworkRoot . 'ddrop3/');
                die();
            } else {
                echo 'a';
            }
        }
    } else {
        logout();
        header('location: ' . $frameworkRoot . 'loginpage/logout.html');
    }
}
//header('location: '.$frameworkRoot.'loginpage/loginf.html');
die();
?>