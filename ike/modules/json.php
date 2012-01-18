<?php
global $pathparts;
global $frameworkRoot;
if (count($pathparts) > 1) {
    if (file_exists('modules/json/' . $pathparts[1] . '.php')) {
        include('modules/json/' . $pathparts[1] . '.php');
    } else {
        jsonBroken(404, 'No generator found');
    }
} else {
    jsonBroken(200, 'Module active');
}

?>