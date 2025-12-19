<?php
/**
 * 공통 헤더 컴포넌트
 * 모든 페이지에서 include하여 사용
 */
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? SITE_NAME; ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="container">
            <a href="index.php" class="logo"><?php echo SITE_NAME; ?></a>
            <nav>
                <ul>
                    <li><a href="index.php">홈</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="write.php">글 작성</a></li>
                        <li><span style="color: #000; font-weight: 600;">👤
                                <?php echo htmlspecialchars($_SESSION['username']); ?></span></li>
                        <li><a href="logout.php">로그아웃</a></li>
                    <?php else: ?>
                        <li><a href="login.php">로그인</a></li>
                        <li><a href="register.php">회원가입</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">