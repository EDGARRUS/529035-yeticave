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

$is_auth = rand(0, 1);

$user_name = 'EDUARD'; // укажите здесь ваше имя

$index = 0;
$num_count = count($catalog_easy);

?>