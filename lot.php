<?php

require_once 'init.php';

$sql = 'SELECT `id`, `name` FROM categories';
$result = mysqli_query($link, $sql);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($link, $_GET['id']);
}

$sql = "SELECT TIMEDIFF(lots.date_end, NOW()) as live, bets.amount + lots.step_price as future_price, lots.id, lots.date_create, lots.name, lots.start_price, lots.description, lots.step_price, lots.image, bets.amount as now_price, categories.name as cat FROM lots LEFT JOIN categories ON lots.category_id = categories.id LEFT JOIN bets ON lots.id = bets.lot_id WHERE lots.id = '%s' ORDER BY bets.amount DESC limit 1";

$sql = sprintf($sql, $id);

if ($result = mysqli_query($link, $sql)) {

    if (!mysqli_num_rows($result)) {
        http_response_code(404);
        $page_content = include_template('404.php', ['error' => 'Лот по данному идентификатору не найден', "menu" => $categories]);
}
    else {
        $lot = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if (empty($lot[now_price])) {
            $lot[now_price] = $lot[start_price];
            $lot[future_price] = $lot[start_price]+$lot[step_price];
        }

        $page_content = include_template('lot.php', ['lot' => $lot, "menu" => $categories]);
    }
}


$layout_content = include_template('layout.php', ["content" => $page_content, "title" => $lot['name'], "menu" => $categories]);
echo $layout_content;

?>

