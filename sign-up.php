<?php

require_once 'init.php';

$categories = menu_categories($link);

$tpl_data = [];
/* Делаю регистрацию */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $errors = [];
    $dict = ['name' => 'Введите имя', 'pass' => 'Введите пароль', 'email' => 'Введите email', 'phone' => 'Введите контактные данные', 'form' => 'Пожалуйста, исправьте ошибки в форме.'];
    $req_fields = ['email', 'pass', 'name', 'phone'];

    foreach ($req_fields as $field) {
        if (empty($form[$field])) {
            $errors[$field] = "Не заполнено поле";
        }
    }


    if (filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {

    } else {
        $errors ['email'] = 'Неправильный формат email';

    }

    //Проверка на загрузку файла
    if (!empty($_FILES['image']['name'])) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $path = $_FILES['image']['name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        if ($file_type === "image/jpeg" or $file_type === "image/png") {
            move_uploaded_file($tmp_name, 'img/' . $path);
            $form['image'] = 'img/' . $path;
        } else {
            $errors['file'] = 'Загрузите картинку в формате JPG';
        }
    }


    if (empty($errors)) {
        add_user($link, $form);
    }


}

if(!empty($_POST)) {
    $page_content = include_template('sign-up.php', ["menu" => $categories, "errors" => $errors, 'dict' => $dict, 'form' => $form, $_POST]);
} else {
    $page_content = include_template('sign-up.php', ["menu" => $categories]);
}
$layout_content = include_template('layout.php', ["content" => $page_content, "title" => "Регистрация пользователя", "menu" => $categories]);
echo $layout_content;

?>