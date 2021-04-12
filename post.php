<?php
date_default_timezone_set("Europe/Moscow");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$config = require 'config.php';

$db = new mysqli($config['db']['host'], $config['db']['username'], $config['db']['password'], $config['db']['dbname']);
$db->set_charset($config['db']['charset']);

require 'helpers.php';

$post_id = $_GET['post_id'] ?? 0;

$sql_select_post_by_id = '
    SELECT p.id, p.title, p.content, u.username, u.user_picture, ct.type_class, COUNT(l.post_id) AS like_count
      FROM posts AS p 
      JOIN users AS u ON p.user_id = u.id
      JOIN content_types AS ct ON p.content_type_id = ct.id 
 LEFT JOIN likes AS l ON p.id = l.post_id
     WHERE p.id = \'' . $post_id . '\'
';

$post = select_query_and_fetch_assoc($db, $sql_select_post_by_id);

if ($post['id'] === NULL || $post_id === 0) {
    header("HTTP/1.0 404 Not Found");
    die;
}

$is_auth = rand(0, 1);
$user_name = 'Валерий';
$title = 'Readme: Популярное';

$post_content = include_template('/post/post-' . $post['type_class'] . '.php', [
    'post' => $post,
]);
$main_content = include_template('post-details.php', [
    'post_content' => $post_content,
    'post' => $post,
]);
$layout_content = include_template('layout.php', [
    'content' => $main_content, 
    'is_auth' => $is_auth, 
    'user_name' => $user_name, 
    'title' => $title,
]);

print($layout_content);
