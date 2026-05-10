<?php
require_once '../includes/functions.php';
requireLogin();

$action = $_GET['action'] ?? 'list';

try {
    $pdo = db();
    
    if ($action === 'approve' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("UPDATE comments SET status = 'approved' WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        header('Location: index.php?page=comments');
        exit;
    }
    
    if ($action === 'spam' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("UPDATE comments SET status = 'spam' WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        header('Location: index.php?page=comments');
        exit;
    }
    
    if ($action === 'delete' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        header('Location: index.php?page=comments');
        exit;
    }
    
    $statusFilter = $_GET['status'] ?? 'all';
    if ($statusFilter === 'all') {
        $comments = $pdo->query("
            SELECT c.*, ac.title as article_title, a.slug 
            FROM comments c
            LEFT JOIN articles a ON c.article_id = a.id
            LEFT JOIN article_content ac ON a.id = ac.article_id AND ac.lang = 'en'
            ORDER BY c.created_at DESC
        ")->fetchAll();
    } else {
        $stmt = $pdo->prepare("
            SELECT c.*, ac.title as article_title, a.slug 
            FROM comments c
            LEFT JOIN articles a ON c.article_id = a.id
            LEFT JOIN article_content ac ON a.id = ac.article_id AND ac.lang = 'en'
            WHERE c.status = ?
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([$statusFilter]);
        $comments = $stmt->fetchAll();
    }
    
    $counts = $pdo->query("SELECT status, COUNT(*) as count FROM comments GROUP BY status")->fetchAll(PDO::FETCH_KEY_PAIR);
    $total = array_sum($counts);
    
} catch (Exception $e) {
    $comments = [];
    $counts = [];
}
?>
<header class="main-header">
    <h1>Comments</h1>
</header>

<div class="filter-tabs">
    <a href="index.php?page=comments" class="filter-tab <?= $statusFilter === 'all' ? 'active' : '' ?>">
        All (<?= $total ?>)
    </a>
    <a href="index.php?page=comments&status=pending" class="filter-tab <?= $statusFilter === 'pending' ? 'active' : '' ?>">
        Pending (<?= $counts['pending'] ?? 0 ?>)
    </a>
    <a href="index.php?page=comments&status=approved" class="filter-tab <?= $statusFilter === 'approved' ? 'active' : '' ?>">
        Approved (<?= $counts['approved'] ?? 0 ?>)
    </a>
    <a href="index.php?page=comments&status=spam" class="filter-tab <?= $statusFilter === 'spam' ? 'active' : '' ?>">
        Spam (<?= $counts['spam'] ?? 0 ?>)
    </a>
</div>

<div class="comments-list">
    <?php foreach ($comments as $comment): ?>
    <div class="comment-item" style="background: #fff; border: 1px solid var(--rule); padding: 1rem; margin-bottom: 1rem;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <strong><?= htmlspecialchars($comment['user_name'] ?? 'Anonymous') ?></strong>
            <span class="status status-<?= $comment['status'] ?>"><?= $comment['status'] ?></span>
        </div>
        <p style="margin-bottom: 0.5rem; color: var(--ink-light);"><?= htmlspecialchars($comment['content']) ?></p>
        <div style="font-size: 0.8rem; color: var(--ink-faded);">
            On article: <a href="index.php?page=article&id=<?= $comment['article_id'] ?>"><?= htmlspecialchars($comment['article_title'] ?? 'N/A') ?></a>
            · <?= timeAgo($comment['created_at']) ?>
        </div>
        <div style="margin-top: 0.75rem; display: flex; gap: 0.5rem;">
            <?php if ($comment['status'] !== 'approved'): ?>
            <a href="index.php?page=comments&action=approve&id=<?= $comment['id'] ?>" class="btn-action" style="color: var(--success);">Approve</a>
            <?php endif; ?>
            <?php if ($comment['status'] !== 'spam'): ?>
            <a href="index.php?page=comments&action=spam&id=<?= $comment['id'] ?>" class="btn-action">Spam</a>
            <?php endif; ?>
            <a href="index.php?page=comments&action=delete&id=<?= $comment['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Delete comment?')">Delete</a>
        </div>
    </div>
    <?php endforeach; ?>
    
    <?php if (empty($comments)): ?>
    <p style="text-align: center; color: var(--ink-faded); padding: 2rem;">No comments found.</p>
    <?php endif; ?>
</div>

<style>
.filter-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid var(--rule);
    padding-bottom: 1rem;
}
.filter-tab {
    padding: 0.5rem 1rem;
    text-decoration: none;
    color: var(--ink-light);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    border-radius: 4px;
}
.filter-tab:hover, .filter-tab.active {
    background: var(--accent);
    color: #fff;
}
.btn-action {
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
    font-family: 'DM Sans', sans-serif;
    border: 1px solid var(--rule);
    background: #fff;
    text-decoration: none;
    border-radius: 3px;
    cursor: pointer;
}
.btn-delete {
    border-color: var(--danger);
    color: var(--danger);
}
</style>