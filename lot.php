<?php

require_once 'init.php';
session_start();

$sql = 'SELECT `id`, `name` FROM categories';
$result = mysqli_query($link, $sql);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($link, $_GET['id']);
}

$sql1 = "SELECT lots.date_end, bets.amount + lots.step_price as future_price, lots.id, lots.date_create, lots.name, lots.start_price, lots.description, lots.step_price, lots.image, lots.author_id, bets.amount as now_price, bets.user_id, categories.name as cat FROM lots LEFT JOIN categories ON lots.category_id = categories.id LEFT JOIN bets ON lots.id = bets.lot_id WHERE lots.id = '%s' ORDER BY bets.amount DESC limit 1";

$sql1 = sprintf($sql1, $id);

$sql2 = "SELECT bets.id, bets.created_at, bets.amount, users.name, bets.lot_id from bets LEFT JOIN users ON users.id = bets.user_id WHERE bets.lot_id = '%s' ORDER BY bets.created_at DESC limit 10";

$sql2 = sprintf($sql2, $id);
$result2 = mysqli_query($link, $sql2);
$all_bets = mysqli_num_rows($result2);

if ($result1 = mysqli_query($link, $sql1)) {

    if (!mysqli_num_rows($result1)) {
        http_response_code(404);
        $page_content = include_template('404.php', ['error' => 'Лот по данному идентификатору не найден', "menu" => $categories]);
}
    else {
        $lot = mysqli_fetch_array($result1, MYSQLI_ASSOC);
        $bets = mysqli_fetch_all($result2, MYSQLI_ASSOC);
        if (empty($lot['now_price'])) {
            $lot['now_price'] = $lot['start_price'];
            $lot['future_price'] = $lot['start_price']+$lot['step_price'];
        }

        $page_content = include_template('lot.php', ['lot' => $lot, 'bets' => $bets, "menu" => $categories, 'all_bets' => $all_bets]);
    }
}

$layout_content = include_template('layout.php', ["content" => $page_content, "title" => $lot['name'], "menu" => $categories, $_SESSION['user']]);
echo $layout_content;

?>

