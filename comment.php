<?php
include "inc_post.php"; // $db_conn 연결 포함, session_start() 포함 가정

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $body  = trim($_POST['body'] ?? '');
    $post_id = intval($_POST['post_id'] ?? 0);

    // 필수 입력값 확인
    if (empty($name) || empty($email) || empty($body) || $post_id <= 0) {
        echo "<script>alert('모든 필드를 입력해주세요.'); history.back();</script>";
        exit();
    }

    // 댓글 저장 (Prepared Statement)
    $sql = "INSERT INTO comments (post_id, name, email, body) VALUES (?, ?, ?, ?)";
    $stmt = $db_conn->prepare($sql);
    $stmt->bind_param("isss", $post_id, $name, $email, $body);

    if ($stmt->execute()) {
        echo "<script>alert('댓글이 등록되었습니다.'); location.href='post.php?id={$post_id}';</script>";
    } else {
        echo "<script>alert('오류가 발생했습니다.');</script>";
        error_log($stmt->error);
    }

    $stmt->close();
}
?>
