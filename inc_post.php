<?php
// 게시글 조회 기능
function get_post($db_conn, $post_id) {
    $stmt = $db_conn->prepare("SELECT id, title, img_url FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->bind_result($id, $title, $img_url);

    if ($stmt->fetch()) {
        $post = [
            'id' => $id,
            'title' => $title,
            'img_url' => $img_url
        ];
    } else {
        $post = null;
    }
    $stmt->close();
    return $post;
}

function get_all_posts($db_conn) {
    $posts = [];
    $result = $db_conn->query("SELECT id, title, img_url FROM posts ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    return $posts;
}
?>
