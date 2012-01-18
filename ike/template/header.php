<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?=$title?></title>
    <base href="<?=$frameworkRoot?>"/>
    <?php
    $scripts_a = array();
    $scripts_a[] = "jquery-1.7";
    $scripts_a[] = "jquery-ui-1.8.16.custom";
    $scripts_a[] = "jquery.ui.core";
    $scripts_a[] = "jquery.ui.sortable";
    $scripts_a[] = "jquery.tools.min";
    global $scripts;
    $scripts = array_merge($scripts_a, $scripts);
    ?>
    <link rel="stylesheet" href="template/newstyle.css"/>
    <link rel="stylesheet" href="template/base/jquery.ui.all.css"/>
    <?php
    foreach ($scripts as $script) {
        ?>
        <script type="text/javascript" src="<?=$frameworkRoot?>javascript/<?=$script?>.js"></script>
        <?php
    }
    ?>
</head>
<body>