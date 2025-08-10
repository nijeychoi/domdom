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
                            <a href=/>Home</a><p>|</p>
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
                    <div/>
                    <hr>
                    <h1>Comments</h1>
                    <span id='user-comments'>
                    <script src='/resources/js/domPurify-2.0.15.js'></script>
                    <script src='/resources/js/loadCommentsWithDomClobbering.js'></script>
                    <script>loadComments('/post/comment')</script>
                    </span>
                    <hr>
                    <section class="add-comment">
                        <h2>Leave a comment</h2>
                        <?php if (!empty($error)): ?>
                        <p style="color:red;"><?=htmlspecialchars($error)?></p>
                    <?php endif; ?>

                    <form method="post" action="post.php?id=<?= $post_id ?>">
                        <label>이름: <input type="text" name="author" required></label><br>
                        <label>댓글: <br><textarea name="comment" rows="4" cols="50" required></textarea></label><br>
                        <button type="submit">댓글 작성</button>
                    </form>

                    <ul>
                        <?php foreach ($comments as $c): ?>
                            <li>
                                <strong><?=htmlspecialchars($c['author'])?></strong>
                                <em>(<?= $c['created_at'] ?>)</em><br>
                                <?=nl2br(htmlspecialchars($c['comment']))?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    </section>
                    <div class="is-linkback">
                        <a href="/">Back to Blog</a>
                    </div>
                </div>
            </section>
            <div class="footer-wrapper">
            </div>
        </div>
</body>
</html>