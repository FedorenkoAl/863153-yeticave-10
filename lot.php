<?php
require_once ('helpers.php');
$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
mysqli_set_charset($link, "utf8");

if (!$link) {
   print('Ошибка подключения:' . mysqli_connect_error());
   die();
}

if (!isset($_GET['id']) && !is_numeric($_GET['id']) && !$_GET['id'] > 0) {
    http_response_code(404);
    $content = include_template('404.php',
    ['error' => '404 Страница не найдена']);
    print($content);
    die();
}

$sql = 'SELECT name FROM category';
$category = db_fetch_data($link, $sql, []);
$user = $_SESSION['user']['id'];

$sql_id = "SELECT l.name, l.image, l.price, l.step, c.name c, l.data_end, l.description, l.author, r.price rat, r.rate_user FROM lots l
    LEFT JOIN category c
    ON l.lots_category = c.id
    LEFT JOIN rate r
    ON r.rate_lots = l.id
    WHERE l.id = ?
    ORDER BY r.date_create DESC";

$lots_id = db_fetch_data_assos($link,$sql_id, [$_GET['id']]);
    if (!$lots_id) {
        http_response_code(404);
        $content = include_template('404.php',
        ['error' => '404 Страница не найдена']);
        print($content);
        die();
    }

    if($lots_id["rat"]) {
        $lots_id['price'] = $lots_id['rat'];
    }
    $lots_id['min'] = $lots_id['price'] + $lots_id['step'];

   $sql_rates = 'SELECT r.id, r.date_create, r.price, u.name FROM rate r
        LEFT JOIN user u
        ON u.id = r.rate_user
        WHERE r.rate_lots = ?
        ORDER BY r.date_create DESC';
        $result = db_fetch_data($link, $sql_rates, [$_GET['id']]);
        $count = count($result);




 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $required = ['cost'];
        $dict = ['cost' => 'Введите сумму'];
        $errors = [];


        if (empty($_POST['cost'])) {
            $errors['cost'] = 'form__item--invalid';
            $page_lot = include_template ('lot.php',[
            'lots_id' => $lots_id,
            'category' => $category,
            'errors' => $errors,
            'dict' => $dict
            ]);
            print($page_lot);
            die();
        }

         if(!ctype_digit($_POST['cost'])) {
            $errors['cost'] = 'form__item--invalid';
            $dict['cost'] = 'Неккоректная сумма';
            $page_lot = include_template ('lot.php',[
            'lots_id' => $lots_id,
            'category' => $category,
            'errors' => $errors,
            'dict' => $dict
             ]);
            print($page_lot);
             die();
        }


         if ($lots_id['min'] >= $_POST['cost']) {
            $errors['cost'] = 'form__item--invalid';
            $dict['cost'] = 'Ставка должна быть больше минимальной цены';
            $page_lot = include_template ('lot.php',[
            'lots_id' => $lots_id,
            'category' => $category,
            'errors' => $errors,
            'dict' => $dict
             ]);
            print($page_lot);
             die();
        }
        $sql_rate = "INSERT INTO rate (date_create, price, rate_lots, rate_user)
                    VALUES (?, ?, ?, ?)";
        $result_rate = db_insert_data($link, $sql_rate, [date('Y.m.d H:i:s'), $_POST['cost'], $_GET['id'], $_SESSION['user']['id']]);
        check($result_rate);
        $lots_id['rate_user'] = $_SESSION['user']['id'];
        $count = $count + 1;
}

$page_lot = include_template ('lot.php',[
    'lots_id' => $lots_id,
    'category' => $category,
    'result' => $result,
    'count' => $count

]);
print($page_lot);

