<?php

require_once 'init.php';
session_start();

$categories = menu_categories($link);

$catalog_list = main_lot_list($link);


$page_content = include_template('index.php', ["menu_list" => $catalog_list, "menu" => $categories]);

if(isset($_SESSION['user'])) {
    $layout_content = include_template('layout.php', ["content" => $page_content, "title" => "Главная", "menu" => $categories, $_SESSION['user']]); } else {
    $layout_content = include_template('layout.php', ["content" => $page_content, "title" => "Главная", "menu" => $categories]);
}
echo $layout_content;

?>

