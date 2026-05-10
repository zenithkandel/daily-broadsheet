<?php
require_once '../includes/functions.php';
requireLogin();

$page = $_GET['page'] ?? 'dashboard';

$stats = [
    'total_articles' => 0,
    'published' => 0,
    'drafts' => 0,
    'comments_pending' => 0
];

try {
    $pdo = db();
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM articles");
    $stats['total_articles'] = $stmt->fetch()['total'] ?? 0;
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM articles WHERE status = 'published'");
    $stats['published'] = $stmt->fetch()['total'] ?? 0;
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM articles WHERE status = 'draft'");
    $stats['drafts'] = $stmt->fetch()['total'] ?? 0;
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM comments WHERE status = 'pending'");
    $stats['comments_pending'] = $stmt->fetch()['total'] ?? 0;
    
    $recentArticles = $pdo->query("
        SELECT a.id, a.status, a.created_at, ac.title 
        FROM articles a 
        LEFT JOIN article_content ac ON a.id = ac.article_id AND ac.lang = 'en' 
        ORDER BY a.created_at DESC LIMIT 5
    ")->fetchAll();
    
    $recentComments = $pdo->query("
        SELECT c.*, ac.title as article_title 
        FROM comments c 
        LEFT JOIN articles a ON c.article_id = a.id 
        LEFT JOIN article_content ac ON a.id = ac.article_id AND ac.lang = 'en'
        ORDER BY c.created_at DESC LIMIT 5
    ")->fetchAll();
    
} catch (Exception $e) {
    $recentArticles = [];
    $recentComments = [];
}

$userName = $_SESSION['user_name'] ?? 'Admin';
$userRole = $_SESSION['role'] ?? 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - The Daily Broadsheet Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@400;500;700&family=Lora:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="admin-body">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <a href="index.php">The Daily Broadsheet</a>
            <span class="brand-sub">Admin</span>
        </div>
        
        <nav class="sidebar-nav">
            <a href="index.php?page=dashboard" class="nav-item active">
                <span class="icon">&#9632;</span> Dashboard
            </a>
            <a href="index.php?page=articles" class="nav-item">
                <span class="icon">&#9776;</span> Articles
            </a>
            <a href="index.php?page=categories" class="nav-item">
                <span class="icon">&#963;</span> Categories
            </a>
            <a href="index.php?page=media" class="nav-item">
                <span class="icon">&#9741;</span> Media
            </a>
            <a href="index.php?page=comments" class="nav-item">
                <span class="icon">&#9827;</span> Comments
                <?php if ($stats['comments_pending'] > 0): ?>
                    <span class="badge"><?= $stats['comments_pending'] ?></span>
                <?php endif; ?>
            </a>
            <a href="index.php?page=users" class="nav-item">
                <span class="icon">&#9829;</span> Users
            </a>
            <a href="index.php?page=settings" class="nav-item">
                <span class="icon">&#9881;</span> Settings
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <span class="user-name"><?= htmlspecialchars($userName) ?></span>
                <span class="user-role"><?= htmlspecialchars($userRole) ?></span>
            </div>
            <a href="../backend/controllers/AuthController.php?action=logout" class="logout-btn">Logout</a>
        </div>
    </aside>
    
    <main class="main-content">
        <header class="main-header">
            <h1>Dashboard</h1>
            <div class="header-actions">
                <a href="index.php?page=article-edit&id=new" class="btn btn-primary">+ New Article</a>
            </div>
        </header>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?= $stats['total_articles'] ?></div>
                <div class="stat-label">Total Articles</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['published'] ?></div>
                <div class="stat-label">Published</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['drafts'] ?></div>
                <div class="stat-label">Drafts</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $stats['comments_pending'] ?></div>
                <div class="stat-label">Pending Comments</div>
            </div>
        </div>
        
        <div class="content-grid">
            <section class="content-card">
                <div class="card-header">
                    <h2>Recent Articles</h2>
                    <a href="index.php?page=articles" class="card-link">View All</a>
                </div>
                <div class="card-body">
                    <?php if (empty($recentArticles)): ?>
                        <p class="empty-state">No articles yet. <a href="index.php?page=article-edit&id=new">Create your first article</a></p>
                    <?php else: ?>
                        <ul class="item-list">
                            <?php foreach ($recentArticles as $article): ?>
                                <li class="item">
                                    <a href="index.php?page=article-edit&id=<?= $article['id'] ?>" class="item-title">
                                        <?= htmlspecialchars($article['title'] ?? 'Untitled') ?>
                                    </a>
                                    <span class="item-meta">
                                        <span class="status status-<?= $article['status'] ?>"><?= $article['status'] ?></span>
                                        <span class="date"><?= timeAgo($article['created_at']) ?></span>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </section>
            
            <section class="content-card">
                <div class="card-header">
                    <h2>Recent Comments</h2>
                    <a href="index.php?page=comments" class="card-link">View All</a>
                </div>
                <div class="card-body">
                    <?php if (empty($recentComments)): ?>
                        <p class="empty-state">No comments yet.</p>
                    <?php else: ?>
                        <ul class="item-list">
                            <?php foreach ($recentComments as $comment): ?>
                                <li class="item">
                                    <div class="comment-content">
                                        <span class="comment-author"><?= htmlspecialchars($comment['user_name'] ?? 'Anonymous') ?></span>
                                        <span class="comment-text"><?= htmlspecialchars(mb_strimwidth($comment['content'], 0, 50, '...')) ?></span>
                                    </div>
                                    <span class="item-meta">
                                        <span class="status status-<?= $comment['status'] ?>"><?= $comment['status'] ?></span>
                                        <span class="date"><?= timeAgo($comment['created_at']) ?></span>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>
</body>
</html>