<?php

require_once 'init.php';

session_start();

if(!isset($_SESSION['user'])) {
    http_response_code(403);
    exit();
}

$categories = menu_categories($link);

//Проверка на заполненность полей
if ($_SERVER['REQUEST_METHOD'] === 'POST') { //Проверка, что сделан пост запрос
    $lot = $_POST; //Передаю значения в лот
    $required_fields = ['name', 'category_id', 'description', 'start_price', 'step_price', 'date_end']; //задаю массив необходимых полей
    $errors = []; //создаю пустой массив с ошибками
    $dict = ['name' => 'Введите наименование лота', 'category_id' => 'Выберите категорию', 'description' => 'Напишите описание лота', 'start_price' => 'Введите начальную цену', 'step_price' => 'Введите шаг ставки', 'form' => 'Пожалуйста, исправьте ошибки в форме.']; //задаю появляющийся текст при ошибки конкретного поля

    foreach ($required_fields as $field) { //прохожусь циклом по полям в поиске пустого
        if (empty($_POST[$field])) {
            $errors[$field] = 'Ошибка';
        }

        if($field === 'category_id') {
            $lot[$field] = (int)$lot[$field];

            if ($lot[$field] < 1) {
                $errors['category_id'] = 'Выберите категорию';
            }
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

    if (!check_category_value($categories, $lot['category_id'])) {
        $errors['category_id'] = 'Ошибка ID категории';
    }

    if (check_date_format($lot['date_end']) === false) {
        $errors['date_end'] = 'Введите дату в формате дд.мм.гггг';
    } else {
        $right_lot['date_end'] = date('Y-m-d', strtotime($lot['date_end']));

        if(strtotime($right_lot['date_end']) < strtotime('+1 day', strtotime('NOW'))) {
            $errors['date_end'] = 'Дата окончания торгов должна быть минимум на 1 день позже публикации объявления';
        }
    }


//Проверка на загрузку файла
    if (!empty($_FILES['image']['name'])) {
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
        $lot['author_id'] = $_SESSION['user']['id'];
        $lot_data = [$lot['category_id'], $right_lot['date_end'], $lot['name'], $lot['description'], $lot['start_price'], $lot['step_price'], $lot['image'], $lot['author_id']];
        add_lot($lot_data, $link);
    } else {
        $page_content = include_template('add.php', ['menu' => $categories, 'lot' => $lot, 'errors' => $errors, 'dict' => $dict]);
    }
} else {
    $page_content = include_template('add.php', ['menu' => $categories]);
}


$layout_content = include_template('layout.php', ["content" => $page_content, "title" => "Добавление лота", "menu" => $categories]);
echo $layout_content;

?>