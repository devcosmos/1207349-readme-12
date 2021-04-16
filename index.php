<?php
require 'common.php';

$filter_post_type_id = $_GET['type_id'] ?? 0;
$filter_post_type_id = intval($filter_post_type_id);

$sql_select_content_types = '
    SELECT * FROM content_types
';
$sql_select_popular_posts = '
    SELECT ct.type_name, ct.type_class, p.id, p.content, u.username, u.user_picture, l.like_count, c.comment_count
      FROM posts AS p 
      JOIN users AS u ON p.user_id = u.id 
      JOIN content_types AS ct ON p.content_type_id = ct.id 
 LEFT JOIN (
        SELECT post_id, COUNT(post_id) AS like_count 
          FROM likes
         GROUP BY post_id
    ) AS l ON p.id = l.post_id 
LEFT JOIN (
        SELECT post_id, COUNT(post_id) AS comment_count 
          FROM comments
         GROUP BY post_id
    ) AS c ON p.id = c.post_id 
';
if ($filter_post_type_id !== 0) {
    $sql_select_popular_posts .= 'WHERE ct.id = ?';
}
$sql_select_popular_posts .= '
    ORDER BY show_count DESC
';

if ($filter_post_type_id !== 0) {
    $popular_posts = select_query_with_stmt_and_fetch($db, $sql_select_popular_posts, 'i', [$filter_post_type_id]);
} else {
    $popular_posts = select_query_and_fetch($db, $sql_select_popular_posts);
}
$content_types = select_query_and_fetch($db, $sql_select_content_types);

$posts_with_date = [];
foreach ($popular_posts as $i => $post) {
    array_push($posts_with_date, array_merge($post, ['date' => generate_random_date($i)]));
}

$main_content = include_template('main.php', [
    'posts' => $posts_with_date,
    'content_types' => $content_types,
    'filter_post_type_id' => $filter_post_type_id,
]);
$layout_content = include_template('layout.php', [
    'content' => $main_content, 
    'is_auth' => $is_auth, 
    'user_name' => $user_name, 
    'title' => $title,
]);

print($layout_content);