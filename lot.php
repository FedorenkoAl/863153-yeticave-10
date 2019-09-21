<?php
require_once ('helpers.php');

$link = mysqli_connect('localhost', 'root', '', 'YetiCave');
mysqli_set_charset($link, "utf8");
check($link);

if (!isset($_GET['id']) && !is_numeric($_GET['id']) && !$_GET['id'] > 0) {
    http_response_code(404);
    $content = include_template('404.php',
    ['error' => '404 Страница не найдена']);
    print($content);
    die();
}

$search = '';

if (isset($_GET['page'])) {
   $search = trim($_GET['search']);
}
else {
    $search = 'Поиск лота';
}

$sql = 'SELECT name FROM category';
$category = db_fetch_data($link, $sql, []);

$count_sql = 'SELECT  id FROM rate
        WHERE rate_lots = ?';
$count = count(db_fetch_data($link, $count_sql, [$_GET['id']]));
    if ($count == 0) {
        $sql_id = "SELECT l.name, l.image, l.price, l.step, c.name c, l.data_end, l.description, l.author FROM lots l
            LEFT JOIN category c
            ON l.lots_category = c.id
            WHERE l.id = ?";
        $lots_id = db_fetch_data_assos($link, $sql_id, [$_GET['id']]);
    }
    else {
        $sql_id = "SELECT l.name, l.image, l.step, c.name c, l.data_end, l.description, l.author, r.price max, r.rate_user FROM lots l
            LEFT JOIN category c
            ON l.lots_category = c.id
            LEFT JOIN rate r
            ON r.rate_lots = l.id
            WHERE l.id = ?
            ORDER BY r.date_create DESC";
        $lots_id = db_fetch_data_assos($link, $sql_id, [$_GET['id']]);
    }

if (!$lots_id) {
    http_response_code(404);
    $page = include_template('404.php',
    ['error' => '404 Страница не найдена']);
    $layout_pages = include_template('layout-pages.php',[
        'page' => $page,
        'category' => $category,
        'search' =>  $search,
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

$sql_rates = 'SELECT r.id, r.date_create, r.price, u.name FROM rate r
        LEFT JOIN user u
        ON u.id = r.rate_user
        WHERE r.rate_lots = ?
        ORDER BY r.date_create DESC';
        $result = db_fetch_data($link, $sql_rates, [$_GET['id']]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dict = [];
    $errors = [];

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
            if ($lots_id['min'] >= $_POST['cost']) {
                $errors['cost'] = 'form__item--invalid';
                $dict['cost'] = 'Ставка должна быть больше минимальной цены';
            }
            else {
                $sql_rate = "INSERT INTO rate (date_create, price, rate_lots, rate_user)
                VALUES (?, ?, ?, ?)";
                $result_rate = db_insert_data($link, $sql_rate, [date('Y.m.d H:i:s'), $_POST['cost'], $_GET['id'], $_SESSION['user']['id']]);
                    if (!$result_rate) {
                        $error = mysqli_error($link);
                        print('Произошла ошибка при выполнении запроса' . $error);
                        die();
                    }
                $count = $count + 1;
                $lots_id['rate_user'] = $_SESSION['user']['id'];
                $sql_rates = 'SELECT r.id, r.date_create, r.price, u.name FROM rate r
                    LEFT JOIN user u
                    ON u.id = r.rate_user
                    WHERE r.rate_lots = ?
                    ORDER BY r.date_create DESC';
                $result = db_fetch_data($link, $sql_rates, [$_GET['id']]);
            }
        }
    }

    $page = include_template ('lot.php',[
    'lots_id' => $lots_id,
    'category' => $category,
    'result' => $result,
    'count' => $count,
    'errors' => $errors,
    'dict' => $dict
    ]);

    $layout_pages = include_template('layout-pages.php',[
        'page' => $page,
        'category' => $category,
        'search' =>  $search,
        'title' => 'Лот'
    ]);
    print($layout_pages);
    die();
}
else {

    $page = include_template ('lot.php',[
        'lots_id' => $lots_id,
        'category' => $category,
        'result' => $result,
        'count' => $count
    ]);

    $layout_pages = include_template('layout-pages.php',[
        'page' => $page,
        'category' => $category,
        'search' =>  $search,
        'title' => 'Лот'
    ]);
}
print($layout_pages);

