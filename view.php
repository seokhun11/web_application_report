<?php
/**
 * 게시글 상세보기 페이지
 */
require_once 'config.php';

$pdo = getDBConnection();

// 게시글 ID 확인
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// 조회수 증가
$pdo->prepare("UPDATE posts SET views = views + 1 WHERE id = ?")->execute([$id]);

// 게시글 조회
$sql = "SELECT p.*, u.username 
        FROM posts p 
        LEFT JOIN users u ON p.user_id = u.id 
        WHERE p.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: index.php');
    exit;
}

// 댓글 목록 조회
$sql = "SELECT c.*, u.username 
        FROM comments c 
        LEFT JOIN users u ON c.user_id = u.id 
        WHERE c.post_id = ? 
        ORDER BY c.created_at ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$comments = $stmt->fetchAll();

// 댓글 작성 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_submit'])) {
    $comment_content = trim($_POST['comment_content'] ?? '');

    if (!empty($comment_content)) {
        $user_id = $_SESSION['user_id'] ?? null;
        $sql = "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $user_id, $comment_content]);

        header("Location: view.php?id=$id#comments");
        exit;
    }
}

// 현재 사용자가 이 게시글의 소유자인지 확인
$isOwner = isset($_SESSION['my_posts']) && in_array($id, $_SESSION['my_posts']);
$pageTitle = htmlspecialchars($post['title']) . ' - ' . SITE_NAME;

require_once 'includes/header.php';
?>

<!-- 게시글 내용 -->
<div class="card">
    <div style="margin-bottom: 15px;">
        <span class="language-tag"><?php echo htmlspecialchars($post['programming_language']); ?></span>
    </div>

    <h1 class="page-title"><?php echo htmlspecialchars($post['title']); ?></h1>

    <div class="post-meta" style="margin-bottom: 30px;">
        <span class="icon-user"><?php echo htmlspecialchars($post['username'] ?? '익명'); ?></span>
        <span class="icon-date"><?php echo date('Y-m-d H:i:s', strtotime($post['created_at'])); ?></span>
        <span class="icon-views"><?php echo $post['views']; ?> 조회</span>
    </div>

    <div style="color: var(--text-primary); line-height: 1.8; margin-bottom: 30px;">
        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
    </div>

    <?php if (!empty($post['code_content'])): ?>
        <h3 style="margin-bottom: 15px; color: var(--primary-color);">코드</h3>
        <div class="code-block">
            <pre><code><?php echo htmlspecialchars($post['code_content']); ?></code></pre>
        </div>
    <?php endif; ?>

    <div class="btn-group">
        <?php if ($isOwner): ?>
            <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">수정</a>
            <a href="delete.php?id=<?php echo $post['id']; ?>" class="btn btn-danger"
                onclick="return confirm('정말 삭제하시겠습니까?');">삭제</a>
        <?php endif; ?>
        <a href="index.php" class="btn btn-secondary">목록으로</a>
    </div>
</div>

<!-- 댓글 섹션 -->
<div class="comments-section" id="comments">
    <h3>💬 댓글 (<?php echo count($comments); ?>)</h3>

    <?php if (empty($comments)): ?>
        <div class="card">
            <p style="text-align: center; color: var(--text-secondary);">
                아직 댓글이 없습니다. 첫 번째 리뷰를 남겨보세요!
            </p>
        </div>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <div class="comment-header">
                    <span class="comment-author"><?php echo htmlspecialchars($comment['username'] ?? '익명'); ?></span>
                    <span><?php echo date('Y-m-d H:i', strtotime($comment['created_at'])); ?></span>
                </div>
                <div class="comment-content">
                    <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- 댓글 작성 폼 -->
    <div class="card" style="margin-top: 30px;">
        <h4 style="margin-bottom: 20px;">리뷰 작성</h4>
        <form method="POST" action="">
            <div class="form-group">
                <label for="comment_content">내용</label>
                <textarea id="comment_content" name="comment_content" class="form-control"
                    placeholder="코드 리뷰 또는 의견을 작성하세요" required></textarea>
            </div>
            <button type="submit" name="comment_submit" class="btn btn-success">등록</button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>