<?php
require_once '../includes/functions.php';
requireLogin();

$page = $_GET['page'] ?? 'dashboard';

$allowedPages = ['dashboard', 'articles', 'article-edit', 'categories', 'media', 'comments', 'users', 'settings'];

if (!in_array($page, $allowedPages)) {
    $page = 'dashboard';
}

$stats = ['total_articles' => 0, 'published' => 0, 'drafts' => 0, 'comments_pending' => 0];

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
    <title>Admin - The Daily Broadsheet</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@400;500;700&family=Lora:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
    <script src="https://zenithkandel.com.np/fontawesome/zenith-icons.js"></script>
</head>
<body class="admin-body">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <a href="index.php">The Daily Broadsheet</a>
            <span class="brand-sub">Admin</span>
        </div>
        
        <nav class="sidebar-nav">
            <a href="index.php?page=dashboard" class="nav-item <?= $page === 'dashboard' ? 'active' : '' ?>">
                <i class="fa-duotone fa-grid-2"></i> Dashboard
            </a>
            <a href="index.php?page=articles" class="nav-item <?= $page === 'articles' || $page === 'article-edit' ? 'active' : '' ?>">
                <i class="fa-duotone fa-newspaper"></i> Articles
            </a>
            <a href="index.php?page=categories" class="nav-item <?= $page === 'categories' ? 'active' : '' ?>">
                <i class="fa-duotone fa-folders"></i> Categories
            </a>
            <a href="index.php?page=media" class="nav-item <?= $page === 'media' ? 'active' : '' ?>">
                <i class="fa-duotone fa-images"></i> Media
            </a>
            <a href="index.php?page=comments" class="nav-item <?= $page === 'comments' ? 'active' : '' ?>">
                <i class="fa-duotone fa-comments"></i> Comments
                <?php if ($stats['comments_pending'] > 0): ?>
                    <span class="badge"><?= $stats['comments_pending'] ?></span>
                <?php endif; ?>
            </a>
            <a href="index.php?page=users" class="nav-item <?= $page === 'users' ? 'active' : '' ?>">
                <i class="fa-duotone fa-users"></i> Users
            </a>
            <a href="index.php?page=settings" class="nav-item <?= $page === 'settings' ? 'active' : '' ?>">
                <i class="fa-duotone fa-gear"></i> Settings
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
        <?php
        switch ($page) {
            case 'dashboard':
                include 'pages/dashboard.php';
                break;
            case 'articles':
                include 'pages/articles.php';
                break;
            case 'article-edit':
                include 'article-edit.php';
                break;
            case 'categories':
                include 'pages/categories.php';
                break;
            case 'media':
                include 'pages/media.php';
                break;
            case 'comments':
                include 'pages/comments.php';
                break;
            case 'users':
                include 'pages/users.php';
                break;
            case 'settings':
                include 'pages/settings.php';
                break;
            default:
                include 'pages/dashboard.php';
        }
        ?>
    </main>
</body>
</html>