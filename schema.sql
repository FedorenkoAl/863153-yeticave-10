CREATE DATABASE YetiCave;
  USE YetiCave;
CREATE TABLE category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(64),
  symbol VARCHAR(64)
);

CREATE TABLE lots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  creation_date DATETIME,
  name VARCHAR(64),
  description TEXT,
  image VARCHAR(64),
  price INT NOT NULL,
  data_end DATETIME,
  step INT NOT NULL,
  author INT,
  author_winner  INT,
  lots_category INT
);

CREATE TABLE rate (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_create DATETIME,
  price INT,
  rate_user INT,
  rate_lots INT
);

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_registration DATETIME,
  email VARCHAR(128) NOT NULL UNIQUE,
  name VARCHAR(64),
  password VARCHAR(128) NOT NULL UNIQUE,
  avatar VARCHAR(128),
  contak TINYTEXT
);
