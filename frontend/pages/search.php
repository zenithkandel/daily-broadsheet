<?php
require_once '../../includes/functions.php';
require_once '../../backend/config/database.php';

$lang = $_SESSION['lang'] ?? 'en';
$query = $_GET['q'] ?? '';

try {
    $pdo = db();
    
    $searchTerm = "%{$query}%";
    $stmt = $pdo->prepare("
        SELECT a.*, ac.title, ac.excerpt 
        FROM articles a 
        JOIN article_content ac ON a.id = ac.article_id AND ac.lang = ?
        WHERE a.status = 'published' AND (ac.title LIKE ? OR ac.excerpt LIKE ?)
        ORDER BY a.published_at DESC
    ");
    $stmt->execute([$lang, $searchTerm, $searchTerm]);
    $results = $stmt->fetchAll();
    
} catch (Exception $e) {
    $results = [];
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search: <?= htmlspecialchars($query) ?> - The Daily Broadsheet</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@400;500;700&family=Lora:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body class="home-page">
    <?php include '../components/masthead.php'; ?>
    <div class="main-container">
        <?php include '../components/header.php'; ?>
        
        <main class="content-wrapper">
            <div class="main-feed" style="grid-column: 1 / -1;">
                <h1 class="search-title">Search Results for "<?= htmlspecialchars($query) ?>"</h1>
                <p class="search-count"><?= count($results) ?> result(s) found</p>
                
                <div class="articles-grid">
                    <?php foreach ($results as $article): ?>
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
                    
                    <?php if (empty($results)): ?>
                    <div class="empty-message">
                        <p>No results found. Try different keywords.</p>
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