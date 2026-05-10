<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../backend/config/database.php';

$lang = $_SESSION['lang'] ?? 'en';
$articleId = $_GET['id'] ?? null;

if (!$articleId) {
    header('Location: index.php');
    exit;
}

try {
    $pdo = db();
    
    $stmt = $pdo->prepare("
        SELECT a.*, ac.title, ac.excerpt, ac.body, ac.meta_title, ac.meta_desc,
               u.name as author_name, c.name_en as category_name, c.slug as category_slug
        FROM articles a
        JOIN article_content ac ON a.id = ac.article_id AND ac.lang = ?
        LEFT JOIN users u ON a.author_id = u.id
        LEFT JOIN categories c ON a.category_id = c.id
        WHERE a.id = ? AND a.status = 'published'
    ");
    $stmt->execute([$lang, $articleId]);
    $article = $stmt->fetch();
    
    if (!$article) {
        echo "<h1>Article not found</h1>";
        echo "<p><a href='index.php'>Go back home</a></p>";
        exit;
    }
    
    $pdo->prepare("UPDATE articles SET view_count = view_count + 1 WHERE id = ?")->execute([$articleId]);
    
    $relatedStmt = $pdo->prepare("
        SELECT a.id, ac.title, a.featured_image
        FROM articles a
        JOIN article_content ac ON a.id = ac.article_id AND ac.lang = ?
        WHERE a.category_id = ? AND a.id != ? AND a.status = 'published'
        ORDER BY a.published_at DESC LIMIT 3
    ");
    $relatedStmt->execute([$lang, $article['category_id'], $articleId]);
    $related = $relatedStmt->fetchAll();
    
    // Get approved comments
    $commentsStmt = $pdo->prepare("
        SELECT * FROM comments 
        WHERE article_id = ? AND status = 'approved' 
        ORDER BY created_at DESC
    ");
    $commentsStmt->execute([$articleId]);
    $comments = $commentsStmt->fetchAll();
    
    // Handle new comment submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_comment'])) {
        $name = htmlspecialchars($_POST['name'] ?? 'Anonymous');
        $email = htmlspecialchars($_POST['email'] ?? '');
        $content = htmlspecialchars($_POST['comment'] ?? '');
        
        if (!empty($content)) {
            $insertStmt = $pdo->prepare("INSERT INTO comments (article_id, user_name, email, content, status) VALUES (?, ?, ?, ?, 'pending')");
            $insertStmt->execute([$articleId, $name, $email, $content]);
            $commentMessage = "Thank you! Your comment has been submitted for moderation.";
        }
    }
    
} catch (Exception $e) {
    echo "<h1>Error loading article</h1>";
    echo "<p>Please try again later.</p>";
    exit;
}

$readingTime = ceil(strlen($article['body']) / 1000);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?> - The Daily Broadsheet</title>
    <meta name="description" content="<?= htmlspecialchars($article['meta_desc'] ?? $article['excerpt']) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($article['meta_title'] ?? $article['title']) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($article['excerpt']) ?>">
    <meta property="og:image" content="<?= $article['featured_image'] ?? '' ?>">
    <meta property="og:type" content="article">
    <meta name="twitter:card" content="summary_large_image">
    
    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NewsArticle",
        "headline": <?= json_encode($article['title']) ?>,
        "description": <?= json_encode($article['excerpt']) ?>,
        "image": <?= json_encode($article['featured_image']) ?>,
        "datePublished": <?= json_encode($article['published_at']) ?>,
        "dateModified": <?= json_encode($article['updated_at'] ?? $article['published_at']) ?>,
        "author": {
            "@type": "Person",
            "name": <?= json_encode($article['author_name']) ?>
        },
        "publisher": {
            "@type": "Organization",
            "name": "The Daily Broadsheet",
            "logo": {
                "@type": "ImageObject",
                "url": "/codes/daily-broadsheet/frontend/assets/images/logo.png"
            }
        },
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "<?= $_SERVER['REQUEST_URI'] ?>"
        }
    }
    </script>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@400;500;700&family=Lora:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/codes/daily-broadsheet/frontend/assets/css/main.css">
    <script src="https://zenithkandel.com.np/fontawesome/zenith-icons.js"></script>
    <style>
        .article-page {
            max-width: 800px;
            margin: 0 auto;
        }
        .article-header {
            margin-bottom: 2rem;
        }
        .article-category {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--accent);
            display: block;
            margin-bottom: 0.5rem;
        }
        .article-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        .article-meta {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            color: var(--ink-light);
        }
        .article-meta .author {
            font-weight: 600;
            color: var(--ink);
        }
        .article-meta .separator {
            margin: 0 0.5rem;
        }
        .featured-image {
            margin: 2rem 0;
        }
        .featured-image img {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: cover;
        }
        .article-body {
            font-size: 1.125rem;
            line-height: 1.8;
        }
        .article-body p {
            margin-bottom: 1.5rem;
        }
        .article-body h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            margin: 2rem 0 1rem;
        }
        .article-body h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            margin: 1.5rem 0 0.75rem;
        }
        .article-body blockquote {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 1.4rem;
            border-left: 4px solid var(--accent);
            padding-left: 1.5rem;
            margin: 2rem 0;
            color: var(--ink-light);
        }
        .share-buttons {
            display: flex;
            gap: 1rem;
            margin: 2rem 0;
            padding: 1rem 0;
            border-top: 1px solid var(--rule);
            border-bottom: 1px solid var(--rule);
        }
        .share-buttons a {
            padding: 0.5rem 1rem;
            background: var(--paper-dark);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
        }
        .related-articles {
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 3px solid var(--rule);
        }
        .related-title {
            font-family: 'DM Sans', sans-serif;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1.5rem;
        }
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        .related-item a {
            display: block;
        }
        .related-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            margin-bottom: 0.5rem;
        }
        .related-item h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
        }
    </style>
</head>
<body class="home-page">
    <?php include __DIR__ . '/../components/masthead.php'; ?>
    
    <div class="main-container">
        <?php include __DIR__ . '/../components/header.php'; ?>
        
        <main class="article-page">
            <article>
                <header class="article-header">
                    <a href="index.php?page=category&slug=<?= $article['category_slug'] ?>" class="article-category">
                        <?= htmlspecialchars($article['category_name']) ?>
                    </a>
                    <h1 class="article-title"><?= htmlspecialchars($article['title']) ?></h1>
                    <div class="article-meta">
                        <span class="author">By <?= htmlspecialchars($article['author_name']) ?></span>
                        <span class="separator">·</span>
                        <span><?= date('M d, Y', strtotime($article['published_at'])) ?></span>
                        <span class="separator">·</span>
                        <span><?= $readingTime ?> min read</span>
                    </div>
                </header>
                
                <?php if ($article['featured_image']): ?>
                <div class="featured-image">
                    <img src="<?= $article['featured_image'] ?>" alt="">
                </div>
                <?php endif; ?>
                
<div class="share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($_SERVER['REQUEST_URI']) ?>" target="_blank">Facebook</a>
                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode($_SERVER['REQUEST_URI']) ?>" target="_blank">Twitter</a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode($_SERVER['REQUEST_URI']) ?>" target="_blank">LinkedIn</a>
                    <a href="mailto:?subject=<?= urlencode($article['title']) ?>&body=<?= urlencode($_SERVER['REQUEST_URI']) ?>">Email</a>
                </div>
                
                <!-- Comments Section -->
                <section class="comments-section">
                    <h3 class="comments-title"><i class="fa-duotone fa-comments"></i> Comments (<?= count($comments) ?>)</h3>
                    
                    <?php if (isset($commentMessage)): ?>
                    <div class="comment-message"><?= htmlspecialchars($commentMessage) ?></div>
                    <?php endif; ?>
                    
                    <!-- Comment Form -->
                    <div class="comment-form">
                        <h4>Leave a Comment</h4>
                        <form method="POST">
                            <input type="hidden" name="new_comment" value="1">
                            <div class="form-row">
                                <input type="text" name="name" placeholder="Your Name" required>
                            </div>
                            <div class="form-row">
                                <input type="email" name="email" placeholder="Your Email (optional)">
                            </div>
                            <div class="form-row">
                                <textarea name="comment" placeholder="Write your comment..." rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Comment</button>
                        </form>
                    </div>
                    
                    <!-- Comments List -->
                    <div class="comments-list">
                        <?php foreach ($comments as $comment): ?>
                        <div class="comment-item">
                            <div class="comment-header">
                                <span class="comment-author"><?= htmlspecialchars($comment['user_name'] ?? 'Anonymous') ?></span>
                                <span class="comment-date"><?= timeAgo($comment['created_at']) ?></span>
                            </div>
                            <div class="comment-body">
                                <?= htmlspecialchars($comment['content']) ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($comments)): ?>
                        <p class="no-comments">No comments yet. Be the first to comment!</p>
                        <?php endif; ?>
                    </div>
                </section>
                
                <?php if (!empty($related)): ?>
                <section class="related-articles">
                    <h3 class="related-title">Related Articles</h3>
                    <div class="related-grid">
                        <?php foreach ($related as $item): ?>
                        <div class="related-item">
                            <a href="index.php?page=article&id=<?= $item['id'] ?>">
                                <img src="<?= $item['featured_image'] ?? '/codes/daily-broadsheet/frontend/assets/images/placeholder.jpg' ?>" alt="">
                                <h4><?= htmlspecialchars($item['title']) ?></h4>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>
            </article>
        </main>
        
        <?php include __DIR__ . '/../components/footer.php'; ?>
    </div>
    
    <script src="/codes/daily-broadsheet/frontend/assets/js/main.js"></script>
</body>
</html>