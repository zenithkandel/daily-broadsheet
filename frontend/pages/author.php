<?php
require_once '../../includes/functions.php';
require_once '../../backend/config/database.php';

$lang = $_SESSION['lang'] ?? 'en';
$authorId = $_GET['id'] ?? null;

if (!$authorId) {
    header('Location: index.php');
    exit;
}

try {
    $pdo = db();
    
    $authorStmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $authorStmt->execute([$authorId]);
    $author = $authorStmt->fetch();
    
    if (!$author) {
        echo "<h1>Author not found</h1>";
        echo "<p><a href='index.php'>Go back home</a></p>";
        exit;
    }
    
    $artStmt = $pdo->prepare("
        SELECT a.*, ac.title, ac.excerpt 
        FROM articles a 
        JOIN article_content ac ON a.id = ac.article_id AND ac.lang = ?
        WHERE a.author_id = ? AND a.status = 'published'
        ORDER BY a.published_at DESC
    ");
    $artStmt->execute([$lang, $authorId]);
    $articles = $artStmt->fetchAll();
    
} catch (Exception $e) {
    $author = null;
    $articles = [];
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($author['name'] ?? 'Author') ?> - The Daily Broadsheet</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@400;500;700&family=Lora:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        .author-header {
            text-align: center;
            padding: 3rem 0;
            border-bottom: 3px solid var(--rule);
            margin-bottom: 2rem;
        }
        .author-name {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .author-bio {
            color: var(--ink-light);
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body class="home-page">
    <?php include '../components/masthead.php'; ?>
    <div class="main-container">
        <?php include '../components/header.php'; ?>
        
        <main class="content-wrapper">
            <div class="main-feed" style="grid-column: 1 / -1;">
                <div class="author-header">
                    <h1 class="author-name"><?= htmlspecialchars($author['name']) ?></h1>
                    <?php if ($author['bio']): ?>
                    <p class="author-bio"><?= htmlspecialchars($author['bio']) ?></p>
                    <?php endif; ?>
                </div>
                
                <h2 class="section-title">Articles by <?= htmlspecialchars($author['name']) ?></h2>
                
                <div class="articles-grid">
                    <?php foreach ($articles as $article): ?>
                    <article class="article-card">
                        <div class="card-image">
                            <img src="<?= $article['featured_image'] ?? '../assets/images/placeholder.jpg' ?>" alt="">
                        </div>
                        <div class="card-content">
                            <h3><a href="index.php?page=article&id=<?= $article['id'] ?>"><?= htmlspecialchars($article['title']) ?></a></h3>
                            <p><?= htmlspecialchars(mb_strimwidth($article['excerpt'] ?? '', 0, 100, '...')) ?></p>
                            <div class="card-meta">
                                <span><?= timeAgo($article['published_at']) ?></span>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                    
                    <?php if (empty($articles)): ?>
                    <div class="empty-message">
                        <p>No articles by this author yet.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
        
        <?php include '../components/footer.php'; ?>
    </div>
    <script src="../assets/js/main.js"></script>
</body>
</html>