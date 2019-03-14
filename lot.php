<?php

require_once 'init.php';
session_start();

$categories = menu_categories($link);

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($link, $_GET['id']);
}

$lot = lot_data($link, $id);
if ($lot === null) {
    http_response_code(404);
    $page_content = include_template('404.php', ['error' => 'Лот по данному идентификатору не найден', "menu" => $categories]);
} else {
    $all_bets = bets_count($link, $id);
    $bets = bets_history($link, $id);
    $page_content = include_template('lot.php', ['lot' => $lot, 'bets' => $bets, "menu" => $categories, 'all_bets' => $all_bets]);
}


$layout_content = include_template('layout.php', ["content" => $page_content, "title" => $lot['name'], "menu" => $categories, $_SESSION['user']]);
echo $layout_content;

?>

