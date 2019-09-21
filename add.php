<?php
require_once ('helpers.php');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
mysqli_set_charset($link, "utf8");
check($link);

$category_name = [];
$format = '';
$new_name ='';
if (!isset($_SESSION['user'])) {
       http_response_code(403);
         die();
}

if (isset($_GET['page'])) {
   $search = trim($_GET['search']);
}
else {
    $search = 'Поиск лота';
}

$sql = 'SELECT name FROM category';
$category = db_fetch_data($link, $sql, []);

$page = include_template('add.php', [
    'category' => $category
]);

$layout_pages = include_template('layout-pages.php',[
    'page' => $page,
    'category' => $category,
    'search' => 'Поиск лота',
    'title' => 'Добавление лота'
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['lot-name', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $dict = ['lot-name' => 'Введите наименование лота', 'category' => 'Выберете катеорию', 'message' => 'Напишите описание лота', 'lot-rate' => 'Введите начальную цену','lot-step' => 'Введите шаг ставки', 'lot-date' => 'Введите дату завершения торгов'];

    $errors = [];
    $error = 'form--invalid';
    foreach ($required as $key) {
       if (empty($_POST[$key])) {
            $errors[$key] = 'form__item--invalid';
        }
    }

    if (!ctype_digit($_POST['lot-rate']) && !empty($_POST['lot-rate'])) {
        $errors['lot-rate'] = 'form__item--invalid';
        $dict['lot-rate'] = 'Неккоректная сумма';
    }

    if (!ctype_digit($_POST['lot-step']) && !empty($_POST['lot-step'])) {
        $errors['lot-step'] = 'form__item--invalid';
        $dict['lot-step'] = 'Неккоректная сумма';
    }


    if (!is_date_valid($_POST['lot-date']) || (strtotime($_POST['lot-date']) - time()) < (strtotime('tomorrow') - time())) {
        $errors['lot-date'] = 'form__item--invalid';
    }

    if ($_POST['category'] == 'Выберите категорию') {
        $errors['category'] = 'form__item--invalid';
    }
    else {
        foreach ($category as $key => $value) {
            $category_name[] = $value['name'];
        }
        if (!in_array($_POST['category'], $category_name)) {
            $errors['category'] = 'form__item--invalid';
            $dict['category'] = 'Указана несуществующая категория';
        }
    }

    if (!$_FILES['lot-img']['name']) {
        $errors['lot-img'] = 'Загрузите картинку';
    }
    else {
        if ((mime_content_type($_FILES['lot-img']['tmp_name']) !== 'image/png') && (mime_content_type($_FILES['lot-img']['tmp_name']) !== 'image/jpeg')) {
                $errors['lot-img'] = 'Загрузите картинку в формате jpeg/png';
        }
        if ($_FILES ['lot-img']['size'] > 200000) {
            $errors['lot-img'] = 'Максимальный размер файла: 20Кб';
        }
    }

    if (count($errors)) {
        $page = include_template('add.php', [
            'category' => $category,
            'error' => $error,
            'errors' => $errors,
            'dict' => $dict
             ]);
        $layout_pages = include_template('layout-pages.php',[
            'page' => $page,
            'category' => $category,
            'search' => 'Поиск лота',
            'title' => 'Добавление лота'
            ]);
         print($layout_pages);
         die();
    }

    if (mime_content_type($_FILES['lot-img']['tmp_name']) == 'image/png') {
        $format = 'png';
    }

    if (mime_content_type($_FILES['lot-img']['tmp_name']) == 'image/jpeg') {
        $format = 'jpeg';
    }

    $new_name = uniqid() . $format;
    move_uploaded_file($_FILES['lot-img']['tmp_name'], 'uploads/' . $new_name);

    $sql_cat_id = "SELECT id FROM category WHERE name = ?";
            $result_category = db_fetch_data_assos($link, $sql_cat_id, [$_POST['category']]);

    $sql_lot = 'INSERT INTO lots (creation_date, name, description,image, price, step, lots_category, data_end, author)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';

    $result_lot = db_insert_data($link, $sql_lot, [date('Y.m.d H:i:s'),  $_POST['lot-name'],$_POST['message'], 'uploads/' . $new_name,  $_POST['lot-rate'],  $_POST['lot-step'], $result_category['id'], $_POST['lot-date'], $_SESSION['user']['id']]);
    if ($result_lot) {
    header('Location: lot.php?id=' . $result_lot);
        die();
    }
    else {
        $error = mysqli_error($link);
        print('Произошла ошибка при выполнении запроса' . $error);
        die();
    }
}
print($layout_pages);
