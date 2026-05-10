<?php
require_once '../includes/functions.php';
requireLogin();

$action = $_GET['action'] ?? 'list';

try {
    $pdo = db();
    
    if ($action === 'delete' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        header('Location: index.php?page=articles');
        exit;
    }
    
    if ($action === 'toggle-featured' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("UPDATE articles SET featured = NOT featured WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        header('Location: index.php?page=articles');
        exit;
    }
    
    $articles = $pdo->query("
        SELECT a.*, ac.title, ac.excerpt, u.name as author_name, c.name_en as category_name
        FROM articles a
        LEFT JOIN article_content ac ON a.id = ac.article_id AND ac.lang = 'en'
        LEFT JOIN users u ON a.author_id = u.id
        LEFT JOIN categories c ON a.category_id = c.id
        ORDER BY a.created_at DESC
    ")->fetchAll();
    
    $statusCounts = $pdo->query("
        SELECT status, COUNT(*) as count FROM articles GROUP BY status
    ")->fetchAll(PDO::FETCH_KEY_PAIR);
    
} catch (Exception $e) {
    $articles = [];
    $statusCounts = [];
}

$currentStatus = $_GET['status'] ?? 'all';
$filteredArticles = $currentStatus === 'all' 
    ? $articles 
    : array_filter($articles, fn($a) => $a['status'] === $currentStatus);
?>

<header class="main-header">
    <h1><i class="fa-duotone fa-newspaper"></i> Articles</h1>
    <a href="index.php?page=article-edit&id=new" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Article</a>
</header>

<div class="filter-tabs">
    <a href="index.php?page=articles" class="filter-tab <?= $currentStatus === 'all' ? 'active' : '' ?>">
        All (<?= count($articles) ?>)
    </a>
    <a href="index.php?page=articles&status=published" class="filter-tab <?= $currentStatus === 'published' ? 'active' : '' ?>">
        Published (<?= $statusCounts['published'] ?? 0 ?>)
    </a>
    <a href="index.php?page=articles&status=draft" class="filter-tab <?= $currentStatus === 'draft' ? 'active' : '' ?>">
        Drafts (<?= $statusCounts['draft'] ?? 0 ?>)
    </a>
    <a href="index.php?page=articles&status=scheduled" class="filter-tab <?= $currentStatus === 'scheduled' ? 'active' : '' ?>">
        Scheduled (<?= $statusCounts['scheduled'] ?? 0 ?>)
    </a>
</div>

<table class="articles-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Author</th>
            <th>Status</th>
            <th>Views</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($filteredArticles as $article): ?>
        <tr>
            <td>
                <div class="article-title">
                    <a href="index.php?page=article-edit&id=<?= $article['id'] ?>">
                        <?= htmlspecialchars($article['title'] ?? 'Untitled') ?>
                    </a>
                    <?php if ($article['featured']): ?>
                    <span class="featured-badge"><i class="fa-solid fa-star"></i> Featured</span>
                    <?php endif; ?>
                </div>
                <div class="article-excerpt"><?= htmlspecialchars($article['excerpt'] ?? '') ?></div>
            </td>
            <td><?= htmlspecialchars($article['category_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($article['author_name'] ?? '-') ?></td>
            <td>
                <span class="status status-<?= $article['status'] ?>"><?= $article['status'] ?></span>
            </td>
            <td><?= number_format($article['view_count'] ?? 0) ?></td>
            <td><?= date('M d, Y', strtotime($article['created_at'])) ?></td>
            <td>
                <div class="action-btns">
                    <a href="index.php?page=article-edit&id=<?= $article['id'] ?>" class="btn-action btn-edit"><i class="fa-solid fa-pen"></i></a>
                    <a href="index.php?page=articles&action=toggle-featured&id=<?= $article['id'] ?>" class="btn-action">
                        <i class="fa-solid fa-star"></i>
                    </a>
                    <a href="index.php?page=articles&action=delete&id=<?= $article['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Delete this article?')"><i class="fa-solid fa-trash"></i></a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        
        <?php if (empty($filteredArticles)): ?>
        <tr>
            <td colspan="7" style="text-align: center; padding: 2rem; color: var(--ink-faded);">
                No articles found. <a href="index.php?page=article-edit&id=new">Create one</a>
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

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
.articles-table {
    width: 100%;
    background: #fff;
    border: 1px solid var(--rule);
}
.articles-table th,
.articles-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--rule);
}
.articles-table th {
    font-family: 'DM Sans', sans-serif;
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--ink-light);
    background: var(--paper-dark);
}
.articles-table tr:hover {
    background: var(--paper);
}
.article-title {
    font-weight: 600;
    color: var(--ink);
}
.article-title a {
    color: inherit;
    text-decoration: none;
}
.article-title a:hover {
    color: var(--accent);
}
.article-excerpt {
    font-size: 0.85rem;
    color: var(--ink-faded);
    max-width: 300px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.featured-badge {
    display: inline-block;
    background: var(--highlight);
    color: var(--ink);
    font-size: 0.7rem;
    padding: 0.125rem 0.5rem;
    border-radius: 2px;
    margin-left: 0.5rem;
}
.action-btns {
    display: flex;
    gap: 0.5rem;
}
.btn-action {
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
    font-family: 'DM Sans', sans-serif;
    border: 1px solid var(--rule);
    background: #fff;
    color: var(--ink);
    text-decoration: none;
    border-radius: 3px;
    cursor: pointer;
}
.btn-action:hover {
    background: var(--paper-dark);
}
.btn-edit {
    border-color: var(--accent);
    color: var(--accent);
}
.btn-delete {
    border-color: var(--danger);
    color: var(--danger);
}
.btn-delete:hover {
    background: #fee;
}
.main-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}
.main-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 1.75rem;
}
</style>