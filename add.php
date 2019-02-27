<?php

require_once 'init.php';

$sql = 'SELECT `id`, `name` FROM categories';
$result = mysqli_query($link, $sql);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$page_content = include_template('add.php', ['lot' => $lot, 'menu' => $categories]);

//Проверка на заполненность полей
if ($_SERVER['REQUEST_METHOD'] == 'POST') { //Проверка, что сделан пост запрос
    $lot = $_POST; //Передаю значения в лот
    $required_fields = ['name', 'categories', 'description', 'start_price', 'step_price', 'date_end']; //задаю массив необходимых полей
    $errors = []; //создаю пустой массив с ошибками
    $dict = ['name' => 'Введите наименование лота', 'categories' => 'Выберите категорию', 'description' => 'Напишите описание лота', 'start_price' => 'Введите начальную цену', 'step_price' => 'Введите шаг ставки', 'date_end' => 'Введите дату завершения торгов']; //задаю появляющийся текст при ошибки конкретного поля
    foreach ($required_fields as $field) { //прохожусь циклом по полям в поиске пустого
        if (empty($_POST[$field])) {
            $errors[$field] = 'Ошибка';
        }
    }
//Проверка на загрузку файла
    if (isset($_FILES['image']['name'])) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $path = $_FILES['image']['name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        if ($file_type !== "image/jpeg") {
            $errors['file'] = 'Загрузите картинку в формате JPEG';
        } else {
            move_uploaded_file($tmp_name, 'img/' . $path);
            $lot['image'] = 'img/' . $path;
        }
    } else {
        $errors['file'] = 'Вы не загрузили файл';
    }
//Подсчет ошибок
    if (count($errors)) {
        $page_content = include_template('add.php', ['lot' => $lot, 'errors' => $errors, 'dict' => $dict, 'menu' => $categories]);
    } else {
        $sql = 'INSERT INTO lots (date_create, category_id, date_end, name, description, start_price, step_price, image) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, [$lot['category_id'], $lot['date_create'], $lot['date_end'], $lot['name'], $lot['description'], $lot['image'], $lot['start_price'], $lot['step_price']]);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            $lot_id = mysqli_insert_id($link);

            header("Location: lot.php?id=" . $lot_id);
        }
    }
}


$layout_content = include_template('layout.php', ["content" => $page_content, "title" => "Добавление лота", "menu" => $categories]);
echo $layout_content;

?>