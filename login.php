<?php
require_once ('helpers.php');

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
mysqli_set_charset($link, "utf8");
check($link);

$sql = 'SELECT name FROM category';
$category = db_fetch_data($link, $sql, []);
check($category);
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

    if (count($errors)) {
        if(!$errors['email']) {

            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                $errors['email'] = 'form__item--invalid';
                $dict['email'] = 'Email должен быт корректным';
            }
        }
        $page_sign = include_template('login.php',[
        'category' => $category,
        'error' =>  $error,
        'errors' => $errors,
        'dict' => $dict
        ]);
        print($page_sign);
        die();
    }


    $sql = "SELECT id, name, password, contak FROM user WHERE email = ?";
    $result = db_fetch_data_assos($link, $sql, [$_POST['email']]);

        if (!$result) {
            $dict['email'] = 'Неверный email';
            $errors['email'] = 'form__item--invalid';
                $page_sign = include_template('login.php',[
                'category' => $category,
               'error' =>  $error,
                'errors' => $errors,
                'dict' => $dict
                ]);
                print($page_sign);
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
                $page_sign = include_template('login.php',[
                'category' => $category,
                'error' =>  $error,
                'errors' => $errors,
                'dict' => $dict
                ]);
                print($page_sign);
                die();
            }
}
else {
    $page_login = include_template('login.php',[
    'category' => $category,
    ]);
}

print($page_login);
