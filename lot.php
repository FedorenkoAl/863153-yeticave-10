<?php
require_once ('helpers.php');
$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
mysqli_set_charset($link, "utf8");

if (!$link) {
   print('Ошибка подключения:' . mysqli_connect_error());
   die();
}

if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
    $lot_id = mysqli_real_escape_string($link, $_GET['id']);
}

else {
   http_response_code(404);
    $content = include_template('404.php',
    ['error' => '404 Страница не найдена']);
    print($content);
    die();
}

$sql = 'SELECT name FROM category';
$category = db_fetch_data($link, $sql, []);

$sql_id = "SELECT l.name, l.image, l.price, c.name c, l.data_end, l.description FROM lots l
    LEFT JOIN category c
    ON l.lots_category = c.id
    WHERE l.id = $lot_id";

$lots_id = db_fetch_data_assos($link,$sql_id, []);
    if (!$lots_id) {
        http_response_code(404);
        $content = include_template('404.php',
        ['error' => '404 Страница не найдена']);
        print($content);
        die();
    }

$page_lot = include_template ('lot.php',[
    'lots_id' => $lots_id,
    'category' => $category

]);
print($page_lot);

