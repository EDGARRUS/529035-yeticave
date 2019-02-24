<?php

require_once "functions.php";

$db = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'yeticave',
];

$link = mysqli_connect($db['host'],$db['user'],$db['password'],$db['database']);
mysqli_set_charset($link, "utf8");

?>