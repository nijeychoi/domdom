<?php
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
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $body  = trim($_POST['body']);

    if ($name === "" || $email === "" || $body === "") {
        echo "<script>alert('모든 필드를 입력해주세요.'); history.back();</script>";
        exit;
    }

    add_comment($db_conn, $post_id, $name, $email, $body);

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
</head>
<body>
<div class="container">
    <h1><?= htmlspecialchars($post['title']) ?></h1>
    <img src="<?= htmlspecialchars($post['img_url']) ?>" alt="이미지">

    <h2>댓글</h2>
    <ul>
        <?php foreach ($comments as $c): ?>
            <li>
                <strong><?= htmlspecialchars($c['name']) ?></strong> (<?= htmlspecialchars($c['email']) ?>):<br>
                <?= nl2br(htmlspecialchars($c['body'])) ?>
            </li>
        <?php endforeach; ?>
    </ul>


    <h3>작성한 댓글 미리보기</h3>

    <div class="preview-wrap">
    <div id="preview" class="preview-box">(미리보기 영역)</div>
    <button type="button" id="previewBtn">미리보기</button>
    </div>


    <h2>댓글 작성</h2>
    <form method="POST" action="post.php?id=<?= $post_id ?>">
        <div class="form-group">   
            <label for="name">이름</label>
            <input type="text" id='name' name="name" required>
        </div>
         
        <div class="form-group">
            <label for="email">이메일</label>
            <input type="email" id='email' name="email" required>
        </div>

        <div class="form-group">
            <label for="body">댓글</label>
            <textarea name="body" id='body' placeholder="HTML is allowed" required></textarea>
        </div>

        <button type="submit">Post Comment</button>
    </form>

    <div class="is-linkback">
        <a href="index.php">Back to Blog</a>
    </div>


    <script>
    // 취약점 textarea 값을 innerHTML로 그대로 주입하는 DOM XSS
    (function livePreview(){
    var btn = document.getElementById("previewBtn");
    var box = document.getElementById("preview");
    var ta  = document.getElementById("body"); // 댓글 본문 textarea

    if (btn && box && ta) {
        btn.addEventListener("click", function () {
        box.innerHTML = ta.value; // DOM XSS sink
        });
    }
    })();
    </script>



</div>

</body>

</html>
