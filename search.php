<?php
require_once ('helpers.php');

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
if (!$link) {
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
    die();
}
mysqli_set_charset($link, "utf8");

$sql = 'SELECT id, name FROM category';
$category = db_fetch_data($link, $sql, []);

$page_items = 9;

$page = '';
$layout_pages = '';
$lots_item = [];
$lots = '';
$search = '';
$pages_count = 0;
$cur_page = 0;
$offset = 0;
$pages = [];

if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = trim($_GET['search']);

    if (!isset($_GET['page'])) {
        $_GET['page'] = 1;
        $cur_page = $_GET['page'];
        $sql = "SELECT l.id  FROM lots l
            LEFT JOIN category c ON c.id = l.lots_category
            WHERE MATCH(l.name, l.description)
            AGAINST( ? IN BOOLEAN MODE) and l.data_end >= NOW()
            ORDER BY l.creation_date DESC";
        $lots = count(db_fetch_data($link, $sql, [$search]));
            if ($lots == 0) {
                $page = include_template('search.php', []);
                $layout_pages = include_template('layout-pages.php',[
                'page' => $page,
                'category' => $category,
                'search' =>  $search,
                'title' => 'Результаты поиска'
                ]);
                print($layout_pages);
                 die();
            }
        $pages_count = ceil($lots / $page_items);
        $offset = ($cur_page - 1) * $page_items;
        $pages = range(1, $pages_count);

        $sql = "SELECT l.id, l.name, l.image, c.name cat, l.price, l.data_end FROM lots l
            LEFT JOIN category c ON c.id = l.lots_category
            WHERE MATCH(l.name, l.description)
            AGAINST( ? IN BOOLEAN MODE) and l.data_end >= NOW()
            ORDER BY l.creation_date DESC LIMIT 9 OFFSET $offset";
        $lots_item = db_fetch_data($link, $sql, [$search]);

    }
    else  {
        if (isset($_GET['count'])) {
            $pages_count = $_GET['count'];
            $search = trim($_GET['search']);
            $cur_page = $_GET['page'];
            $offset = ($cur_page - 1) * $page_items;
            $pages = range(1, $pages_count);
            $sql = "SELECT l.id, l.name, l.image, c.name cat, l.price, l.data_end FROM lots l
                LEFT JOIN category c ON c.id = l.lots_category
                WHERE MATCH(l.name, l.description)
                AGAINST( ? IN BOOLEAN MODE) and l.data_end >= NOW()
                ORDER BY l.creation_date DESC LIMIT 9 OFFSET $offset";
            $lots_item = db_fetch_data($link, $sql, [$search]);
        }
    }
}
else {
    header('Location: /');
    die();
}

$page = include_template('search.php', [
    'lots_item' => $lots_item,
    'pages_count' => $pages_count,
    'search' =>  $search,
    'pages' => $pages,
    'cur_page' => $cur_page
]);

$layout_pages = include_template('layout-pages.php',[
    'page' => $page,
    'category' => $category,
    'search' =>  $search,
    'title' => 'Результаты поиска'
]);
print($layout_pages);

