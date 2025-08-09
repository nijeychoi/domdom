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
                    <p><span id=blog-author>Kel Surpreeze</span> | 06 July 2025</p>
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
                        <form action="/post/comment" method="POST" enctype="application/x-www-form-urlencoded">
                            <input required type="hidden" name="csrf" value="Z2tgulamLaT0nca9sPYbm1ldYVXNhJ0n">
                            <input required type="hidden" name="postId" value="1">
                            <label>Comment:</label>
                            <div>HTML is allowed</div>
                            <textarea required rows="12" cols="300" name="comment"></textarea>
                                    <label>Name:</label>
                                    <input required type="text" name="name">
                                    <label>Email:</label>
                                    <input required type="email" name="email">
                                    <label>Website:</label>
                                    <input pattern="(http:|https:).+" type="text" name="website">
                            <button class="button" type="submit">Post Comment</button>
                        </form>
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