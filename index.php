<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db_conn = new mysqli("localhost", "root", "", "blog");
$db_conn->set_charset("utf8mb4");

include 'inc_post.php';

$posts = get_all_posts($db_conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>게시글 목록</title>
    <link rel="stylesheet" href="css/blog.css">
</head>
<body>
<h1>Blog</h1>
<ul>
    <?php foreach ($posts as $post): ?>
        <li>
            <a href="post.php?id=<?= $post['id'] ?>">
                <?= htmlspecialchars($post['title']) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>
