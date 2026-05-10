<?php
$userName = $_SESSION['user_name'] ?? 'Admin';
?>
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