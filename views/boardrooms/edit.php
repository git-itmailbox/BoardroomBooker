<!DOCTYPE html>
<html>
<head>
        <link rel="stylesheet" type="text/css" href="/css/mystyle.css">
</head>
<body>
<div><H1>
        <a class="homeUrl" href='/mainpage/home'>Boardroom Booker</a>
    </H1>
</div>
<h3>Boardroom edit: <?= $boardroom->id ?></h3>
<?php
require_once('views/boardrooms/form.php');

?>
</body>
</html>
