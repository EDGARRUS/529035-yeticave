<?php

require_once 'init.php';

$sql = 'SELECT `id`, `name` FROM categories';
$result = mysqli_query($link, $sql);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$id = mysqli_real_escape_string($link, $_GET['id']);

$sql = "SELECT TIMEDIFF(lots.date_end, NOW()) as live, lots.id, lots.date_create, lots.name, lots.start_price, lots.image, categories.name as cat FROM lots LEFT JOIN categories ON lots.category_id = categories.id WHERE lots.id = '%s'";

$sql = sprintf($sql, $id);

if ($result = mysqli_query($link, $sql)) {
    $lot = mysqli_fetch_array($result, MYSQLI_ASSOC);
}

$page_content = include_template('lot.php', ['lot' => '$lot']);
$layout_content = include_template('layout.php', ["content" => $page_content, "title" => "Главная", "menu" => $categories]);
echo $layout_content;

?>

