<?php
/**
 * 게시글 삭제 처리
 */
require_once 'config.php';

$pdo = getDBConnection();

// 게시글 ID 확인
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// 세션 기반 소유권 확인
$isOwner = isset($_SESSION['my_posts']) && in_array($id, $_SESSION['my_posts']);

if (!$isOwner) {
    // 권한이 없으면 목록으로 리다이렉트
    header('Location: index.php?msg=unauthorized');
    exit;
}

try {
    // 게시글 존재 여부 확인
    $stmt = $pdo->prepare("SELECT id FROM posts WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->fetch()) {
        // 게시글 삭제 (댓글은 CASCADE로 자동 삭제)
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$id]);

        // 세션에서도 제거
        $_SESSION['my_posts'] = array_filter($_SESSION['my_posts'], fn($pid) => $pid != $id);

        header('Location: index.php?msg=deleted');
        exit;
    } else {
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    die('삭제 중 오류가 발생했습니다: ' . $e->getMessage());
}
?>