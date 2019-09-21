<?php
require_once ('helpers.php');

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
mysqli_set_charset($link, "utf8");
check($link);

$search = '';
$sql = 'SELECT name FROM category';
$category = db_fetch_data($link, $sql, []);

if (isset($_GET['page'])) {
   $search = trim($_GET['search']);
}
else {
    $search = 'Поиск лота';
}

$page = include_template('login.php', []);
$layout_pages = include_template('layout-pages.php',[
    'page' => $page,
    'category' => $category,
    'search' =>  $search,
    'title' => 'Вход'
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['email', 'password'];
    $dict = ['email' => 'Введите e-mail', 'password' => 'Введите пароль'];
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
        $sql = "SELECT id, name, password, contacts FROM user WHERE email = ?";
        $result = db_fetch_data_assos($link, $sql, [$_POST['email']]);

            if (!$result) {
                $dict['email'] = 'Неверный email';
                $errors['email'] = 'form__item--invalid';
            }
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
            'search' =>  $search,
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
        $dict['password'] = 'Невнерный пароль';
        $errors['password'] = 'form__item--invalid';
        $page = include_template('login.php', [
            'error' => $error,
            'errors' => $errors,
            'dict' => $dict
        ]);
        $layout_pages = include_template('layout-pages.php',[
            'page' => $page,
            'category' => $category,
            'search' =>  $search,
            'title' => 'Вход'
        ]);
        print($layout_pages);
        die();
    }
}

print($layout_pages);
