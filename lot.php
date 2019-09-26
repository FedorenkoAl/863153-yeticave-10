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

$page = '';
$layout_pages = '';
$count = 0;
$lots_id = [];
$result = [];
$dict = [];
$errors = [];
$result_rate = '';
$error = '';

if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
   $sql = 'SELECT id FROM rate
            WHERE rate_lots = ?';
    $count = count(db_fetch_data($link, $sql, [$_GET['id']]));
        if ($count == 0) {
            $sql = "SELECT l.name, l.image, l.price, l.step, c.name c, l.data_end, l.description, l.author FROM lots l
                LEFT JOIN category c
                ON l.lots_category = c.id
                WHERE l.id = ?";
            $lots_id = db_fetch_data_assos($link, $sql, [$_GET['id']]);
        }
        else {
            $sql = "SELECT l.name, l.image, l.step, c.name c, l.data_end, l.description, l.author, r.price max, r.rate_user FROM lots l
                LEFT JOIN category c
                ON l.lots_category = c.id
                LEFT JOIN rate r
                ON r.rate_lots = l.id
                WHERE l.id = ?
                ORDER BY r.date_create DESC";
            $lots_id = db_fetch_data_assos($link, $sql, [$_GET['id']]);
        }

    if (!$lots_id) {
        http_response_code(404);
        $page = include_template('404.php',[]);
        $layout_pages = include_template('layout-pages.php',[
            'page' => $page,
            'category' => $category,
            'title' => '404 Страница не найдена'
        ]);
        print($layout_pages);
        die();
    }

    if (!isset($lots_id['rate_user'])) {
        $lots_id['rate_user'] = 0;
    }

    if(!isset($lots_id['max'])) {
        $lots_id['max'] = $lots_id['price'];
    }

    $lots_id['min'] = $lots_id['max'] + $lots_id['step'];

    $sql = 'SELECT r.id, r.date_create, r.price, u.name FROM rate r
            LEFT JOIN user u
            ON u.id = r.rate_user
            WHERE r.rate_lots = ?
            ORDER BY r.date_create DESC';
            $result = db_fetch_data($link, $sql, [$_GET['id']]);
}
else {
     http_response_code(404);
        $page = include_template('404.php',[]);
        $layout_pages = include_template('layout-pages.php',[
            'page' => $page,
            'category' => $category,
            'title' => '404 Страница не найдена'
        ]);
        print($layout_pages);
        die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_SESSION['user'])) {
        http_response_code(403);
        die();
    }

    if ((strtotime($lots_id['data_end']) < time()) || ($lots_id['author'] == $_SESSION['user']['id']) || ($lots_id['rate_user'] == $_SESSION['user']['id'])) {
        http_response_code(403);
            die();
    }

    if (empty($_POST['cost'])) {
        $errors['cost'] = 'form__item--invalid';
        $dict['cost'] = 'Введите сумму';
    }
    else {
        if (!ctype_digit($_POST['cost'])) {
            $errors['cost'] = 'form__item--invalid';
            $dict['cost'] = 'Неккоректная сумма';
        }
        else {
            if ($lots_id['min'] > $_POST['cost']) {
                $errors['cost'] = 'form__item--invalid';
                $dict['cost'] = 'Ставка должна быть больше минимальной цены';
            }
            else {
                $sql = "INSERT INTO rate (date_create, price, rate_lots, rate_user)
                VALUES (?, ?, ?, ?)";
                $result_rate = db_insert_data($link, $sql, [date('Y.m.d H:i:s'), $_POST['cost'], $_GET['id'], $_SESSION['user']['id']]);
                    if (!$result_rate) {
                        $error = mysqli_error($link);
                        print('Произошла ошибка при выполнении запроса' . $error);
                        die();
                    }
                $count = $count + 1;
                $lots_id['rate_user'] = $_SESSION['user']['id'];
                $sql = 'SELECT r.id, r.date_create, r.price, u.name FROM rate r
                    LEFT JOIN user u
                    ON u.id = r.rate_user
                    WHERE r.rate_lots = ?
                    ORDER BY r.date_create DESC';
                $result = db_fetch_data($link, $sql, [$_GET['id']]);
            }
        }
    }

    $page = include_template ('lot.php',[
    'lots_id' => $lots_id,
    'result' => $result,
    'count' => $count,
    'errors' => $errors,
    'dict' => $dict
    ]);

    $layout_pages = include_template('layout-pages.php',[
        'page' => $page,
        'category' => $category,
        'title' => 'Лот'
    ]);
    print($layout_pages);
    die();
}
else {

    $page = include_template ('lot.php',[
        'lots_id' => $lots_id,
        'result' => $result,
        'count' => $count
    ]);

    $layout_pages = include_template('layout-pages.php',[
        'page' => $page,
        'category' => $category,
        'title' => 'Лот'
    ]);
}
print($layout_pages);

