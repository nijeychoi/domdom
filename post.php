<?php
    include "inc_post.php"
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="/css/post.css" rel="stylesheet">
</head>
<body>
    <div class="post-container">
        
        
    </div>
    <div theme="blog">
            <section class="maincontainer">
                <div class="container is-page">
                    <header class="navigation-header">
                        <section class="top-links">
                            <a href=index.php>Home</a><p>|</p>
                        </section>
                    </header>
                    <header class="notification-header">
                    </header>
                    <div class="blog-post">
                    <img src="<?= htmlspecialchars($image)?>">
                    <h1><?= htmlspecialchars($title) ?></h1>
                    <p><span id=blog-author>Mudo</span> | 06 July 2025</p>
                    <hr>
                    <p></p>
                    

                    <div>
                        <!-- highlight 영역에 URL 파라미터 값을 JS가 그대로 삽입 -->
                        <h2>특별 강조</h2>
                        <div id="highlight"></div>
                    </div>

                    <script>
                        // URL 파라미터에서 'highlight' 가져오기
                        const urlParams = new URLSearchParams(window.location.search);
                        const highlight = urlParams.get('highlight');

                        if (highlight) {
                            //여기서 필터링 없이 innerHTML로 바로 삽입 → DOM XSS 취약점 발생
                            document.getElementById('highlight').innerHTML = highlight;
                        }
                    </script>


                    <hr>
                    <h2>Comments</h2>
                    
                    <ul>
                    <?php
                    $stmt = $db_conn->prepare("SELECT name, email, body FROM comments WHERE post_id = ? ORDER BY id DESC");
                    $stmt->bind_param("i", $post['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()):
                    ?>
                        <li>
                            <strong><?= htmlspecialchars($row['name']) ?></strong>
                            (<?= htmlspecialchars($row['email']) ?>)<br>
                            <?= nl2br(htmlspecialchars($row['body'])) ?>
                        </li>
                    <?php endwhile; ?>
                    </ul>

                    <h3>Add Comments</h3>
                    <form method="POST" action="comment.php" onsubmit="return validateCommentForm()">
                        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
                        
                        <div class="form-group">
                            <label for="name">이름</label>
                            <input type="text" id="name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">이메일</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="body">댓글 내용</label>
                            <textarea id="body" name="body" required></textarea>
                        </div>
                        
                        <div class="buttons">
                            <button type="submit" class="btn btn-submit">등록하기</button>
                        </div>
                    </form>

                    <script>
                    function validateCommentForm() {
                        var name = document.getElementById('name').value.trim();
                        var email = document.getElementById('email').value.trim();
                        var body = document.getElementById('body').value.trim();

                        if (!name || !email || !body) {
                            alert('모든 필드를 입력해주세요.');
                            return false;
                        }
                        return true;
                    }
                    </script>

                    </section>
                    <div class="is-linkback">
                        <a href="index.php">Back to Blog</a>
                    </div>
                </div>
            </section>
            <div class="footer-wrapper">
            </div>
        </div>
</body>
</html>