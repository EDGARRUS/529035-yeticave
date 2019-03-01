<?php

require_once 'init.php';

$sql = 'SELECT `id`, `name` FROM categories';
$result = mysqli_query($link, $sql);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$tpl_data = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $errors = [];
    $dict = ['name' => 'Введите имя', 'pass' => 'Введите пароль', 'email' => 'Введите email', 'phone' => 'Введите контактные данные', 'form' => 'Пожалуйста, исправьте ошибки в форме.'];
    $req_fields = ['email', 'pass', 'name', 'phone'];

  foreach ($req_fields as $field) {
        if (empty($form[$field])) {
            $errors[$field] = "Не заполнено поле";
        } }


    if (filter_var($form['email'],FILTER_VALIDATE_EMAIL)) {

    } else {
        $errors [] = 'Неправильный формат email';

    }

   //Проверка на загрузку файла
    if (isset($_FILES['image']['name'])) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $path = $_FILES['image']['name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        if ($file_type !== "image/jpeg") {
            $errors['file'] = 'Загрузите картинку в формате JPG';
        } else {
            move_uploaded_file($tmp_name, 'img/' . $path);
            $form['image'] = 'img/' . $path;
        } }



    if (empty($errors)) {
        $email = mysqli_real_escape_string($link, $form['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($link, $sql);


        if (mysqli_num_rows($res) > 0) {
        $errors['duplicate'] = 'Пользователь с этим email уже зарегистрирован';
        } else {
        $password = password_hash($form['pass'], PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users (date_registr, email, name, pass, phone, image) VALUES (NOW(), ?, ?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, [$form['email'], $form['name'], $password, $form['phone'], $form['image']]);
        $res = mysqli_stmt_execute($stmt);
        } }

   if ($res && empty($errors)) {
        header("Location: /index.php");
        exit();
    }


}


$page_content = include_template('sign-up.php', ["menu" => $categories, "errors" => $errors, 'dict' => $dict, 'form' => $form]);
$layout_content = include_template('layout.php', ["content" => $page_content, "title" => "Регистрация пользователя", "menu" => $categories]);
echo $layout_content;

?>