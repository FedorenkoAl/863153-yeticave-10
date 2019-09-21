<?php
require_once ('helpers.php');
require_once ('getwinner.php');

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
mysqli_set_charset($link, "utf8");
check($link);

$search = '';

$sql = 'SELECT name, symbol FROM category';
$category = db_fetch_data($link, $sql, []);

$sql_lots = 'SELECT l.id, l.name, l.image, c.name cat, l.price, l.data_end FROM lots l
    LEFT JOIN category c ON c.id = l.lots_category
    WHERE data_end >= NOW() ORDER BY l.creation_date DESC';
$lots = db_fetch_data($link, $sql_lots, []);

if (isset($_GET['page'])) {
   $search = trim($_GET['search']);
}
else {
    $search = 'Поиск лота';
}

$page_content = include_template('main.php', [
    'lots' => $lots,
    'category' => $category
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'category' => $category,
    'search' =>  $search,
    'title' => 'Главная страница'
]);

print($layout_content);



