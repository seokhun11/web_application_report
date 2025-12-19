<?php
/**
 * 게시글 작성 페이지
 */
require_once 'config.php';

$error = '';
$pageTitle = '글 작성 - ' . SITE_NAME;

// 폼 제출 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $code_content = trim($_POST['code_content'] ?? '');
    $programming_language = trim($_POST['programming_language'] ?? 'plaintext');

    if (empty($title) || empty($content)) {
        $error = '제목과 내용은 필수 입력 항목입니다.';
    } else {
        try {
            $pdo = getDBConnection();

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

require_once 'includes/header.php';
?>

<h1 class="page-title">글 작성</h1>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <form method="POST" action="">
        <div class="form-group">
            <label for="title">제목 *</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="게시글 제목을 입력하세요" required
                value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
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
            <textarea id="code_content" name="code_content" class="form-control code-input"
                placeholder="리뷰 받고 싶은 코드를 붙여넣으세요"><?php echo htmlspecialchars($_POST['code_content'] ?? ''); ?></textarea>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">등록</button>
            <a href="index.php" class="btn btn-secondary">취소</a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>