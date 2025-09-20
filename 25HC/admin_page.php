<?php
    if (!isset($_GET['auth']) || $_GET['auth'] !== 'success') {
        die("<h1>[접근 거부]</h1><p>정상적인 경로로 접근하지 않았습니다.</p>");
    }
    
    // (추가) 페이지 내용과 별개로, 응답 헤더에 플래그를 담아 보냅니다.
    header("X-Flag: FLAG{emeldjRmxdlek}");
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <title>비밀 관리자 패널</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Access Granted</h1>
        <p>관리자 패널에 성공적으로 접근했습니다. 하지만 플래그는 어디에 있을까요?</p>
        </div>
</body>
</html>