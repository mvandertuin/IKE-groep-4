<?php
global $isModulePage;
global $pathparts;
global $libParts;
$libParts = array();
$libParts = !$pathparts ? false : explode('-', $pathparts[0]);
$isModulePage = !$libParts ? false : file_exists('./modules/' . $libParts[0] . '.php');
function execModule()
{
    global $libParts;
    require_once('./modules/' . $libParts[0] . '.php');
}

function prepareModule($moduleName)
{
    if (file_exists('./modules/' . $moduleName . '/prepare.php')) {
        require_once('./modules/' . $moduleName . '/prepare.php');
    } else {
        broken('900', 'Unknown module or module cannot be prepared');
    }
}

function moduleExec($moduleName, $subModule = '')
{
    if (empty($subModule)) {
        include('./modules/' . $moduleName . '.php');
    } else {
        include('./modules/' . $moduleName . '/' . $subModule . '.php');
    }
}

?>