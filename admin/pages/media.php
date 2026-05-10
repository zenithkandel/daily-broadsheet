<?php
require_once '../includes/functions.php';
requireLogin();

$message = '';
$uploadDir = __DIR__ . '/../../uploads/media_library/';

try {
    $pdo = db();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['file']['name'])) {
        $file = $_FILES['file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'webm', 'mp3', 'wav', 'pdf', 'doc', 'docx'];
        
        if (in_array($ext, $allowedExts)) {
            $type = 'document';
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) $type = 'image';
            elseif (in_array($ext, ['mp4', 'webm'])) $type = 'video';
            elseif (in_array($ext, ['mp3', 'wav'])) $type = 'audio';
            
            $filename = time() . '_' . basename($file['name']);
            $subdir = $type === 'image' ? 'images' : ($type === 'video' ? 'videos' : ($type === 'audio' ? 'audio' : 'documents'));
            $targetDir = $uploadDir . $subdir . '/';
            
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $targetPath = $targetDir . $filename;
            
            if (copy($file['tmp_name'], $targetPath)) {
                $dbPath = 'media_library/' . $subdir . '/' . $filename;
                try {
                    $stmt = $pdo->prepare("INSERT INTO article_media (article_id, type, filename) VALUES (NULL, ?, ?)");
                    $stmt->execute([$type, $dbPath]);
                    $message = 'File uploaded successfully! DB: ' . $type . ' - ' . $dbPath;
                } catch (Exception $e) {
                    $message = 'DB Error: ' . $e->getMessage();
                }
            } else {
                $message = 'Failed to save file.';
            }
        } else {
            $message = 'Invalid file type.';
        }
    }
    
    if (isset($_GET['delete'])) {
        $stmt = $pdo->prepare("SELECT filename FROM article_media WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $file = $stmt->fetch();
        if ($file) {
            $fullPath = __DIR__ . '/../../uploads/' . $file['filename'];
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
        $pdo->prepare("DELETE FROM article_media WHERE id = ?")->execute([$_GET['delete']]);
        header('Location: index.php?page=media');
        exit;
    }
    
    $media = $pdo->query("SELECT * FROM article_media WHERE article_id = 0 ORDER BY id DESC")->fetchAll();
    $typeFilter = $_GET['type'] ?? 'all';
    if ($typeFilter !== 'all') {
        $media = array_filter($media, fn($m) => $m['type'] === $typeFilter);
    }
    
} catch (Exception $e) {
    $media = [];
}

$typeFilter = $_GET['type'] ?? 'all';
?>
<header class="main-header">
    <h1>Media Library</h1>
</header>

<?php if ($message): ?>
<div class="alert alert-<?= strpos($message, 'success') !== false ? 'success' : 'error' ?>">
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<div class="upload-zone" style="border: 2px dashed var(--rule); padding: 2rem; text-align: center; margin-bottom: 2rem; background: var(--paper-dark);">
    <form method="POST" enctype="multipart/form-data" style="display: inline;">
        <input type="file" name="file" required style="display: inline;">
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
    <p style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--ink-faded);">
        Allowed: JPG, PNG, GIF, WebP, MP4, WebM, MP3, WAV, PDF, DOC
    </p>
</div>

<div class="filter-tabs" style="margin-bottom: 1.5rem;">
    <a href="index.php?page=media" class="filter-tab <?= $typeFilter === 'all' ? 'active' : '' ?>">All</a>
    <a href="index.php?page=media&type=image" class="filter-tab <?= $typeFilter === 'image' ? 'active' : '' ?>">Images</a>
    <a href="index.php?page=media&type=video" class="filter-tab <?= $typeFilter === 'video' ? 'active' : '' ?>">Videos</a>
    <a href="index.php?page=media&type=audio" class="filter-tab <?= $typeFilter === 'audio' ? 'active' : '' ?>">Audio</a>
    <a href="index.php?page=media&type=document" class="filter-tab <?= $typeFilter === 'document' ? 'active' : '' ?>">Documents</a>
</div>

<div class="media-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
    <?php foreach ($media as $item): ?>
    <div class="media-item" style="border: 1px solid var(--rule); padding: 0.5rem; background: #fff;">
        <?php if ($item['type'] === 'image'): ?>
        <img src="/codes/daily-broadsheet/uploads/<?= $item['filename'] ?>" style="width: 100%; height: 120px; object-fit: cover;">
        <?php elseif ($item['type'] === 'video'): ?>
        <div style="width: 100%; height: 120px; background: var(--dark-overlay); display: flex; align-items: center; justify-content: center; color: #fff;"><i class="fa-duotone fa-video"></i></div>
        <?php elseif ($item['type'] === 'audio'): ?>
        <div style="width: 100%; height: 120px; background: var(--paper-dark); display: flex; align-items: center; justify-content: center; color: var(--ink-light);"><i class="fa-duotone fa-music"></i></div>
        <?php else: ?>
        <div style="width: 100%; height: 120px; background: var(--paper-dark); display: flex; align-items: center; justify-content: center; color: var(--ink-light);"><i class="fa-duotone fa-file-lines"></i></div>
        <?php endif; ?>
        <div style="margin-top: 0.5rem; font-size: 0.75rem; word-break: break-all;">
            <?= htmlspecialchars(basename($item['filename'])) ?>
        </div>
        <a href="index.php?page=media&delete=<?= $item['id'] ?>" class="btn-action btn-delete" style="font-size: 0.7rem; padding: 0.25rem 0.5rem; margin-top: 0.25rem; display: inline-block;" onclick="return confirm('Delete this file?')">Delete</a>
    </div>
    <?php endforeach; ?>
    
    <?php if (empty($media)): ?>
    <p style="grid-column: 1 / -1; text-align: center; color: var(--ink-faded); padding: 2rem;">No media files uploaded yet.</p>
    <?php endif; ?>
</div>

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
.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 4px;
}
.alert-success {
    background: #efe;
    border: 1px solid #cfc;
    color: #363;
}
.alert-error {
    background: #fee;
    border: 1px solid #fcc;
    color: #633;
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
.btn-delete {
    border-color: var(--danger);
    color: var(--danger);
}
</style>