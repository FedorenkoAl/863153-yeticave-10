<?php
require_once ('helpers.php');

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
mysqli_set_charset($link, "utf8");
check($link);

$sql = 'SELECT name FROM category';
$category = db_fetch_data($link, $sql, []);

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
    if (count($errors)) {
        $page_sign = include_template('sign-up.php',[
        'category' => $category,
        'error' =>  $error,
        'errors' => $errors,
        'dict' => $dict
        ]);
        print($page_sign);
         die();
    }
    $sql = "SELECT id FROM user WHERE email = ? LIMIT 1";
    $result = db_fetch_data_assos($link, $sql, [$_POST['email']]);

    if ($result) {
            $errors['email'] = 'form__item--invalid';
            $dict['email'] = 'Пользователь с этим email уже зарегистрирован';
            $page_sign = include_template('sign-up.php',[
            'category' => $category,
            'error' =>  $error,
            'errors' => $errors,
             'dict' => $dict
             ]);
             print($page_sign);
             die();
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $errors['email'] = 'form__item--invalid';
        $dict['email'] = 'Email должен быт корректным';
        $page_sign = include_template('sign-up.php',[
         'category' => $category,
         'error' =>  $error,
         'errors' => $errors,
          'dict' => $dict
         ]);
         print($page_sign);
         die();
    }

    $sql = "INSERT INTO user (date_registration, email, name, password, contak)
    VALUES (?, ?, ?, ?, ?)";
     $result_email = db_insert_data($link, $sql, [date('Y.m.d H:i:s'), $_POST['email'], $_POST['name'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['message']]);
    check($result_email);

    header('Location: /');
    die();

}

else {
    $page_sign = include_template('sign-up.php',[
    'category' => $category
]);
}
print($page_sign);
