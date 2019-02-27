<?php

require_once 'init.php';

$sql = 'SELECT `id`, `name` FROM categories';
$result = mysqli_query($link, $sql);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = $_POST;
    $required_fields = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $errors = [];
    $dict = ['lot-name' => 'Введите наименование лота', 'category' => 'Выберите категорию', 'message' => 'Напишите описание лота', 'lot-rate' => 'Введите начальную цену', 'lot-step' => 'Введите шаг ставки', 'lot-date' => 'Введите дату завершения торгов'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Ошибка';
        }
    }

    if (isset($_FILES['lot-image']['name'])) {
        $tmp_name = $_FILES['lot-image']['tmp_name'];
        $path = $_FILES['lot-image']['name'];
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

    if (count($errors)) {
        $page_content = include_template('add.php', ['lot' => $lot, 'errors' => $errors, 'dict' => $dict, "menu" => $categories]);
    }

    else {
        $page_content = include_template('lot.php', ['lot' => $lot, "menu" => $categories]);
    }
}

else {
    $page_content = include_template('add.php', []);
}

$layout_content = include_template('layout.php', ["content" => $page_content, "title" => "Добавление лота", "menu" => $categories]);
echo $layout_content;

?>