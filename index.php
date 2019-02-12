<?php

require_once "functions.php";

$page_content = include_template('index.php', ["menu_list" => $catalog_list, "menu" => $catalog_easy]);
$layout_content = include_template('layout.php', ["content" => $page_content, "title" => "Главная", "menu" => $catalog_easy]);
echo $layout_content;

?>

