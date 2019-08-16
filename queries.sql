-- Добавляем в БД список категорий
INSERT INTO category (name)
   VALUES
       ('Доски и лыжи'),
       ('Крепления'),
       ('Ботинки'),
       ('Одежда'),
       ('Инструменты'),
       ('Разное');

-- -- Добавляем в БД список пользователей

INSERT INTO user (date_registration, email, name, password, avatar, contak)
VALUES
  ('2019-01-02 7:00:00', 'aleks@mail.ru', 'Aleks', 'hom1',
    'https://www.iconfinder.com/icons/
    2487762/avatar_beard_man_people_user_icon',
    'Тел: 2-12-85-06,Москва, ул.Профсоюзная 2'),
  ('2018-12-02 7:00:00','feodor@mail.ru','Feodor','hom2',
    'https://www.iconfinder.com/icons/
    2487762/avatar_beard_man_people_user_icon',
    'Тел: 2-12-85-06бМосква, ул.Профсоюзная 2');

  -- Добавляем в БД список объявлений

   INSERT INTO lots (creation_date, name,description, image, price,data_end,
   step, author, author_winner, lots_category)
 VALUES
   ('2019-01-02 7:00:00', '2014 Rossignol District Snowboard',
      'Легкий маневренный сноуборд, готовый дать жару в любом парке,
     растопив снег мощным щелчкоми четкими дугами.
     Стекловолокно Bi-Ax, уложенное в двух направлениях,
     наделяет этот снаряд отличной гибкостью и отзывчивостью,
     а симметричная геометрия в сочетании с классическим прогибом кэмбер
     позволит уверенно держать высокие скорости.
     А если к концу катального дня сил совсем не останется,
     просто посмотрите на Вашу доску и улыбнитесь,
     крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
      'img/lot-1.jpg', 10999, '2019-12-02 7:00:0', 30, 1, 2, 1),

   ('2019-01-03 7:00:00', '2014 Rossignol District Snowboard',
      'Легкий маневренный сноуборд,
     готовый дать жару в любом парке',
      'img/lot-2.jpg', 15999, '2019-03-02 7:00:0', 20, 1, 2, 1),

   ('2019-01-06 7:00:00', 'Крепления Union Contact Pro 2015 года размер L/XL',
     'Легкий маневренный сноуборд,
    готовый дать жару в любом парке',
    'img/lot-3.jpg', 8000, '2019-05-02 7:00:0', 25, 1, 2, 2),

   ('2019-03-06 7:00:00','Ботинки для сноуборда DC Mutiny Charocal',
      'Легкий маневренный сноуборд,
     готовый дать жару в любом парке', 'img/lot-4.jpg',
    10999, '2019-09-02 7:00:00', 10, 2, 2, 3),

   ('2019-01-23 9:00:00', 'Куртка для сноуборда DC Mutiny Charocal',
      'Легкий маневренный сноуборд,
     готовый дать жару в любом парке', 'img/lot-5.jpg',
   7500, '2019-10-23 1:00:00', 45, 1, 1, 4),

   ('2019-01-23 9:00:00',
     'Маска Oakley Canopy','Легкий маневренный сноуборд,
     готовый дать жару в любом парке', 'img/lot-6.jpg',
      5400, '2019-11-23 9:00:00', 23, 2, 1, 5);

   INSERT INTO rate (date_create, price,rate_user, rate_lots)
    VALUES
      ('2019-01-02 8:00:00', 13000, 1, 1),
      ('2019-01-02 9:00:00', 6500, 2, 6);


-- -- Получаем все категории
    SELECT * FROM category
    ORDER BY id ASC;

  --  Получаем самые новые, открытые лот.Каждый лот должен включать название,
  -- стартовую цену, ссылку на изображение,
  -- цену, название категории

  SELECT l.name, l.price, l.image, MAX(r.price) r, c.name c FROM lots l
  LEFT JOIN rate r
  ON r.rate_lots = l.id
  LEFT JOIN category c
  ON l.lots_category = c.id
  GROUP BY l.id ORDER BY l.data_end LIMIT 5;

 -- ПОЛУЧАЕМ лот по его id И также название категории, к которой принадлежит лот

  SELECT l.id, l.name, l.price, l.image, c.name c FROM lots l
  JOIN category c
  ON l.lots_category = c.id
  WHERE l.id = 4;


 -- обновить название лота по его идентификатору;

  UPDATE lots SET name = 'Куртка для сноуборда DC Mutiny Charocal'
  WHERE id = 2;

 -- список самых свежих ставок для лота по его идентификатору

  SELECT l.id, l.name, r.date_create, r.price, u.name us FROM lots l
  LEFT JOIN rate r
  ON r.rate_lots = l.id
  LEFT JOIN user u
  ON u.id = r.rate_user
  WHERE l.id = 6
  ORDER BY r.date_create DESC LIMIT 5;


