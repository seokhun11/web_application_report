<?php
/**
 * 메인 페이지 - 게시글 목록
 */
require_once 'config.php';

$pdo = getDBConnection();
$pageTitle = SITE_NAME . ' - 홈';

// 게시글 목록 조회
$sql = "SELECT p.*, u.username, 
        (SELECT COUNT(*) FROM comments WHERE post_id = p.id) as comment_count
        FROM posts p 
        LEFT JOIN users u ON p.user_id = u.id 
        ORDER BY p.created_at DESC";
$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<h1 class="page-title">코드 리뷰 게시판</h1>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">
        <?php
        $messages = [
            'created' => '게시글이 작성되었습니다.',
            'updated' => '게시글이 수정되었습니다.',
            'deleted' => '게시글이 삭제되었습니다.'
        ];
        echo $messages[$_GET['msg']] ?? '';
        ?>
    </div>
<?php endif; ?>

<div class="post-list">
    <?php if (empty($posts)): ?>
        <div class="card">
            <p style="text-align: center; color: var(--text-secondary);">
                아직 작성된 게시글이 없습니다. 첫 번째 글을 작성해보세요!
            </p>
        </div>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="card post-item">
                <div>
                    <span class="language-tag"><?php echo htmlspecialchars($post['programming_language']); ?></span>
                </div>
                <a href="view.php?id=<?php echo $post['id']; ?>" class="post-title">
                    <?php echo htmlspecialchars($post['title']); ?>
                </a>
                <p style="color: var(--text-secondary);">
                    <?php echo mb_substr(strip_tags($post['content']), 0, 100) . '...'; ?>
                </p>
                <div class="post-meta">
                    <span class="icon-user"><?php echo htmlspecialchars($post['username'] ?? '익명'); ?></span>
                    <span class="icon-date"><?php echo date('Y-m-d H:i', strtotime($post['created_at'])); ?></span>
                    <span class="icon-views"><?php echo $post['views']; ?> 조회</span>
                    <span class="icon-comments"><?php echo $post['comment_count']; ?> 댓글</span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if (isset($_SESSION['user_id'])): ?>
    <div class="btn-group" style="margin-top: 30px;">
        <a href="write.php" class="btn btn-primary">글 작성</a>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>