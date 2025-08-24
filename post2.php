<?php
session_start(); // 세션 시작

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db_conn = new mysqli("localhost", "root", "", "blog");
$db_conn->set_charset("utf8mb4");

include 'inc_post.php';
include 'inc_comment.php';

// GET으로 게시글 id 가져오기
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($post_id <= 0) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 댓글 등록 (POST 처리)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // [1] XSS 트리거만 처리하는 AJAX 요청
    if (isset($_POST['triggered']) && $_POST['triggered'] === '1') {
        $_SESSION['show_flag'] = true;
        header('Content-Type: text/plain; charset=UTF-8');
        exit('OK');
    }

    // [2] 실제 댓글 등록
    $name  = isset($_POST['name'])  ? trim($_POST['name'])  : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $body  = isset($_POST['body'])  ? trim($_POST['body'])  : '';

    if ($name === "" || $email === "" || $body === "") {
        echo "<script>alert('모든 필드를 입력해주세요.'); history.back();</script>";
        exit;
    }

    add_comment($db_conn, $post_id, $name, $email, $body);

    // 댓글 등록 후 리다이렉트 (URL에 flag 노출 없음)
    header("Location: post.php?id=" . $post_id);
    exit;
}

// 게시글 조회
$post = get_post($db_conn, $post_id);
if (!$post) {
    echo "<script>alert('게시글을 찾을 수 없습니다.'); history.back();</script>";
    exit;
}

// 댓글 조회
$comments = get_comments($db_conn, $post_id);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link rel="stylesheet" href="css/post.css">
    <script src="https://cdn.jsdelivr.net/npm/dompurify@2.4.0/dist/purify.min.js"></script>
    <script>
        function previewComment() {
            const body = document.getElementById('body').value;

            // DOMPurify 필터링
            const cleanBody = DOMPurify.sanitize(body, {
                ALLOWED_TAGS:  ['b','i','em','strong','a','p','ul','ol','li','br','span','img','svg','image'],
                ALLOWED_ATTR:  ['href','title','src','alt','class','style','xlink:href','xmlns','width','height'],
                FORBID_TAGS:   ['script','iframe','object','embed'],
                FORBID_ATTR:   ['onerror','onload','onclick','onfocus']
            });


            const previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = cleanBody;

            // 이미지 에러 발생 시 notifyFlag 호출
            previewContainer.querySelectorAll('img').forEach(img => {
                img.addEventListener('error', () => {
                    notifyFlag();
                }, { once: true });
            });

            // SVG 태그 내 onload 실행 시 notifyFlag() 호출 가능
        }

        // 서버에 플래그 세션 저장 요청
        function notifyFlag() {
            fetch('post.php?id=<?= $post_id ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'triggered=1'
            }).then(() => {
                location.href = 'post.php?id=<?= $post_id ?>#flag';
            });
        }
    </script>
</head>
<body>
<div class="container">
    <h1><?= htmlspecialchars($post['title']) ?></h1>
    <img src="<?= htmlspecialchars($post['img_url']) ?>" alt="이미지" style="max-width:400px;">

    <h3>댓글</h3>
    <ul>
        <?php foreach ($comments as $c): ?>
            <li>
                <strong><?= htmlspecialchars($c['name']) ?></strong> (<?= htmlspecialchars($c['email']) ?>):<br>
                <?= nl2br(htmlspecialchars($c['body'])) ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>댓글 작성</h3>
    <form method="POST" action="post.php?id=<?= $post_id ?>">
        <div class="form-group">
            <label for="name">이름</label>
            <input type="text" id="name" name="name" required><br>
        </div>

        <div class="form-group">
            <label for="email">이메일</label>
            <input type="email" id="email" name="email" required><br>
        </div>

        <div class="form-group">
            <label for="body">댓글</label>
            <textarea name="body" id="body" required></textarea><br>
        </div>
        <button type="submit">댓글 작성</button>
        <button type="button" onclick="previewComment()">미리보기</button>
    </form>

    <h3>미리보기</h3>
    <div id="preview-container"></div>

    <div class="is-linkback">
        <a href="index.php">Back to Blog</a>
    </div>

    <?php if (isset($_SESSION['show_flag']) && $_SESSION['show_flag'] === true): ?>
        <div id="flag-container">
            FLAG{DOM_XSS}
        </div>
        <?php unset($_SESSION['show_flag']); ?>
    <?php endif; ?>
</div>
</body>
</html>
