<?php
/**
 * 게시글 작성 페이지
 */
require_once 'config.php';

$error = '';
$success = '';

// 폼 제출 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $code_content = trim($_POST['code_content'] ?? '');
    $programming_language = trim($_POST['programming_language'] ?? 'plaintext');

    // 유효성 검사
    if (empty($title) || empty($content)) {
        $error = '제목과 내용은 필수 입력 항목입니다.';
    } else {
        try {
            $pdo = getDBConnection();

            // 게시글 저장 (로그인 시 user_id 저장, 비로그인 시 NULL)
            $user_id = $_SESSION['user_id'] ?? null;
            $sql = "INSERT INTO posts (user_id, title, content, code_content, programming_language) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id, $title, $content, $code_content, $programming_language]);

            // 세션에 게시글 소유권 저장
            $post_id = $pdo->lastInsertId();
            if (!isset($_SESSION['my_posts'])) {
                $_SESSION['my_posts'] = [];
            }
            $_SESSION['my_posts'][] = $post_id;

            header('Location: index.php?msg=created');
            exit;
        } catch (PDOException $e) {
            $error = '게시글 저장 중 오류가 발생했습니다: ' . $e->getMessage();
        }
    }
}

// 프로그래밍 언어 목록
$languages = [
    'plaintext' => '일반 텍스트',
    'python' => 'Python',
    'javascript' => 'JavaScript',
    'java' => 'Java',
    'c' => 'C',
    'cpp' => 'C++',
    'csharp' => 'C#',
    'php' => 'PHP',
    'html' => 'HTML',
    'css' => 'CSS',
    'sql' => 'SQL',
    'go' => 'Go',
    'rust' => 'Rust',
    'typescript' => 'TypeScript',
    'kotlin' => 'Kotlin',
    'swift' => 'Swift'
];
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>글 작성 - <?php echo SITE_NAME; ?></title>
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
            <h1 class="page-title">✏️ 글 작성</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="card">
                <form method="POST" action="">


                    <div class="form-group">
                        <label for="title">제목 *</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="게시글 제목을 입력하세요"
                            required value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="programming_language">프로그래밍 언어</label>
                        <select id="programming_language" name="programming_language" class="form-control">
                            <?php foreach ($languages as $value => $label): ?>
                                <option value="<?php echo $value; ?>" <?php echo (($_POST['programming_language'] ?? '') === $value) ? 'selected' : ''; ?>>
                                    <?php echo $label; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="content">내용 *</label>
                        <textarea id="content" name="content" class="form-control" placeholder="리뷰 받고 싶은 내용을 상세히 설명해주세요"
                            required><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="code_content">코드 (선택사항)</label>
                        <textarea id="code_content" name="code_content" class="form-control"
                            style="font-family: 'IntelOneMonoItalic', 'Consolas', monospace; min-height: 200px;"
                            placeholder="리뷰 받고 싶은 코드를 붙여넣으세요"><?php echo htmlspecialchars($_POST['code_content'] ?? ''); ?></textarea>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">등록</button>
                        <a href="index.php" class="btn btn-secondary">취소</a>
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