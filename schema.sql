CREATE DATABASE yeticave
DEFAULT CHARACTER SET UTF8
DEFAULT COLLATE UTF8_GENERAL_CI;

USE yeticave;

CREATE TABLE category (
id INT AUTO_INCREMENT PRIMARY KEY,
category_name CHAR(100) NOT NULL
)

CREATE TABLE lot (
id INT AUTO_INCREMENT PRIMARY KEY,
date_create DATETIME,
name_lot CHAR(100),
description TEXT,
image CHAR(100),
start_price INT,
date_end DATETIME,
step_price INT,
author CHAR(100),
winner CHAR(100),
cat_lot CHAR(100)
)

CREATE TABLE price (
id INT AUTO_INCREMENT PRIMARY KEY,
date_price INT,
user_price INT,
user_name CHAR(100),
name_lot CHAR(100)
)

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
date_registr DATETIME,
email CHAR(200),
user_name CHAR(200),
user_pass CHAR(100),
user_avatar CHAR,
user_contact INT
)

CREATE UNIQUE INDEX cat ON category(category_name);
CREATE INDEX l_name ON lot(name_lot);
CREATE UNIQUE INDEX u_email ON users(email);
CREATE UNIQUE INDEX u_name ON users(user_name);
CREATE UNIQUE INDEX u_contact ON users(user_contact);