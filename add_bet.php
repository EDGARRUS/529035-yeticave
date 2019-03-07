<?php

require_once 'init.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $errors = [];

    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($link, $_GET['id']);
        $sql = "SELECT  bets.amount + lots.step_price AS future_price, bets.amount AS now_price, lots.id FROM lots LEFT JOIN bets ON lots.id = bets.lot_id WHERE lots.id = '%s' ORDER BY bets.amount DESC LIMIT 1";
        $sql = sprintf($sql, $id);
        $result = mysqli_query($link, $sql);
        $lot = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        $page_content = include_template('404.php', ['error' => 'Лот по данному идентификатору не найден']);
        exit();
    }

    if (empty($form['amount'])) {
        $errors['amount'] = "Не заполнено поле";
        header("Location: lot.php?id=" . $lot['id'] . "&errors=true");
        exit();
    } else {
        if (!is_numeric($form['amount']) and $form['amount'] > 0 and !is_int($form['amount'])) {
            $errors['amount'] = "Введите целое число";
            header("Location: lot.php?id=" . $lot['id'] . "&errors=true");
            exit();
        } else {

            $amount = mysqli_real_escape_string($link, $form['amount']);


            if (empty($lot['now_price'])) {
                $lot['future_price'] = $lot['start_price'] + $lot['step_price'];
            }
            if ($amount < $lot['future_price']) {
                $errors['amount'] = "Ставка должна быть выше";
                header("Location: lot.php?id=" . $lot['id'] . "&errors=true");
                exit();
            }

            if (empty($errors)) {
                $sql = 'INSERT INTO bets (created_at, amount, user_id, lot_id) VALUES (NOW(), ?, ?, ?)';
                $stmt = db_get_prepare_stmt($link, $sql, [$amount, $_SESSION['user']['id'], $lot['id']]);
                $res = mysqli_stmt_execute($stmt);

                if ($res) {
                    header("Location: lot.php?id=" . $lot['id']);
                    exit();

                }
            } else {

                header("Location: lot.php?id=" . $lot['id'] . "&errors=true");
                exit();
            }

        }
    }
}


?>