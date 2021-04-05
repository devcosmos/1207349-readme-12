-- список типов контента для поста
INSERT INTO content_types (type_name, type_class) 
VALUES ('Текст', 'text'),
       ('Цитата', 'quote'),
       ('Картинка', 'photo'),
       ('Видео', 'video'),
       ('Ссылка', 'link'); 

-- придумайте пару пользователей
INSERT INTO users (email, password, username, user_picture) 
VALUES ('larisa@readme.ru', 'qwertyL', 'Лариса', 'userpic-larisa-small.jpg'),
       ('vadik@readme.ru', 'qwertyV', 'Вадик', 'userpic.jpg'),
       ('viktor@readme.ru', 'qwertyV', 'Виктор', 'userpic-mark.jpg');

-- придумайте пару комментариев к разным постам
INSERT INTO comments 
   SET user_id = 1, post_id = 2, content = 'Текст комментария для второго поста';
INSERT INTO comments 
   SET user_id = 2, post_id = 1, content = 'Текст комментария для первого поста';

-- существующий список постов
INSERT INTO posts 
   SET user_id = 1, 
       content_type_id = 2, 
       title = 'Цитата',
       content = 'Мы в жизни любим только раз, а после ищем лишь похожих',
       show_count = 10;

INSERT INTO posts 
   SET user_id = 2, 
       content_type_id = 1, 
       title = 'Игра престолов',
       content = 'Не могу дождаться начала финального сезона своего любимого сериала!',
       show_count = 8;

INSERT INTO posts 
   SET user_id = 3, 
       content_type_id = 3, 
       title = 'Наконец, обработал фотки!',
       content = 'rock-medium.jpg',
       show_count = 15;

INSERT INTO posts 
   SET user_id = 1, 
       content_type_id = 3, 
       title = 'Моя мечта',
       content = 'coast-medium.jpg',
       show_count = 9;

INSERT INTO posts 
   SET user_id = 2, 
       content_type_id = 5, 
       title = 'Лучшие курсы',
       content = 'www.htmlacademy.ru',
       show_count = 100;

-- получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента
SELECT p.content, u.username, ct.type_name
  FROM posts AS p
  JOIN users AS u 
    ON p.user_id = u.id
  JOIN content_types AS ct 
    ON p.content_type_id = ct.id
 ORDER BY show_count DESC;

-- получить список постов для конкретного пользователя
SELECT * 
  FROM posts 
 WHERE user_id = 2;

-- получить список комментариев для одного поста, в комментариях должен быть логин пользователя
SELECT c.content, u.username
  FROM comments AS c
  JOIN users AS u
    ON c.user_id = u.id
 WHERE c.post_id = 2;

-- добавить лайк к посту
INSERT INTO likes
   SET user_id = 1, post_id = 1;

-- подписаться на пользователя
INSERT INTO subscribers
   SET subscriber_id = 1, user_id = 1;
