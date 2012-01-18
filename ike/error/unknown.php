<!doctype html>
<html>
<head>
    <title><?= $code ?> - An error occurred</title>
</head>
<body>
<h1><?= $code ?> - An error occurred</h1>

<p>An unknown error occurred while running the script. An additional message (if present) is displayed below</p>

<p><?= $message ?></p>
<hr/>
<p>Served by Bright framework on <?= $_SERVER['HTTP_HOST'] ?></p>
</body>
</html>