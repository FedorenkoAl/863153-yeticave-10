<?php
require_once ('helpers.php');

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
mysqli_set_charset($link, "utf8");
check($link);

unset($_SESSION['user']);

header('Location: /');
die();
