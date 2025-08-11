<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // DB 연결
    $db_conn = new mysqli("localhost", "root", "", "blog");
    $db_conn->set_charset("utf8mb4");

    // post_id 가져오기
    $post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // 게시글 조회
    $query = "SELECT title, img_url FROM posts WHERE id = ?";
    $stmt = $db_conn->prepare($query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->bind_result($title, $imageUrl);

    if ($stmt->fetch()) {
        $post = [
            'id' => $post_id,
            'title' => $title,
            'img_url' => $imageUrl
        ];
    } else {
        $post = [
            'id' => 0,
            'title' => "게시글을 찾을 수 없습니다.",
            'img_url' => "/images/no-image.png"
        ];
    }

    $stmt->close();
    // 여기서 close() 안 함 → 다른 코드에서도 $db_conn 사용 가능

} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    echo "데이터베이스 오류가 발생했습니다.";
    exit;
}
?>
