<?php
// DB 연결
$mysqli = new mysqli("localhost", "root", "", "dom");
if ($mysqli->connect_errno) {
    echo "Failed to connect: " . $mysqli->connect_error;
    exit();
}

// postId 가져오기
$postId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 게시글 조회
$query = "SELECT title, image_url FROM post WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $postId);
$stmt->execute();
$stmt->bind_result($title, $image);
$stmt->fetch();
$stmt->close();
?>