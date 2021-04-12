<?php
date_default_timezone_set("Europe/Moscow");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$config = require 'config.php';

$db = new mysqli($config['db']['host'], $config['db']['username'], $config['db']['password'], $config['db']['dbname']);
$db->set_charset($config['db']['charset']);

require 'helpers.php';

$filter_post_type_id = $_GET['type_id'] ?? 0;

$sql_select_content_types = '
    SELECT * FROM content_types
';
$sql_select_popular_posts = '
    SELECT ct.type_name, ct.type_class, p.id, p.content, u.username, u.user_picture, COUNT(l.post_id) AS like_count
      FROM posts AS p 
      JOIN users AS u ON p.user_id = u.id 
      JOIN content_types AS ct ON p.content_type_id = ct.id 
 LEFT JOIN likes AS l ON p.id = l.post_id 
';
$sql_select_popular_posts .= $filter_post_type_id === 0 ? '' : 'WHERE ct.id = \'' . $filter_post_type_id . '\'';
$sql_select_popular_posts .= '
    GROUP BY p.id
    ORDER BY show_count DESC
';

$content_types = select_query_and_fetch_all($db, $sql_select_content_types);
$popular_posts = select_query_and_fetch_all($db, $sql_select_popular_posts);

$is_auth = rand(0, 1);
$user_name = 'Валерий';
$title = 'Readme: Популярное';
$icons_size = [
    'text' => [ 'width' => '20', 'height' => '21'],
    'quote' => [ 'width' => '21', 'height' => '20'],
    'photo' => [ 'width' => '22', 'height' => '18'],
    'video' => [ 'width' => '24', 'height' => '16'],
    'link' => [ 'width' => '21', 'height' => '18'],
];

$posts_with_date = [];
foreach ($popular_posts as $i => $post) {
    array_push($posts_with_date, array_merge($post, ['date' => generate_random_date($i)]));
}

$main_content = include_template('main.php', [
    'posts' => $posts_with_date,
    'content_types' => $content_types,
    'icons_size' => $icons_size,
    'filter_post_type_id' => $filter_post_type_id,
]);
$layout_content = include_template('layout.php', [
    'content' => $main_content, 
    'is_auth' => $is_auth, 
    'user_name' => $user_name, 
    'title' => $title,
]);

print($layout_content);