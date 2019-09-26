<?php
require_once ('helpers.php');
require_once ('getwinner.php');

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
if ($link == false) {
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
    die();
}
mysqli_set_charset($link, "utf8");

$sql = 'SELECT id, name, symbol FROM category';
$category = db_fetch_data($link, $sql, []);

$sql_lots = 'SELECT l.id, l.name, l.image, c.name cat, l.price, l.data_end FROM lots l
    LEFT JOIN category c ON c.id = l.lots_category
    WHERE data_end > NOW() ORDER BY l.creation_date DESC';
$lots = db_fetch_data($link, $sql_lots, []);

$page_content = include_template('main.php', [
    'lots' => $lots,
    'category' => $category
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'category' => $category,
    'title' => 'Главная страница'
]);

print($layout_content);



