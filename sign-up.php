<?php
require_once ('helpers.php');

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
mysqli_set_charset($link, "utf8");
check($link);

$search = '';

if (isset($_GET['page'])) {
   $search = trim($_GET['search']);
}
else {
    $search = 'Поиск лота';
}

$sql = 'SELECT name FROM category';
$category = db_fetch_data($link, $sql, []);

$page = include_template('sign-up.php', []);

$layout_pages = include_template('layout-pages.php',[
    'page' => $page,
    'category' => $category,
    'search' =>  $search,
    'title' => 'Регистрация'
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['email', 'password', 'name', 'message'];
    $dict = ['email' => 'Введите e-mail', 'password' => 'Введите пароль', 'name' => 'Введите имя', 'message' => 'Напишите как с вами связаться', 'form' => 'Пожалуйста, исправьте ошибки в форме.'];
    $errors = [];
    $error = 'form--invalid';


    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'form__item--invalid';
        }
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)  && !empty($_POST['email'])){
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

    if (count($errors)) {
        $page = include_template('sign-up.php', [
            'error' => $error,
            'errors' => $errors,
            'dict' => $dict
             ]);
        $layout_pages = include_template('layout-pages.php',[
            'page' => $page,
            'category' => $category,
            'search' =>  $search,
            'title' => 'Регистрация'
            ]);
         print($layout_pages);
         die();
    }

    $sql = "INSERT INTO user (date_registration, email, name, password, contacts)
    VALUES (?, ?, ?, ?, ?)";
    $result_email = db_insert_data($link, $sql, [date('Y.m.d H:i:s'), $_POST['email'], $_POST['name'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['message']]);
    if ($result_email) {
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
