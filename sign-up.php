<?php
require_once ('helpers.php');

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
if ($link == false) {
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
    die();
}
mysqli_set_charset($link, "utf8");

$required = [];
$dict = [];
$errors = [];
$error = '';
$result = [];

$sql = 'SELECT id, name FROM category';
$category = db_fetch_data($link, $sql, []);

$page = include_template('sign-up.php', []);
$layout_pages = include_template('layout-pages.php',[
    'page' => $page,
    'category' => $category,
    'title' => 'Регистрация'
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['password', 'name', 'message'];
    $dict = ['email' => 'Введите e-mail', 'password' => 'Введите пароль', 'name' => 'Введите имя', 'message' => 'Напишите как с вами связаться', 'form' => 'Пожалуйста, исправьте ошибки в форме.'];
   $error = 'form--invalid';

    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'form__item--invalid';
        }
    }
    if (!empty($_POST['email'])) {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $errors['email'] = 'form__item--invalid';
            $dict['email'] = 'Email должен быт корректным';
        }
        else {
            $sql = "SELECT id FROM user WHERE email = ? LIMIT 1";
            $result = db_fetch_data_assos($link, $sql, [$_POST['email']]);
                if ($result) {
                    $errors['email'] = 'form__item--invalid';
                    $dict['email'] = 'Пользователь с этим email уже зарегистрирован';
                }
        }
    }
    else {
        $errors['email'] = 'form__item--invalid';
    }

    if (count($errors)) {
        $page = include_template('sign-up.php', [
            'error' => $error,
            'errors' => $errors,
            'dict' => $dict
             ]);
        $layout_pages = include_template('layout-pages.php',[
            'page' => $page,
            'category' => $category,
            'title' => 'Регистрация'
            ]);
         print($layout_pages);
         die();
    }

    $sql = "INSERT INTO user (date_registration, email, name, password, contacts)
    VALUES (?, ?, ?, ?, ?)";
    $result = db_insert_data($link, $sql, [date('Y.m.d H:i:s'), $_POST['email'], $_POST['name'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['message']]);
    if ($result) {
        header('Location: /');
        die();
    }
    else {
        $error = mysqli_error($link);
        print('Произошла ошибка при выполнении запроса' . $error);
        die();
    }
}
print($layout_pages);
