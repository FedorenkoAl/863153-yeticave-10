<?php
require_once ('helpers.php');

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
if ($link == false) {
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
    die();
}
mysqli_set_charset($link, "utf8");

$dict = [];
$errors = [];
$error = '';
$result =[];

$sql = 'SELECT id, name FROM category';
$category = db_fetch_data($link, $sql, []);

$page = include_template('login.php', []);
$layout_pages = include_template('layout-pages.php',[
    'page' => $page,
    'category' => $category,
    'title' => 'Вход'
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dict = ['email' => 'Введите e-mail', 'password' => 'Введите пароль'];
    $error = 'form--invalid';

    if (!empty($_POST['email'])) {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $errors['email'] = 'form__item--invalid';
            $dict['email'] = 'Email должен быт корректным';
        }
        else {
            $sql = "SELECT id, name, password, contacts FROM user WHERE email = ?";
            $result = db_fetch_data_assos($link, $sql, [$_POST['email']]);

                if (!$result) {
                    $dict['email'] = 'Неверный email';
                    $errors['email'] = 'form__item--invalid';
                }
        }
    }
    else {
        $errors['email'] = 'form__item--invalid';
    }
    if (empty($_POST['password'])) {
        $errors['password'] = 'form__item--invalid';
    }

    if (count($errors)) {
        $page = include_template('login.php', [
            'error' => $error,
            'errors' => $errors,
            'dict' => $dict
        ]);
        $layout_pages = include_template('layout-pages.php',[
            'page' => $page,
            'category' => $category,
            'title' => 'Вход'
        ]);
        print($layout_pages);
        die();
    }

    if (password_verify($_POST['password'] ,$result['password'])) {
        $_SESSION['user'] = $result;
        header('Location: /');
        die();
    }
    else {
        $dict['password'] = 'Неверный пароль';
        $errors['password'] = 'form__item--invalid';
        $page = include_template('login.php', [
            'error' => $error,
            'errors' => $errors,
            'dict' => $dict
        ]);
        $layout_pages = include_template('layout-pages.php',[
            'page' => $page,
            'category' => $category,
            'title' => 'Вход'
        ]);
        print($layout_pages);
        die();
    }
}

print($layout_pages);
