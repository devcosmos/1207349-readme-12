<?php
require 'common.php';

$post_id = $_GET['post_id'] ?? 0;
$post_id = intval($post_id);

$sql_select_post_by_id = '
    SELECT p.id, p.title, p.content, u.username, u.user_picture, ct.type_class
      FROM posts AS p 
      JOIN users AS u ON p.user_id = u.id
      JOIN content_types AS ct ON p.content_type_id = ct.id
     WHERE p.id = ?';
$sql_select_like_count = '
    SELECT COUNT(post_id) AS count
      FROM likes
     WHERE post_id = ? 
     GROUP BY post_id';
$sql_select_comment_count = '
    SELECT COUNT(post_id) AS count
      FROM comments
     WHERE post_id = ? 
     GROUP BY post_id';

$post = select_query_with_stmt_and_fetch($db, $sql_select_post_by_id, 'i', [$post_id], false);
$like_count = select_query_with_stmt_and_fetch($db, $sql_select_like_count, 'i', [$post_id], false);
$comment_count = select_query_with_stmt_and_fetch($db, $sql_select_comment_count, 'i', [$post_id], false);

if ($post['id'] === NULL || $post_id === 0) {
    get_error_code(404);
}

$post_content = include_template('post/post-' . $post['type_class'] . '.php', [
    'post' => $post,
]);
$main_content = include_template('post-details.php', [
    'post_content' => $post_content,
    'post' => $post,
    'like_count' => $like_count['count'] ?? 0,
    'comment_count' => $comment_count['count'] ?? 0,
]);
$layout_content = include_template('layout.php', [
    'content' => $main_content, 
    'is_auth' => $is_auth, 
    'user_name' => $user_name, 
    'title' => $title,
]);

print($layout_content);
