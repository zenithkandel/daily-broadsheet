<?php
require_once '../includes/functions.php';
requireLogin();

$articleId = $_GET['id'] ?? 'new';
$lang = $_SESSION['lang'] ?? 'en';

$categories = [];
$authors = [];
$article = [
    'slug' => '',
    'author_id' => $_SESSION['user_id'] ?? 1,
    'category_id' => 1,
    'status' => 'draft',
    'featured' => 0,
    'featured_image' => '',
    'video_url' => '',
    'audio_url' => '',
    'scheduled_at' => ''
];
$content = [
    'en' => ['title' => '', 'excerpt' => '', 'body' => '', 'meta_title' => '', 'meta_desc' => ''],
    'ne' => ['title' => '', 'excerpt' => '', 'body' => '', 'meta_title' => '', 'meta_desc' => '']
];

try {
    $pdo = db();
    
    $categories = $pdo->query("SELECT * FROM categories ORDER BY sort_order")->fetchAll();
    $authors = $pdo->query("SELECT id, name FROM users ORDER BY name")->fetchAll();
    
    if ($articleId !== 'new') {
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$articleId]);
        $article = array_merge($article, $stmt->fetch() ?: []);
        
        $contentStmt = $pdo->prepare("SELECT * FROM article_content WHERE article_id = ?");
        $contentStmt->execute([$articleId]);
        $contents = $contentStmt->fetchAll();
        
        foreach ($contents as $c) {
            if (isset($content[$c['lang']])) {
                $content[$c['lang']] = $c;
            }
        }
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $articleData = [
            'slug' => generateSlug($_POST['title_en'] ?: 'article'),
            'author_id' => $_POST['author_id'],
            'category_id' => $_POST['category_id'],
            'status' => $_POST['status'],
            'featured' => isset($_POST['featured']) ? 1 : 0,
            'featured_image' => $_POST['featured_image'] ?? '',
            'video_url' => $_POST['video_url'] ?? '',
            'audio_url' => $_POST['audio_url'] ?? '',
            'scheduled_at' => $_POST['scheduled_at'] ?: null,
            'published_at' => $_POST['status'] === 'published' ? date('Y-m-d H:i:s') : null
        ];
        
        if ($articleId === 'new') {
            $stmt = $pdo->prepare("INSERT INTO articles (slug, author_id, category_id, status, featured, featured_image, video_url, audio_url, scheduled_at, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $articleData['slug'], $articleData['author_id'], $articleData['category_id'],
                $articleData['status'], $articleData['featured'], $articleData['featured_image'],
                $articleData['video_url'], $articleData['audio_url'], $articleData['scheduled_at'],
                $articleData['published_at']
            ]);
            $articleId = $pdo->lastInsertId();
        } else {
            $stmt = $pdo->prepare("UPDATE articles SET slug=?, author_id=?, category_id=?, status=?, featured=?, featured_image=?, video_url=?, audio_url=?, scheduled_at=?, published_at=? WHERE id=?");
            $stmt->execute([
                $articleData['slug'], $articleData['author_id'], $articleData['category_id'],
                $articleData['status'], $articleData['featured'], $articleData['featured_image'],
                $articleData['video_url'], $articleData['audio_url'], $articleData['scheduled_at'],
                $articleData['published_at'], $articleId
            ]);
        }
        
        foreach (['en', 'ne'] as $langCode) {
            $titleKey = "title_$langCode";
            $excerptKey = "excerpt_$langCode";
            $bodyKey = "body_$langCode";
            $metaTitleKey = "meta_title_$langCode";
            $metaDescKey = "meta_desc_$langCode";
            
            $checkStmt = $pdo->prepare("SELECT id FROM article_content WHERE article_id = ? AND lang = ?");
            $checkStmt->execute([$articleId, $langCode]);
            
            if ($checkStmt->fetch()) {
                $updStmt = $pdo->prepare("UPDATE article_content SET title=?, excerpt=?, body=?, meta_title=?, meta_desc=? WHERE article_id=? AND lang=?");
                $updStmt->execute([
                    $_POST[$titleKey] ?? '', $_POST[$excerptKey] ?? '', $_POST[$bodyKey] ?? '',
                    $_POST[$metaTitleKey] ?? '', $_POST[$metaDescKey] ?? '', $articleId, $langCode
                ]);
            } else {
                $insStmt = $pdo->prepare("INSERT INTO article_content (article_id, lang, title, excerpt, body, meta_title, meta_desc) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $insStmt->execute([
                    $articleId, $langCode, $_POST[$titleKey] ?? '', $_POST[$excerptKey] ?? '',
                    $_POST[$bodyKey] ?? '', $_POST[$metaTitleKey] ?? '', $_POST[$metaDescKey] ?? ''
                ]);
            }
        }
        
        header('Location: index.php?page=articles');
        exit;
    }
    
} catch (Exception $e) {
    $error = $e->getMessage();
}

$pageTitle = $articleId === 'new' ? 'New Article' : 'Edit Article';
?>

<header class="main-header">
    <h1><?= $pageTitle ?></h1>
    <a href="index.php?page=articles" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Articles</a>
</header>

<?php if (isset($error)): ?>
<div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" class="editor-container">
    <div class="editor-main">
        <div class="lang-tabs">
            <div class="lang-tab active" data-lang="en">English</div>
            <div class="lang-tab" data-lang="ne">Nepali</div>
        </div>
        
        <?php foreach (['en', 'ne'] as $langCode): ?>
        <div class="lang-panel <?= $langCode === 'en' ? 'active' : '' ?>" data-lang="<?= $langCode ?>">
            <div class="form-row">
                <label>Title (<?= strtoupper($langCode) ?>)</label>
                <input type="text" name="title_<?= $langCode ?>" value="<?= htmlspecialchars($content[$langCode]['title'] ?? '') ?>" required>
            </div>
            
            <div class="form-row">
                <label>Excerpt</label>
                <div class="excerpt-helper">
                    <textarea name="excerpt_<?= $langCode ?>" rows="3"><?= htmlspecialchars($content[$langCode]['excerpt'] ?? '') ?></textarea>
                    <button type="button" class="btn-auto-excerpt" onclick="autoExcerpt('<?= $langCode ?>')">Auto</button>
                </div>
            </div>
            
            <div class="form-row">
                <label>Body Content</label>
                <div class="editor-wrapper">
                    <div id="editor-<?= $langCode ?>"><?= $content[$langCode]['body'] ?? '' ?></div>
                </div>
                <input type="hidden" name="body_<?= $langCode ?>" id="body-<?= $langCode ?>">
            </div>
            
            <div class="form-row">
                <label>SEO Title (<?= strtoupper($langCode) ?>)</label>
                <input type="text" name="meta_title_<?= $langCode ?>" value="<?= htmlspecialchars($content[$langCode]['meta_title'] ?? '') ?>" placeholder="Leave empty to use article title">
            </div>
            
            <div class="form-row">
                <label>SEO Description (<?= strtoupper($langCode) ?>)</label>
                <textarea name="meta_desc_<?= $langCode ?>" rows="2" placeholder="150-160 characters"><?= htmlspecialchars($content[$langCode]['meta_desc'] ?? '') ?></textarea>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="editor-sidebar">
        <div class="sidebar-card">
            <h3><i class="fa-duotone fa-paper-plane-top"></i> Publishing</h3>
            
            <div class="form-row">
                <label>Status</label>
                <select name="status">
                    <option value="draft" <?= $article['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= $article['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                    <option value="scheduled" <?= $article['status'] === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                </select>
            </div>
            
            <div class="form-row">
                <label>Schedule Date</label>
                <input type="datetime-local" name="scheduled_at" value="<?= $article['scheduled_at'] ? date('Y-m-d\TH:i', strtotime($article['scheduled_at'])) : '' ?>">
            </div>
            
            <div class="checkbox-row">
                <input type="checkbox" name="featured" id="featured" <?= $article['featured'] ? 'checked' : '' ?>>
                <label for="featured">Featured Article</label>
            </div>
        </div>
        
        <div class="sidebar-card">
            <h3><i class="fa-duotone fa-info-circle"></i> Details</h3>
            
            <div class="form-row">
                <label>Category</label>
                <select name="category_id">
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $article['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name_en']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-row">
                <label>Author</label>
                <select name="author_id">
                    <?php foreach ($authors as $auth): ?>
                    <option value="<?= $auth['id'] ?>" <?= $article['author_id'] == $auth['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($auth['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-row">
                <label>Featured Image URL</label>
                <input type="url" name="featured_image" value="<?= htmlspecialchars($article['featured_image'] ?? '') ?>" placeholder="https://...">
            </div>
        </div>
        
        <div class="sidebar-card">
            <h3><i class="fa-duotone fa-video"></i> Media</h3>
            
            <div class="form-row">
                <label>Video URL (YouTube/Vimeo)</label>
                <input type="url" name="video_url" value="<?= htmlspecialchars($article['video_url'] ?? '') ?>" placeholder="https://youtube.com/...">
            </div>
            
            <div class="form-row">
                <label>Audio URL</label>
                <input type="url" name="audio_url" value="<?= htmlspecialchars($article['audio_url'] ?? '') ?>" placeholder="https://...">
            </div>
        </div>
        
        <div class="btn-group">
            <button type="submit" class="btn-save"><i class="fa-solid fa-save"></i> Save Article</button>
            <a href="index.php?page=articles" class="btn-cancel">Cancel</a>
        </div>
    </div>
</form>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    const quillEn = new Quill('#editor-en', {
        theme: 'snow',
        placeholder: 'Write your article content here...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'image', 'video'],
                ['clean']
            ]
        }
    });
    
    const quillNe = new Quill('#editor-ne', {
        theme: 'snow',
        placeholder: 'Write your article content here...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'image', 'video'],
                ['clean']
            ]
        }
    });
    
    document.querySelector('form').onsubmit = function() {
        document.getElementById('body-en').value = quillEn.root.innerHTML;
        document.getElementById('body-ne').value = quillNe.root.innerHTML;
    };
    
    document.querySelectorAll('.lang-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.lang-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.lang-panel').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.querySelector('.lang-panel[data-lang="' + this.dataset.lang + '"]').classList.add('active');
        });
    });
    
    function autoExcerpt(lang) {
        const editor = lang === 'en' ? quillEn : quillNe;
        const text = editor.getText();
        const sentences = text.split(/[.!?]+/).filter(s => s.trim().length > 20);
        const excerpt = sentences.slice(0, 2).join('. ').substring(0, 160);
        document.querySelector(`textarea[name="excerpt_${lang}"]`).value = excerpt;
    }
</script>

<style>
.editor-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}
.editor-main {
    background: #fff;
    border: 1px solid var(--rule);
    padding: 1.5rem;
}
.editor-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.sidebar-card {
    background: #fff;
    border: 1px solid var(--rule);
    padding: 1.25rem;
}
.sidebar-card h3 {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--rule);
}
.form-row {
    margin-bottom: 1rem;
}
.form-row label {
    display: block;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--ink-light);
    margin-bottom: 0.375rem;
}
.form-row input,
.form-row select,
.form-row textarea {
    width: 100%;
    padding: 0.625rem;
    border: 1px solid var(--rule);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    background: var(--paper);
}
.form-row input:focus,
.form-row select:focus,
.form-row textarea:focus {
    outline: none;
    border-color: var(--accent);
}
.form-row textarea {
    resize: vertical;
    min-height: 80px;
}
.checkbox-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.checkbox-row input {
    width: auto;
}
.lang-tabs {
    display: flex;
    gap: 0;
    margin-bottom: 1.5rem;
    border: 1px solid var(--rule);
}
.lang-tab {
    flex: 1;
    padding: 0.75rem;
    text-align: center;
    background: var(--paper);
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
}
.lang-tab.active {
    background: #fff;
    border-bottom: 2px solid var(--accent);
}
.lang-panel {
    display: none;
}
.lang-panel.active {
    display: block;
}
.editor-wrapper {
    height: 300px;
    margin-bottom: 1rem;
}
.editor-wrapper .ql-container {
    height: 250px;
}
.btn-group {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--rule);
}
.btn-save {
    padding: 0.75rem 1.5rem;
    background: var(--accent);
    color: #fff;
    border: none;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    border-radius: 4px;
}
.btn-save:hover {
    background: var(--accent-dark);
}
.btn-cancel {
    padding: 0.75rem 1.5rem;
    background: transparent;
    color: var(--ink);
    border: 1px solid var(--rule);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    cursor: pointer;
    border-radius: 4px;
    text-decoration: none;
}
.btn-cancel:hover {
    background: var(--paper-dark);
}
.excerpt-helper {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    margin-top: 0.5rem;
}
.excerpt-helper textarea {
    flex: 1;
}
.btn-auto-excerpt {
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
    background: var(--paper-dark);
    border: 1px solid var(--rule);
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    margin-top: 0.5rem;
}
.btn-auto-excerpt:hover {
    background: var(--rule);
}
.main-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}
.main-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 1.75rem;
}
.alert-error {
    background: #fee;
    border: 1px solid #fcc;
    color: #633;
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 4px;
}
</style>