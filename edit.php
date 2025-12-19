<?php
/**
 * 게시글 수정 페이지
 */
require_once 'config.php';

$pdo = getDBConnection();
$error = '';

// 게시글 ID 확인
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// 기존 게시글 조회
$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: index.php');
    exit;
}

// 세션 기반 소유권 확인
$isOwner = isset($_SESSION['my_posts']) && in_array($id, $_SESSION['my_posts']);

if (!$isOwner) {
    header('Location: view.php?id=' . $id);
    exit;
}

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
            $sql = "UPDATE posts SET title = ?, content = ?, code_content = ?, programming_language = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $content, $code_content, $programming_language, $id]);

            header('Location: view.php?id=' . $id);
            exit;
        } catch (PDOException $e) {
            $error = '게시글 수정 중 오류가 발생했습니다: ' . $e->getMessage();
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

$pageTitle = '게시글 수정 - ' . SITE_NAME;
require_once 'includes/header.php';
?>

<h1 class="page-title">게시글 수정</h1>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <form method="POST" action="">
        <div class="form-group">
            <label for="title">제목 *</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="게시글 제목을 입력하세요" required
                value="<?php echo htmlspecialchars($_POST['title'] ?? $post['title']); ?>">
        </div>

        <div class="form-group">
            <label for="programming_language">프로그래밍 언어</label>
            <select id="programming_language" name="programming_language" class="form-control">
                <?php foreach ($languages as $value => $label): ?>
                    <option value="<?php echo $value; ?>" <?php
                       $selected = $_POST['programming_language'] ?? $post['programming_language'];
                       echo ($selected === $value) ? 'selected' : '';
                       ?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="content">내용 *</label>
            <textarea id="content" name="content" class="form-control" placeholder="리뷰 받고 싶은 내용을 상세히 설명해주세요"
                required><?php echo htmlspecialchars($_POST['content'] ?? $post['content']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="code_content">코드 (선택사항)</label>
            <textarea id="code_content" name="code_content" class="form-control code-input"
                placeholder="리뷰 받고 싶은 코드를 붙여넣으세요"><?php echo htmlspecialchars($_POST['code_content'] ?? $post['code_content']); ?></textarea>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">수정 완료</button>
            <a href="view.php?id=<?php echo $id; ?>" class="btn btn-secondary">취소</a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>