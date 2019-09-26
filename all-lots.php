<?php
require_once ('helpers.php');

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
if ($link == false) {
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
    die();
}
mysqli_set_charset($link, "utf8");

$sql = 'SELECT id, name FROM category';
$category = db_fetch_data($link, $sql, []);

$category_name = [];
$page = '';
$layout_pages = '';
$page_items = 1;
$lots_count = 0;
$pages_count = 0;
$cur_page = 0;
$offset = 0;
$pages = '';
$lots_category = [];

if (isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0)) {
    $sql = 'SELECT name FROM category WHERE id = ?';
    $category_name = db_fetch_data_assos($link, $sql, [$_GET['id']]);
   if (!isset($_GET['page'])) {
        $_GET['page'] = 1;
        $sql = "SELECT l.id l FROM lots l
                LEFT JOIN category c
                ON l.lots_category = c.id
                WHERE c.id = ? and l.data_end >= NOW() ORDER BY l.creation_date DESC";
        $lots_count = COUNT(db_fetch_data($link, $sql, [$_GET['id']]));

        $pages_count = ceil($lots_count / $page_items);
        $cur_page = $_GET['page'];
        $offset = ($cur_page - 1) * $page_items;
        $pages = range(1, $pages_count);

        $sql = "SELECT l.id l, l.name, l.image, l.price, c.name c, l.data_end FROM lots l
                LEFT JOIN category c
                ON l.lots_category = c.id
            WHERE c.id = ? and l.data_end >= NOW() ORDER BY l.creation_date DESC LIMIT 1 OFFSET $offset";
        $lots_category = db_fetch_data($link, $sql, [$_GET['id']]);
    }
    else {
        if (isset($_GET['count'])) {
            $pages_count = $_GET['count'];
            $cur_page = $_GET['page'];
            $offset = ($cur_page - 1) * $page_items;
            $pages = range(1, $pages_count);
            $sql = "SELECT l.id l, l.name, l.image, l.price, c.name c, l.data_end FROM lots l
                LEFT JOIN category c
                ON l.lots_category = c.id
                WHERE c.id = ? and l.data_end >= NOW() ORDER BY l.creation_date DESC LIMIT 1 OFFSET $offset";
            $lots_category = db_fetch_data($link, $sql, [$_GET['id']]);
        }
    }
}
else {
    header('Location: /');
    die();
}
$page = include_template('all-lots.php', [
            'category_name' => $category_name,
            'lots_category' => $lots_category,
            'pages_count' => $pages_count,
            'pages' => $pages,
            'cur_page' => $cur_page
        ]);

        $layout_pages = include_template('layout-pages.php',[
            'page' => $page,
            'category' => $category,
            'title' => 'Все лоты в категории'
        ]);
    print($layout_pages);
