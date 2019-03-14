<?php

require_once 'init.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $errors = [];

    if (isset($_GET['id'])) {
        $lot = lot_bets_data($link, $_GET['id']);
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
                $user_id = $_SESSION['user']['id'];
                $res = insert_bet($link, $amount, $user_id, $lot['id']);

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