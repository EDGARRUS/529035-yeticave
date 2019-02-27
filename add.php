<?php

require_once 'init.php';

$sql = 'SELECT `id`, `name` FROM categories';
$result = mysqli_query($link, $sql);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

//Проверка на заполненность полей
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = $_POST;
    $required_fields = ['name', 'categories', 'description', 'start_price', 'step_price', 'date_end'];
    $errors = [];
    $dict = ['name' => 'Введите наименование лота', 'categories' => 'Выберите категорию', 'description' => 'Напишите описание лота', 'start_price' => 'Введите начальную цену', 'step_price' => 'Введите шаг ставки', 'date_end' => 'Введите дату завершения торгов'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Ошибка';
        }
    }
//Проверка на загрузку файла
    if (isset($_FILES['image']['name'])) {
        $tmp_name = $_FILES['lot-image']['tmp_name'];
        $path = $_FILES['image']['name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        if ($file_type !== "image/jpeg") {
            $errors['file'] = 'Загрузите картинку в формате JPEG';
        } else {
            move_uploaded_file($tmp_name, 'img/' . $path);
            $lot['image'] = 'img/' . $path;
        }
    }

    else {
        $errors['file'] = 'Вы не загрузили файл';
    }
//Подсчет ошибок
    if (count($errors)) {
        $page_content = include_template('add.php', ['lot' => $lot, 'errors' => $errors, 'dict' => $dict, 'menu' => $categories]);
    }

    else {
        $page_content = include_template('add.php', ['lot' => $lot, 'menu' => $categories]);
    }
}

else {
    $page_content = include_template('add.php', ['lot' => $lot, 'menu' => $categories]);
}

$layout_content = include_template('layout.php', ["content" => $page_content, "title" => "Добавление лота", "menu" => $categories]);
echo $layout_content;

?>