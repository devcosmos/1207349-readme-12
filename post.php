<?php
require 'common.php';

$post_id = $_GET['post_id'] ?? 0;
$post_id = intval($post_id);

$sql_select_post_by_id = '
    SELECT p.id, p.title, p.content, u.username, u.user_picture, ct.type_class, l.like_count, c.comment_count
      FROM posts AS p 
      JOIN users AS u ON p.user_id = u.id
      JOIN content_types AS ct ON p.content_type_id = ct.id
 LEFT JOIN ( 
        SELECT post_id, COUNT(post_id) AS like_count
          FROM likes
         WHERE post_id = ? 
         GROUP BY post_id
    ) AS l ON p.id = l.post_id 
 LEFT JOIN ( 
        SELECT post_id, COUNT(post_id) AS comment_count
          FROM comments
         WHERE post_id = ?  
         GROUP BY post_id
    ) AS c ON p.id = c.post_id 
     WHERE p.id = ?
';
$sql_select_comments_from_post = '
    SELECT u.username, u.user_picture, c.content, c.dt_add
      FROM comments AS c
      JOIN users AS u ON c.user_id = u.id
    WHERE c.post_id = ?
';

$post = select_query_with_stmt_and_fetch($db, $sql_select_post_by_id, 'iii', [$post_id, $post_id, $post_id], false);
if ($post['id'] === NULL || $post_id === 0) {
    get_error_code(404);
}

$post_comments = select_query_with_stmt_and_fetch($db, $sql_select_comments_from_post, 'i', [$post_id]);

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
