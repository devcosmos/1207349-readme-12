CREATE DATABASE readme
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE readme;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(128) NOT NULL UNIQUE,
  login VARCHAR(128) NOT NULL UNIQUE,
  password CHAR(64) NOT NULL,
  avatar_path VARCHAR(255)
);

CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  title VARCHAR(255),
  content TEXT,
  quote_author VARCHAR(255),
  img_path VARCHAR(255),
  video_path VARCHAR(255),
  link_path VARCHAR(255),
  show_count INT,
  user_id INT,
  content_type_id INT,
  hashtag_id INT
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
  title VARCHAR(255)
);

CREATE TABLE content_types (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  icon_class VARCHAR(255)
);
