<?php
require_once ('helpers.php');
$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
mysqli_set_charset($link, "utf8");
check($link);

 if (!isset($_SESSION['user'])) {
       http_response_code(403);
         die();
}

$sql = 'SELECT name FROM category';
$category = db_fetch_data($link, $sql, []);
check($category);

$page_add = include_template('add.php', [
'category' => $category,
'option' => 'Выберите категорию'
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
    if ($_POST['category'] == 'Выберите категорию') {
        $option = 'Выберите категорию';
        $errors['category'] = 'form__item--invalid';
    }
    else {
        $option = $_POST['category'];
    }

    the_end($category, $option, $error, $errors, $dict);

   if(!ctype_digit($_POST['lot-rate'])) {
        $errors['lot-rate'] = 'form__item--invalid';
        the_end($category, $option, $error, $errors, $dict);
    }


    if (!ctype_digit($_POST['lot-step'])) {
        $errors['lot-step'] = 'form__item--invalid';
        the_end($category, $option, $error, $errors, $dict);
    }


    if (!is_date_valid($_POST['lot-date']) || ((strtotime($_POST['lot-date']) - time()) <  86400)) {
         $errors['lot-date'] = 'form__item--invalid';
        the_end($category, $option, $error, $errors, $dict);
    }

    if (!$_FILES['lot-img']['name']) {
        $file_number = 1;
         $page_add = include_template('add.php', [
        'category' => $category,
        'option' => $_POST['category'],
        'error' => $error,
        'int' => $int
        ]);
        print($page_add);
        die();
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $tmp_name = $_FILES['lot-img']['tmp_name'];
    $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== 'image/png' && $file_type !== 'image/jpeg') {
            $file_number = 2;
             $page_add = include_template('add.php', [
            'category' => $category,
            'option' => $_POST['category'],
            'error' => $error,
            'int' => $int
            ]);
            print($page_add);
            die();
        }
     $file_size = $_FILES ['lot-img']['size'];
        if ($file_size > 200000) {
            $file_number = 3;
            $page_add = include_template('add.php', [
            'category' => $category,
            'option' => $_POST['category'],
            'error' => $error,
            'int' => $int
            ]);
            print($page_add);
            die();
        }
    move_uploaded_file($_FILES['lot-img']['tmp_name'], 'uploads/' . $_FILES['lot-img']['name']);


    $sql_cat_id = "SELECT id FROM category WHERE name = ?";
    $result_category = db_fetch_data_assos($link, $sql_cat_id, [$_POST['category']]);
    check($result_category);

    $sql_lot = 'INSERT INTO lots (creation_date, name, description,image, price, step, lots_category, data_end)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

    $result_lot = db_insert_data($link, $sql_lot, [date('Y.m.d H:i:s'),  $_POST['lot-name'],$_POST['message'], 'uploads/' . $_FILES['lot-img']['name'],  $_POST['lot-rate'],  $_POST['lot-step'], $result_category['id'], $_POST['lot-date']]);

    check($result_lot);
    if ($result_lot) {
        header('Location: lot.php?id=' . $result_lot);
         $page_lot = include_template ('lot.php',[
        'lots_id' => $lots_id,
        'category' => $category
        ]);
        print($page_lot);
        die();
    }
    else {
        $page_add = include_template('add.php', [
        'category' => $category,
        'option' => 'Выберите категорию'
        ]);
    }
}
print($page_add);
