<?php

function format_price($right_price) {

    $right_price = ceil($right_price);

    if($right_price > 1000) {
        $right_price = number_format($right_price,0,""," ");
    }
    $right_price .= " ₽";
    return $right_price;
}

function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

function check_date_format($date) {
        $result = false;
    $regexp = '/(\d{2})\.(\d{2})\.(\d{4})/m';
    if (preg_match($regexp, $date, $parts) && count($parts) == 4) {
        $result = checkdate($parts[2], $parts[1], $parts[3]);
    }
    return $result;
}

/**
 * Функция правильного окончания передаваемых слов в зависимости от передаваемого количества
 *
 * @param int $number Количество элементов
 * @param array $words_array Массив со словами, которые склоняются от количества (например, ['минута', 'минуты', 'минут']
 *
 * @return mixed|string Слово со спряженным окончанием
 */

function words_ending($number, $words_array)
{
    switch (($number >= 20) ? $number % 10 : $number) {
        case 1:
            $result = array_key_exists(0, $words_array) ? $words_array[0] : 'n';
            break;
        case 2:
        case 3:
        case 4:
            $result = array_key_exists(1, $words_array) ? $words_array[1] : 'n';
            break;
        default:
            $result = array_key_exists(2, $words_array) ? $words_array[2] : 'n';
    }

    return $result;
}

/**
 * Функция рассчета времени в относительном формате
 *
 * @param int $ts Временная метка прошедшего времени
 * @return false|string Строка, отражающая кол-во прошедшего времени
 */

function calc_time_ago(int $ts)
{
    $delta_ts = strtotime('now') - $ts;

    if ($delta_ts >= 86400) {
        return date("d.m.y в H:i", $ts);
    } else {
        if ($delta_ts >= 3600) {
            return (floor($delta_ts / 3600) . " " . words_ending(floor($delta_ts / 3600),
                    ["час", "часа", "часов"]) . " назад");
        } else {
            return (floor($delta_ts / 60) . " " . words_ending(floor($delta_ts / 60),
                    ["минута", "минуты", "минут"]) . " назад");
        }
    }
}

function calc_time_to_end($ts)
{
    $now = strtotime('now');
    $difference = $ts - $now;
    $hours = floor($difference/ 3600);
    if($hours < 10) {
        $hours = "0" . $hours;
    }
    $minutes = ($hours % 60);
    if($minutes < 10) {
        $minutes = "0" . $minutes;
    }
    return $hours.":".$minutes;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */

function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
}

function check_category_value ($categories, $new_lot_category) {
    foreach ($categories as $val) {
        if ((int) $val['id'] === (int) $new_lot_category) {
            return true;
        }
    }
    return false;
}

function add_lot($lot_data, $link) {
    $sql = 'INSERT INTO lots (date_create, category_id, date_end, name, description, start_price, step_price, image, author_id) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql, $lot_data);
    $res = mysqli_stmt_execute($stmt);

    if ($res) {
        $lot_id = mysqli_insert_id($link);

        header("Location: lot.php?id=" . $lot_id);
    }
}

function menu_categories($link) {
    $sql = 'SELECT `id`, `name` FROM categories';
    $result = mysqli_query($link, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

function main_lot_list($link) {
    $sql = 'SELECT lots.date_end, lots.id, lots.date_create, lots.name, lots.start_price, lots.image, categories.name as cat FROM lots LEFT JOIN categories ON lots.category_id = categories.id WHERE lots.winner_id is null AND lots.date_end > NOW() ORDER BY lots.date_create DESC LIMIT 9;';
    $result = mysqli_query($link, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
        return null;
}

function user_email_finder($link, $user_email) {
    $email = mysqli_real_escape_string($link, $user_email);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);
    $result = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;
    return $result;
}

function add_user($link, $form) {
    $email = mysqli_real_escape_string($link, $form['email']);
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);


    if (mysqli_num_rows($res) > 0) {
        $dict['email'] = 'Пользователь с этим email уже зарегистрирован';
        return $dict['email'];
    } else {
        $password = password_hash($form['pass'], PASSWORD_DEFAULT);
        if (!empty($_FILES['image']['name'])) {
            $sql = 'INSERT INTO users (date_registr, email, name, pass, phone, image) VALUES (NOW(), ?, ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [$form['email'], $form['name'], $password, $form['phone'], $form['image']]);
            $res = mysqli_stmt_execute($stmt);
        } else {
            $sql = 'INSERT INTO users (date_registr, email, name, pass, phone) VALUES (NOW(), ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [$form['email'], $form['name'], $password, $form['phone']]);
            $res = mysqli_stmt_execute($stmt);
        }

        if ($res) {
            header("Location: /index.php");
            exit();
        }
    }

}

function lot_data($link, $id) {
    $sql1 = "SELECT lots.date_end, bets.amount + lots.step_price as future_price, lots.id, lots.date_create, lots.name, lots.start_price, lots.description, lots.step_price, lots.image, lots.author_id, bets.amount as now_price, bets.user_id, categories.name as cat FROM lots LEFT JOIN categories ON lots.category_id = categories.id LEFT JOIN bets ON lots.id = bets.lot_id WHERE lots.id = '%s' ORDER BY bets.amount DESC limit 1";
    $sql1 = sprintf($sql1, $id);
    if ($result1 = mysqli_query($link, $sql1)) {
        if (!mysqli_num_rows($result1)) {
            return null;
        } else {
            $lot = mysqli_fetch_array($result1, MYSQLI_ASSOC);
            if (empty($lot['now_price'])) {
                $lot['now_price'] = $lot['start_price'];
                $lot['future_price'] = $lot['start_price']+$lot['step_price'];
            }
            return $lot;

        }
    }
}

function bets_count($link, $id) {
    $sql2 = "SELECT bets.id, bets.created_at, bets.amount, users.name, bets.lot_id from bets LEFT JOIN users ON users.id = bets.user_id WHERE bets.lot_id = '%s' ORDER BY bets.created_at DESC limit 10";
    $sql2 = sprintf($sql2, $id);
    $result2 = mysqli_query($link, $sql2);
    $all_bets = mysqli_num_rows($result2);
    return $all_bets;
}

function bets_history($link, $id) {
    $sql2 = "SELECT bets.id, bets.created_at, bets.amount, users.name, bets.lot_id from bets LEFT JOIN users ON users.id = bets.user_id WHERE bets.lot_id = '%s' ORDER BY bets.created_at DESC limit 10";
    $sql2 = sprintf($sql2, $id);
    $result2 = mysqli_query($link, $sql2);
    $bets = mysqli_fetch_all($result2, MYSQLI_ASSOC);
    return $bets;
}

function lot_bets_data($link, $id) {
    $sql = "SELECT  bets.amount + lots.step_price AS future_price, bets.amount AS now_price, lots.id FROM lots LEFT JOIN bets ON lots.id = bets.lot_id WHERE lots.id = '%s' ORDER BY bets.amount DESC LIMIT 1";
    $sql = sprintf($sql, $id);
    $result = mysqli_query($link, $sql);
    return mysqli_fetch_array($result, MYSQLI_ASSOC);

}

function insert_bet($link, $amount, $user_id, $lot_id) {
    $sql = 'INSERT INTO bets (created_at, amount, user_id, lot_id) VALUES (NOW(), ?, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql, [$amount, $user_id, $lot_id]);
    return mysqli_stmt_execute($stmt);
}

?>