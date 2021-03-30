CREATE DATABASE readme
  DEFAULT CHARACTER SET utf8mb4;

USE readme;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(128) NOT NULL UNIQUE,
  username VARCHAR(128) NOT NULL,
  password VARCHAR(64) NOT NULL,
  avatar_path VARCHAR(255)
);

CREATE TABLE content_types (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL UNIQUE,
  icon_class VARCHAR(255) NOT NULL UNIQUE
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
  show_count INT,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (content_type_id) REFERENCES content_types(id)
);

CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  content TEXT,
  user_id INT,
  post_id INT,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (post_id) REFERENCES posts(id)
);

CREATE TABLE likes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  post_id INT,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (post_id) REFERENCES posts(id)
);

CREATE TABLE subscribers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  subscriber_id INT,
  user_id INT,
  FOREIGN KEY (subscriber_id) REFERENCES users(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  content TEXT,
  sender_id INT,
  recipient_id INT,
  FOREIGN KEY (sender_id) REFERENCES users(id),
  FOREIGN KEY (recipient_id) REFERENCES users(id)
);

CREATE TABLE hashtags (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE post_tags (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT,
  hashtag_id INT,
  FOREIGN KEY (post_id) REFERENCES posts(id),
  FOREIGN KEY (hashtag_id) REFERENCES hashtags(id)
);

CREATE INDEX content_type ON content_types(title);
CREATE INDEX hashtag ON hashtags(title);