<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../backend/config/database.php';

$lang = $_SESSION['lang'] ?? 'en';
$slug = $_GET['slug'] ?? '';

try {
    $pdo = db();
    
    $catStmt = $pdo->prepare("SELECT * FROM categories WHERE slug = ?");
    $catStmt->execute([$slug]);
    $category = $catStmt->fetch();
    
    if (!$category) {
        echo "<h1>Category not found</h1>";
        echo "<p><a href='index.php'>Go back home</a></p>";
        exit;
    }
    
    $catName = $lang === 'ne' ? ($category['name_ne'] ?? $category['name_en']) : $category['name_en'];
    
    $artStmt = $pdo->prepare("
        SELECT a.*, ac.title, ac.excerpt 
        FROM articles a 
        JOIN article_content ac ON a.id = ac.article_id AND ac.lang = ?
        WHERE a.category_id = ? AND a.status = 'published'
        ORDER BY a.published_at DESC
    ");
    $artStmt->execute([$lang, $category['id']]);
    $articles = $artStmt->fetchAll();
    
} catch (Exception $e) {
    $category = null;
    $articles = [];
    $catName = 'Category';
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($catName) ?> - The Daily Broadsheet</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@400;500;700&family=Lora:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <script src="https://zenithkandel.com.np/fontawesome/zenith-icons.js"></script>
</head>
<body class="home-page">
    <?php include '../components/masthead.php'; ?>
    <div class="main-container">
        <?php include '../components/header.php'; ?>
        
        <main class="content-wrapper">
            <div class="main-feed" style="grid-column: 1 / -1;">
                <h1 class="category-title"><?= htmlspecialchars($catName) ?></h1>
                
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
                        <p>No articles in this category yet.</p>
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