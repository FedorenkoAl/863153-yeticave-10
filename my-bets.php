<?php
require_once ('helpers.php');

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
if ($link == false) {
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
    die();
}
mysqli_set_charset($link, "utf8");

$page = '';
$layout_pages = '';
$lot_active = [];
$lots_win = [];
$lots_end =[];
$rate_lots = [];
$lot_main = [];

$sql = 'SELECT id, name FROM category';
$category = db_fetch_data($link, $sql, []);

$sql = "SELECT r.rate_lots FROM rate r
    WHERE r.rate_user = ?
    ORDER BY r.date_create DESC";
$result = db_fetch_data($link, $sql, [$_SESSION['user']['id']]);

if (!$result) {
    $page = include_template ('my-bets.php', []);
    $layout_pages = include_template('layout-pages.php',[
        'page' => $page,
        'category' => $category,
        'title' => 'Мои ставки'
    ]);
    print($layout_pages);
    die();;
}


foreach ($result as $key => $value) {
    $rate_lots[] = $value['rate_lots'];
}

$result = array_unique($rate_lots);

 foreach ($result as $value) {
   $sql = "SELECT  l.data_end, l.id, l.image, l.name, c.name cat, r.price, r.date_create FROM lots l
        LEFT JOIN rate r ON r.rate_lots = $value and r.rate_user = ?
        LEFT JOIN category c ON c.id = l.lots_category
        WHERE l.id = $value
        ORDER BY r.date_create DESC LIMIT 1
       ";
    $rate_lots = db_fetch_data_assos($link, $sql, [$_SESSION['user']['id']]);
    $lot_main[] = $rate_lots;
}

foreach ($lot_main as $key => $value) {
    if ((strtotime($value['data_end']) - time()) < 0 ) {
       $sql = "SELECT r.rate_user FROM rate r
            WHERE r.rate_lots = ?  ORDER BY r.price DESC LIMIT 1";
            $result = db_fetch_data_assos($link, $sql, [$value['id']]);
                if ($result) {
                    if ($_SESSION['user']['id'] == $result['rate_user']) {
                        $lots_win[] = $value;
                    }
                    else {
                        $lots_end[] = $value;
                    }
                }
    }
    else {
        $lot_active[] = $value;
    }
}

$page = include_template ('my-bets.php', [
    'lot_active' => $lot_active,
    'lots_end' => $lots_end,
    'lots_win' => $lots_win
]);

$layout_pages = include_template('layout-pages.php',[
    'page' => $page,
    'category' => $category,
    'title' => 'Мои ставки'
]);
print($layout_pages);
