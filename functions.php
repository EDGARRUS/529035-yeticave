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

$is_auth = rand(0, 1);

$user_name = 'EDUARD'; // укажите здесь ваше имя

$catalog_easy = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];
$index = 0;
$num_count = count($catalog_easy);

$Rossignol = [
    "name" => "2014 Rossignol District Snowboard",
    "cat" => "Доски и лыжи",
    "price" => 10999,
    "img" => "img/lot-1.jpg",
];

$Ply = [
    "name" => "DC Ply Mens 2016/2017 Snowboard",
    "cat" => "Доски и лыжи",
    "price" => 159999,
    "img" => "img/lot-2.jpg",
];

$Union = [
    "name" => "Крепления Union Contact Pro 2015 года размер L/XL",
    "cat" => "Крепления",
    "price" => 8000,
    "img" => "img/lot-3.jpg",
];

$Charocal_bot = [
    "name" => "Ботинки для сноуборда DC Mutiny Charocal",
    "cat" => "Ботинки",
    "price" => 10999,
    "img" => "img/lot-4.jpg",
];

$Charocal_cur = [
    "name" => "Куртка для сноуборда DC Mutiny Charocal",
    "cat" => "Одежда",
    "price" => 7500,
    "img" => "img/lot-5.jpg",
];

$Canopy = [
    "name" => "Маска Oakley Canopy",
    "cat" => "Разное",
    "price" => 5400,
    "img" => "img/lot-6.jpg",
];

$catalog_list = [$Rossignol, $Ply, $Union, $Charocal_bot, $Charocal_cur, $Canopy]

?>