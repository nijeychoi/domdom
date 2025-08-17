<?php
// 댓글 조회
function get_comments($db_conn, $post_id) {
    $comments = [];
    $stmt = $db_conn->prepare("SELECT name, email, body FROM comments WHERE post_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->bind_result($name, $email, $body);
    while ($stmt->fetch()) {
        $comments[] = [
            'name' => $name,
            'email' => $email,
            'body' => $body
        ];
    }
    $stmt->close();
    return $comments;
}

// 댓글 등록
function add_comment($db_conn, $post_id, $name, $email, $body) {
    $stmt = $db_conn->prepare("INSERT INTO comments (post_id, name, email, body) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $post_id, $name, $email, $body);
    $stmt->execute();
    $stmt->close();
}
?>
