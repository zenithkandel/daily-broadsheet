<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../backend/config/database.php';

$lang = $_SESSION['lang'] ?? 'en';

try {
    $pdo = db();
    
    $featuredArticle = $pdo->prepare("
        SELECT a.*, ac.title, ac.excerpt, ac.body 
        FROM articles a 
        JOIN article_content ac ON a.id = ac.article_id 
        WHERE a.status = 'published' AND a.featured = 1 AND ac.lang = ?
        ORDER BY a.published_at DESC LIMIT 1
    ");
    $featuredArticle->execute([$lang]);
    $featured = $featuredArticle->fetch();
    
    $trendingStmt = $pdo->prepare("
        SELECT a.*, ac.title, ac.excerpt 
        FROM articles a 
        JOIN article_content ac ON a.id = ac.article_id 
        WHERE a.status = 'published' AND ac.lang = ?
        ORDER BY a.view_count DESC LIMIT 5
    ");
    $trendingStmt->execute([$lang]);
    $trending = $trendingStmt->fetchAll();
    
    $recentStmt = $pdo->prepare("
        SELECT a.*, ac.title, ac.excerpt 
        FROM articles a 
        JOIN article_content ac ON a.id = ac.article_id 
        WHERE a.status = 'published' AND ac.lang = ? AND (a.featured = 0 OR a.id != ?)
        ORDER BY a.published_at DESC LIMIT 10
    ");
    $recentStmt->execute([$lang, $featured['id'] ?? 0]);
    $recent = $recentStmt->fetchAll();
    
    $categories = $pdo->query("SELECT * FROM categories ORDER BY sort_order")->fetchAll();
    
} catch (Exception $e) {
    $featured = null;
    $trending = [];
    $recent = [];
    $categories = [];
}

$siteName = 'The Daily Broadsheet';
$today = date('F j, Y');
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $siteName ?> - <?= $today ?></title>
    <meta name="description" content="A nod to classic print newspapers reimagined for the web">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@400;500;700&family=Lora:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/codes/daily-broadsheet/frontend/assets/css/main.css">
    <script src="https://zenithkandel.com.np/fontawesome/zenith-icons.js"></script>
</head>
<body class="home-page">
    <?php include __DIR__ . '/../components/masthead.php'; ?>
    
    <div class="main-container">
        <?php include __DIR__ . '/../components/header.php'; ?>
        
        <main class="content-wrapper">
            <?php if ($featured): ?>
            <section class="hero-section">
                <div class="hero-image">
                    <img src="<?= $featured['featured_image'] ?? '../assets/images/placeholder.jpg' ?>" alt="">
                </div>
                <div class="hero-content">
                    <span class="hero-category"><?= getCategoryName($featured['category_id'], $categories) ?></span>
                    <h1><a href="index.php?page=article&id=<?= $featured['id'] ?>"><?= htmlspecialchars($featured['title']) ?></a></h1>
                    <p class="hero-excerpt"><?= htmlspecialchars($featured['excerpt'] ?? '') ?></p>
                    <div class="hero-meta">
                        <span><?= timeAgo($featured['published_at']) ?></span>
                    </div>
                </div>
            </section>
            <?php endif; ?>
            
            <div class="content-grid">
                <aside class="sidebar-left">
                    <h3 class="sidebar-title">Trending</h3>
                    <ul class="trending-list">
                        <?php foreach ($trending as $i => $item): ?>
                        <li class="trending-item">
                            <span class="trending-number"><?= $i + 1 ?></span>
                            <a href="index.php?page=article&id=<?= $item['id'] ?>"><?= htmlspecialchars($item['title']) ?></a>
                        </li>
                        <?php endforeach; ?>
                        <?php if (empty($trending)): ?>
                        <li class="trending-item">No trending articles yet.</li>
                        <?php endif; ?>
                    </ul>
                </aside>
                
                <div class="main-feed">
                    <h2 class="section-title">Latest News</h2>
                    <div class="articles-grid">
                        <?php foreach ($recent as $article): ?>
                        <article class="article-card">
                            <div class="card-image">
                                <img src="<?= $article['featured_image'] ?? '../assets/images/placeholder.jpg' ?>" alt="">
                            </div>
                            <div class="card-content">
                                <span class="card-category"><?= getCategoryName($article['category_id'], $categories) ?></span>
                                <h3><a href="index.php?page=article&id=<?= $article['id'] ?>"><?= htmlspecialchars($article['title']) ?></a></h3>
                                <p><?= htmlspecialchars(mb_strimwidth($article['excerpt'] ?? '', 0, 100, '...')) ?></p>
                                <div class="card-meta">
                                    <span><?= timeAgo($article['published_at']) ?></span>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                        
                        <?php if (empty($recent)): ?>
                        <div class="empty-message">
                            <p>No articles published yet.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
        
        <?php include __DIR__ . '/../components/footer.php'; ?>
    </div>
    
    <script src="/codes/daily-broadsheet/frontend/assets/js/main.js"></script>
</body>
</html>

<?php
function getCategoryName($categoryId, $categories) {
    foreach ($categories as $cat) {
        if ($cat['id'] == $categoryId) {
            return $cat['name_en'];
        }
    }
    return 'News';
}