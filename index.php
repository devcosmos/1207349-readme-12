<?php
require 'helpers.php';

$is_auth = rand(0, 1);
$user_name = 'Валерий';
$title = 'Readme: Популярное';
$posts = [
    [
        'title' => 'Цитата',
        'type' => 'post-quote',
        'content' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
        'user_name' => 'Лариса',
        'user_picture' => 'userpic-larisa-small.jpg',
    ],
    [
        'title' => 'Игра престолов',
        'type' => 'post-text',
        'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!',
        'user_name' => 'Владик',
        'user_picture' => 'userpic.jpg',
    ],
    [
        'title' => 'Наконец, обработал фотки!',
        'type' => 'post-photo',
        'content' => 'rock-medium.jpg',
        'user_name' => 'Виктор',
        'user_picture' => 'userpic-mark.jpg',
    ],
    [
        'title' => 'Моя мечта',
        'type' => 'post-photo',
        'content' => 'coast-medium.jpg',
        'user_name' => 'Лариса',
        'user_picture' => 'userpic-larisa-small.jpg',
    ],
    [
        'title' => 'Лучшие курсы',
        'type' => 'post-link',
        'content' => 'www.htmlacademy.ru',
        'user_name' => 'Владик',
        'user_picture' => 'userpic.jpg',
    ],
];

function reduce_text($text, $max_symbols = 300) {
    if (mb_strlen($text) <= $max_symbols) {
		return $text;
	}

    $words = explode(' ', $text);
    $symbol_counter = 0;
    $word_counter = 0;

    foreach($words as $word) {
        $symbol_counter += mb_strlen($word);
		if ($symbol_counter > $max_symbols) {
			break;
		}

		$symbol_counter++;
        $word_counter++;
    }

    $text = implode(' ', array_slice($words, 0, $word_counter));

    return $text . '...';
}

$main_content = include_template('main.php', ['posts' => $posts]);
$layout_content = include_template('layout.php', [
    'content' => $main_content, 
    'is_auth' => $is_auth, 
    'user_name' => $user_name, 
    'title' => $title,
]);

print($layout_content);