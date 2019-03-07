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

function dt_interval($date_end)
{
    $dt_end = date_create($date_end);
    $dt_now = date_create("now");
    $dt_diff = date_diff($dt_end, $dt_now);
    $time_count = date_interval_format($dt_diff, "%H:%i");

    return $time_count;
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

$is_auth = rand(0, 1);

$user_name = 'EDUARD'; // укажите здесь ваше имя

$index = 0;
$num_count = count($catalog_easy);

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

?>