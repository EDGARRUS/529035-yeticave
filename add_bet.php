<?php

require_once 'init.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $errors = [];

    if (empty($form['amount'])) {
        $errors['amount'] = "Не заполнено поле";
    } else {
        if (!is_numeric($form['amount']) && $form['amount'] > 0) {
            $errors['amount'] = "Введите число";
        } else {

            $amount = mysqli_real_escape_string($link, $form['amount']);

            if (isset($_GET['id'])) {
                $id = mysqli_real_escape_string($link, $_GET['id']);
                $sql = "SELECT  bets.amount + lots.step_price AS future_price, bets.amount AS now_price, lots.id FROM lots LEFT JOIN bets ON lots.id = bets.lot_id WHERE lots.id = '%s' ORDER BY bets.amount DESC LIMIT 1";
                $sql = sprintf($sql, $id);
                $result = mysqli_query($link, $sql);
                $lot = mysqli_fetch_array($result, MYSQLI_ASSOC);

                if (empty($lot['now_price'])) {
                    $lot['future_price'] = $lot['start_price'] + $lot['step_price'];
                }
                if ($amount < $lot['future_price']) {
                    $errors['amount'] = "Ставка должна быть выше";
                } else {
                    $sql = 'INSERT INTO bets (created_at, amount, user_id, lot_id) VALUES (NOW(), ?, ?, ?)';
                    $stmt = db_get_prepare_stmt($link, $sql, [$amount, $_SESSION['user']['id'], $lot['id']]);
                    $res = mysqli_stmt_execute($stmt);

                    if($res) {
                        header("Location: lot.php?id=" . $lot['id']);
                    }
                }
            }
        }
    }
}


?>