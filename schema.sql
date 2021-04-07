CREATE DATABASE readme
  DEFAULT CHARACTER SET utf8mb4;

USE readme;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(128) NOT NULL UNIQUE,
  username VARCHAR(128) NOT NULL,
  password VARCHAR(64) NOT NULL,
  user_picture VARCHAR(255)
);

CREATE TABLE content_types (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type_name VARCHAR(255) NOT NULL UNIQUE,
  type_class VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_id INT,
  content_type_id INT,
  title VARCHAR(255),
  content TEXT,
  quote_author VARCHAR(255),
  img_path VARCHAR(255),
  video_path VARCHAR(255),
  link_path VARCHAR(255),
  show_count INT
);

CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  content TEXT,
  user_id INT,
  post_id INT
);

CREATE TABLE likes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  post_id INT
);

CREATE TABLE subscribers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  subscriber_id INT,
  user_id INT
);

CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  content TEXT,
  sender_id INT,
  recipient_id INT
);

CREATE TABLE hashtags (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE post_tags (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT,
  hashtag_id INT
);

CREATE INDEX p_user_id ON posts (user_id); -- для ускорения поиска по пользователям в таблице с постами
CREATE INDEX c_post_id ON comments (post_id); -- для ускорения поиска по постам в таблице с комментариями
