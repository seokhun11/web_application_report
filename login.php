<?php
/**
 * 로그인 페이지
 */
require_once 'config.php';

$error = '';

// 이미 로그인된 상태면 메인으로
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// 폼 제출 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = '사용자명과 비밀번호를 입력해주세요.';
    } else {
        try {
            $pdo = getDBConnection();

            // 사용자 조회
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // 로그인 성공
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header('Location: index.php');
                exit;
            } else {
                $error = '사용자명 또는 비밀번호가 올바르지 않습니다.';
            }
        } catch (PDOException $e) {
            $error = '로그인 중 오류가 발생했습니다: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인 - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <header>
        <div class="container">
            <a href="index.php" class="logo"><?php echo SITE_NAME; ?></a>
            <nav>
                <ul>
                    <li><a href="index.php">홈</a></li>
                    <li><a href="login.php">로그인</a></li>
                    <li><a href="register.php">회원가입</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h1 class="page-title">🔐 로그인</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="card">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">사용자명</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="사용자명을 입력하세요"
                            required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">비밀번호</label>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="비밀번호를 입력하세요" required>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">🔓 로그인</button>
                        <a href="register.php" class="btn btn-secondary">회원가입하러 가기</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 <?php echo SITE_NAME; ?>. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>