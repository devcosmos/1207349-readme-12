<?php
require 'common.php';

$post_id = $_GET['post_id'] ?? 0;
$post_id = intval($post_id);

$sql_select_post_by_id = '
    SELECT p.id, p.title, p.content, u.username, u.user_picture, ct.type_class,
    (SELECT count(*) FROM likes WHERE post_id = p.id) AS like_count, 
    (SELECT count(*) FROM comments WHERE post_id = p.id) AS comment_count
      FROM posts AS p 
      JOIN users AS u ON p.user_id = u.id
      JOIN content_types AS ct ON p.content_type_id = ct.id
     WHERE p.id = ?
';
$sql_select_comments_from_post = '
    SELECT u.username, u.user_picture, c.content, c.dt_add
      FROM comments AS c
      JOIN users AS u ON c.user_id = u.id
     WHERE c.post_id = ?
';

$post = select_query_with_stmt_and_fetch($db, $sql_select_post_by_id, 'i', [$post_id], false);
if ($post['id'] === NULL || $post_id === 0) {
    get_error_code(404);
}

$post_comments = select_query_with_stmt_and_fetch($db, $sql_select_comments_from_post, 'i', [$post_id]);

$title = $post['title'];

$post_content = include_template('post/post-' . $post['type_class'] . '.php', ['post' => $post]);
$post_comments_content = include_template('post/post-comments.php', ['post_comments' => $post_comments]);
$main_content = include_template('post-details.php', [
    'post_content' => $post_content,
    'post_comments_content' => $post_comments_content,
    'post' => $post,
]);
$layout_content = include_template('layout.php', [
    'content' => $main_content, 
    'is_auth' => $is_auth, 
    'user_name' => $user_name, 
    'title' => $title,
]);

print($layout_content);
