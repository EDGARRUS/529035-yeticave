<?php

require_once 'init.php';

session_start();

$sql = 'SELECT `id`, `name` FROM categories';
$result = mysqli_query($link, $sql);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $errors = [];
    $req_fields = ['email', 'pass'];

    foreach ($req_fields as $field) {
        if (empty($form[$field])) {
            $errors[$field] = "Не заполнено поле";
        }
    }

    if (!empty($form['email'])) {
        $email = mysqli_real_escape_string($link, $form['email']);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $res = mysqli_query($link, $sql);

        $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

        if (!count($errors) and $user) {
            if (password_verify($form['pass'], $user['pass'])) {
                $_SESSION['user'] = $user;
            } else {
                $errors['pass'] = 'Неверный пароль';
            }
        } else {
            $errors['email'] = 'Такой пользователь не найден';
        }
    }

    if (count($errors)) {
        $page_content = include_template('login.php', ["menu" => $categories, "errors" => $errors, 'form' => $form]);
    } else {
        header("Location: /");
        exit();
    }
}

else {
        $page_content = include_template('login.php', ["menu" => $categories]);

}


$layout_content = include_template('layout.php', ["content" => $page_content, "title" => "Вход пользователя", "menu" => $categories]);
echo $layout_content;

?>