<?php

require_once ('helpers.php');
$is_auth = rand(0, 1);

$user_name = 'Алексей'; // укажите здесь ваше имя

$category = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];
$lots = [
    ["picture" => "img/lot-1.jpg",
    "category" => "$category[0]",
    "title" => "2014 Rossignol District Snowboard",
    "prais" => "10999"
    ],
    [
    "picture" => "img/lot-2.jpg",
    "category" => "$category[0]",
    "title" => "2014 Rossignol District Snowboard",
    "prais" => "159999"
    ],
    [
    "picture" => "img/lot-3.jpg",
    "category" => "$category[1]",
    "title" => "Крепления Union Contact Pro 2015 года размер L/XL",
    "prais" => "8000"
    ],
    [
    "picture" => "img/lot-4.jpg",
    "category" => "$category[2]",
    "title" => "Ботинки для сноуборда DC Mutiny Charocal",
    "prais" => "10999"
    ],
    [
    "picture" => "img/lot-5.jpg",
    "category" => "$category[3]",
    "title" => "Куртка для сноуборда DC Mutiny Charocal",
    "prais" => "7500"
    ],
    [
    "picture" => "img/lot-6.jpg",
    "category" => "$category[4]",
    "title" => "Маска Oakley Canopy",
    "prais" => "5400"
    ]
];



$page_content = include_template('main.php', [
    'lots' => $lots,
    'category' => $category
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'category' => $category,
    'title' => 'Главная страница',
    'is_auth' => $is_auth
]);

print($layout_content);


?>
