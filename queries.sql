INSERT INTO content_types (title, icon_class) 
VALUES ('Текст', 'post-text'),
       ('Цитата', 'post-quote'),
       ('Картинка', 'post-photo'),
       ('Видео', 'post-video'),
       ('Ссылка', 'post-link'); 

INSERT INTO users (email, password, username, avatar_path) 
VALUES ('larisa@readme.ru', 'qwertyL', 'Лариса', 'userpic-larisa-small.jpg'),
       ('vadik@readme.ru', 'qwertyV', 'Вадик', 'userpic.jpg'),
       ('viktor@readme.ru', 'qwertyV', 'Виктор', 'userpic-mark.jpg');

INSERT INTO comments 
   SET user_id = 1, post_id = 2, content = 'Текст комментария для второго поста';
INSERT INTO comments 
   SET user_id = 2, post_id = 1, content = 'Текст комментария для первого поста';

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

