<?php
require_once ('helpers.php');

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
mysqli_set_charset($link, "utf8");
check($link);

$sql = 'SELECT name, symbol FROM category';
$category = db_fetch_data($link, $sql, []);

$search = trim($_GET['search']);

$page_items = 1;
    if (!$search && !isset($_GET['page'])) {
        header('Location: /');
    }

    if ($search && !isset($_GET['page'])) {

        $sql_search_lots = "SELECT l.id, l.name, l.image, c.name cat, l.price, l.data_end FROM lots l
        LEFT JOIN category c ON c.id = l.lots_category
        WHERE MATCH(l.name, l.description)
        AGAINST( ? IN BOOLEAN MODE)
        ORDER BY l.creation_date DESC";
    $lots_count = db_fetch_data_num_rows($link, $sql_search_lots, [$search]);

        $pages_count = ceil($lots_count / $page_items);
        $_GET['page'] = 1;
        $cur_page = $_GET['page'];
        $offset = ($cur_page - 1) * $page_items;
        $pages = range(1, $pages_count);

        $lots_item_sql = "SELECT l.id, l.name, l.image, c.name cat, l.price, l.data_end FROM lots l
            LEFT JOIN category c ON c.id = l.lots_category
            WHERE MATCH(l.name, l.description)
            AGAINST( ? IN BOOLEAN MODE)
            ORDER BY l.creation_date DESC LIMIT 1 OFFSET $offset";
        $lots_item = db_fetch_data($link, $lots_item_sql, [$search]);
    }
    else  {
        $search = $_GET['search'];
       $cur_page = $_GET['page'];
       $pages_count = $_GET['count'];
        $offset = ($cur_page - 1) * $page_items;
        $pages = range(1, $pages_count);
        $lots_item_sql = "SELECT l.id, l.name, l.image, c.name cat, l.price, l.data_end FROM lots l
            LEFT JOIN category c ON c.id = l.lots_category
            WHERE MATCH(l.name, l.description)
            AGAINST( ? IN BOOLEAN MODE)
            ORDER BY l.creation_date DESC LIMIT 1 OFFSET $offset";
            $lots_item = db_fetch_data($link, $lots_item_sql, [$search]);

    }

$page = include_template('search.php', [
    'category' => $category,
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

