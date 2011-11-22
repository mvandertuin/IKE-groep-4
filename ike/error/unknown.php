<!doctype html>
<html>
<head>
<title><?= $code ?> - An error occured</title>
</head>
<body>
<h1><?= $code ?> - An error occured</h1>
<p>An unknown error occured while running the script. An additional message (if present) is displayed below</p>
<p><?= $message ?></p>
<hr />
<p>Served by Bright framework on <?= $_SERVER['HTTP_HOST'] ?></p>
</body>
</html>