<?php
// DB 에러 보고 활성화 (예외 던지기)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // DB 연결
    $mysqli = new mysqli("localhost", "root", "", "blog");
    $mysqli->set_charset("utf8mb4");

    // postId 가져오기 (없으면 0)
    $postId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // 게시글 조회
    $query = "SELECT title, img_url FROM posts WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $stmt->bind_result($title, $imageUrl);

    if ($stmt->fetch()) {
        // 게시글 데이터 있음 → 여기서 HTML 렌더링
    } else {
        // 게시글 없을 때 처리
        $title = "게시글을 찾을 수 없습니다.";
        $imageUrl = "/images/no-image.png";
    }

    $stmt->close();
    $mysqli->close();

} catch (mysqli_sql_exception $e) {
    // 내부 에러는 로그로 남기고, 사용자에겐 일반 메시지 표시
    error_log($e->getMessage());
    echo "데이터베이스 오류가 발생했습니다.";
    exit;
}


$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($post_id <= 0) {
    die("올바른 게시글 ID가 필요합니다.");
}

// 댓글 저장 처리 (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author = trim($_POST['author'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    if ($author && $comment) {
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, author, comment) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $author, $comment]);

        // 댓글 저장 후 리다이렉트(새로고침 시 중복 저장 방지)
        header("Location: post.php?id=$post_id");
        exit;
    } else {
        $error = "이름과 댓글을 모두 입력해주세요.";
    }
}





?>
