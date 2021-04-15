<?php
require 'common.php';

$post_id = $_GET['post_id'] ?? 0;
$post_id = intval($post_id);

$sql_select_post_by_id = '
    SELECT p.id, p.title, p.content, u.username, u.user_picture, ct.type_class, COUNT(l.post_id) AS like_count
      FROM posts AS p 
      JOIN users AS u ON p.user_id = u.id
      JOIN content_types AS ct ON p.content_type_id = ct.id 
 LEFT JOIN likes AS l ON p.id = l.post_id
     WHERE p.id = ? 
     GROUP BY p.id';

$post = select_query_with_stmt_and_fetch($db, $sql_select_post_by_id, 'i', [$post_id], false);

if ($post['id'] === NULL || $post_id === 0) {
    get_error_code(404);
}

$post_content = include_template('post/post-' . $post['type_class'] . '.php', [
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
