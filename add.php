<?php

require_once 'init.php';

session_start();

if(!isset($_SESSION['user'])) {
    http_response_code(403);
    exit();
}

$sql = 'SELECT `id`, `name` FROM categories';
$result = mysqli_query($link, $sql);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

//Проверка на заполненность полей
if ($_SERVER['REQUEST_METHOD'] === 'POST') { //Проверка, что сделан пост запрос
    $lot = $_POST; //Передаю значения в лот
    $required_fields = ['name', 'category_id', 'description', 'start_price', 'step_price', 'date_end']; //задаю массив необходимых полей
    $errors = []; //создаю пустой массив с ошибками
    $dict = ['name' => 'Введите наименование лота', 'categories' => 'Выберите категорию', 'description' => 'Напишите описание лота', 'start_price' => 'Введите начальную цену', 'step_price' => 'Введите шаг ставки', 'date_end' => 'Введите дату завершения торгов', 'form' => 'Пожалуйста, исправьте ошибки в форме.']; //задаю появляющийся текст при ошибки конкретного поля

    foreach ($required_fields as $field) { //прохожусь циклом по полям в поиске пустого
        if (empty($_POST[$field])) {
            $errors[$field] = 'Ошибка';
        }
    }

    if($lot['start_price'] <= 0) {
        $errors['start_price'] = 'Неверная цена';
    }

    if($lot['step_price'] <= 0) {
        $errors['step_price'] = 'Неверная цена';
    }

    if(!is_numeric($lot['start_price']) and !is_int($lot['start_price'])) {
        $errors['start_price'] = 'Некорректная цена';
    }

    if(!is_numeric($lot['step_price']) and !is_int($lot['step_price'])) {
        $errors['step_price'] = 'Некорректная ставка';
    }


    if(strtotime($lot['date_end']) < strtotime('+1 day', strtotime('NOW'))) {
        $errors['date_1'] = 'Неверная дата';
    }

    if(check_date_format(date('d.m.Y', strtotime($lot['date_end'])))) {
        $errors['date_2'] = 'Неверная дата';
    }






//Проверка на загрузку файла
    if (isset($_FILES['image']['name'])) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $path = $_FILES['image']['name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        if ($file_type !== "image/jpeg" and $file_type !== "image/png") {
            $errors['file'] = 'Загрузите картинку в формате JPEG или PNG';
        } else {
            move_uploaded_file($tmp_name, 'img/' . $path);
            $lot['image'] = 'img/' . $path;
        }
    } else {
        $errors['file'] = 'Вы не загрузили файл';
    }
//Подсчет ошибок
    if (empty($errors)) {

        $sql = 'INSERT INTO lots (date_create, category_id, date_end, name, description, start_price, step_price, image, author_id) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, 2)';
        $stmt = db_get_prepare_stmt($link, $sql, [$lot['category_id'], $lot['date_end'], $lot['name'], $lot['description'], $lot['start_price'], $lot['step_price'], $lot['image']]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($link);

            header("Location: lot.php?id=" . $lot_id);
        }
    } else {
        $page_content = include_template('add.php', ['menu' => $categories, 'lot' => $lot, 'errors' => $errors, 'dict' => $dict]);
    }
} else {
    $page_content = include_template('add.php', ['menu' => $categories]);
}


$layout_content = include_template('layout.php', ["content" => $page_content, "title" => "Добавление лота", "menu" => $categories]);
echo $layout_content;

?>