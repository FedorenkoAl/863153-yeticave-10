<?php
require_once ('helpers.php');

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
mysqli_set_charset($link, "utf8");
check($link);
$sql = 'SELECT name, symbol FROM category';
$category = db_fetch_data($link, $sql, []);
$lot_active = [];
$lots_win = [];
$lots_end =[];

$sql_rate_lot = "SELECT r.rate_lots FROM rate r
    WHERE r.rate_user = ?
    ORDER BY r.date_create DESC";
$result_rate_lot = db_fetch_data($link, $sql_rate_lot, [$_SESSION['user']['id']]);

foreach ($result_rate_lot as $key => $value) {
    $rate_lots[] = $value['rate_lots'];
}

$rate_lots_one = array_unique($rate_lots);

foreach ($rate_lots_one as $value) {
   $sql_lots = "SELECT  l.data_end, l.id, l.image, l.name, c.name cat, r.price, r.date_create FROM lots l
        LEFT JOIN rate r ON r.rate_lots = $value and r.rate_user = ?
        LEFT JOIN category c ON c.id = l.lots_category
        WHERE l.id = $value
        ORDER BY r.date_create DESC LIMIT 1
       ";
    $result = db_fetch_data_assos($link, $sql_lots, [$_SESSION['user']['id']]);
    $lot_main[] = $result;
}

foreach ($lot_main as $key => $value) {
    if ((strtotime($value['data_end']) - time()) < 0 ) {
        $id = $value['id'];
            $sql_end = "SELECT r.rate_user FROM rate r
                WHERE r.rate_lots = $id  ORDER BY r.price DESC LIMIT 1";
            $result_end = mysqli_query($link, $sql_end);
            $end = mysqli_fetch_assoc($result_end);
            if ($_SESSION['user']['id'] == $end['rate_user'])  {
                $lots_win[] = $value;
            }
            else {
                $lots_end[] = $value;
            }
    }
    else {
        $lot_active[] = $value;
    }

}

$rate = include_template ('my-bets.php', [
 'category' => $category,
 'lot_active' => $lot_active,
 'lots_end' => $lots_end,
 'lots_win' => $lots_win

 ]);
print($rate);
