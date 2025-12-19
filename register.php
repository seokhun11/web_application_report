<?php
/**
 * 회원가입 페이지
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
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // 유효성 검사
    if (empty($username) || empty($email) || empty($password)) {
        $error = '모든 필드를 입력해주세요.';
    } elseif (strlen($username) < 2 || strlen($username) > 50) {
        $error = '사용자명은 2~50자 사이여야 합니다.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = '올바른 이메일 주소를 입력해주세요.';
    } elseif (strlen($password) < 4) {
        $error = '비밀번호는 최소 4자 이상이어야 합니다.';
    } elseif ($password !== $password_confirm) {
        $error = '비밀번호가 일치하지 않습니다.';
    } else {
        try {
            $pdo = getDBConnection();

            // 중복 확인
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $error = '이미 사용 중인 사용자명 또는 이메일입니다.';
            } else {
                // 비밀번호 해시화 후 저장
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$username, $email, $hashed_password]);

                // 자동 로그인 처리
                $user_id = $pdo->lastInsertId();
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;

                // 메인 페이지로 이동
                header('Location: index.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = '회원가입 중 오류가 발생했습니다: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입 - <?php echo SITE_NAME; ?></title>
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
            <h1 class="page-title">📝 회원가입</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>



            <div class="card">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">사용자명 *</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="사용자명을 입력하세요"
                            required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">이메일 *</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="이메일을 입력하세요"
                            required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">비밀번호 *</label>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="비밀번호를 입력하세요" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirm">비밀번호 확인 *</label>
                        <input type="password" id="password_confirm" name="password_confirm" class="form-control"
                            placeholder="비밀번호를 다시 입력하세요" required>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">🚀 회원가입</button>
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