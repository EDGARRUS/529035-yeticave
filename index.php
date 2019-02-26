<?php

require_once 'init.php';

$sql = 'SELECT `id`, `name` FROM categories';
$result = mysqli_query($link, $sql);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$sql = 'SELECT TIMEDIFF(lots.date_end, NOW()) as live, lots.id, lots.date_create, lots.name, lots.start_price, lots.image, categories.name as cat FROM lots LEFT JOIN categories ON lots.category_id = categories.id WHERE lots.winner_id is null ORDER BY lots.date_create DESC LIMIT 9;';
$result = mysqli_query($link, $sql);

if ($result) {
    $catalog_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$page_content = include_template('index.php', ["menu_list" => $catalog_list, "menu" => $categories]);
$layout_content = include_template('layout.php', ["content" => $page_content, "title" => "Главная", "menu" => $categories]);
echo $layout_content;

?>

