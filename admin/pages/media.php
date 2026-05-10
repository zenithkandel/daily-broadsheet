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
                    $message = 'File uploaded successfully!';
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
    
    $media = $pdo->query("SELECT * FROM article_media WHERE article_id IS NULL OR article_id = 0 ORDER BY id DESC")->fetchAll();
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

<div class="upload-zone">
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
    <p class="upload-hint">Allowed: JPG, PNG, GIF, WebP, MP4, WebM, MP3, WAV, PDF, DOC</p>
</div>

<div class="filter-tabs">
    <a href="index.php?page=media" class="filter-tab <?= $typeFilter === 'all' ? 'active' : '' ?>">All</a>
    <a href="index.php?page=media&type=image" class="filter-tab <?= $typeFilter === 'image' ? 'active' : '' ?>">Images</a>
    <a href="index.php?page=media&type=video" class="filter-tab <?= $typeFilter === 'video' ? 'active' : '' ?>">Videos</a>
    <a href="index.php?page=media&type=audio" class="filter-tab <?= $typeFilter === 'audio' ? 'active' : '' ?>">Audio</a>
    <a href="index.php?page=media&type=document" class="filter-tab <?= $typeFilter === 'document' ? 'active' : '' ?>">Documents</a>
</div>

<div class="media-grid">
    <?php foreach ($media as $item): ?>
    <div class="media-item">
        <?php if ($item['type'] === 'image'): ?>
        <img src="/codes/daily-broadsheet/uploads/<?= $item['filename'] ?>" alt="">
        <?php elseif ($item['type'] === 'video'): ?>
        <div class="media-placeholder media-video"><i class="fa-duotone fa-video"></i></div>
        <?php elseif ($item['type'] === 'audio'): ?>
        <div class="media-placeholder media-audio"><i class="fa-duotone fa-music"></i></div>
        <?php else: ?>
        <div class="media-placeholder media-doc"><i class="fa-duotone fa-file-lines"></i></div>
        <?php endif; ?>
        <span class="filename"><?= htmlspecialchars(basename($item['filename'])) ?></span>
        <div class="media-actions">
            <button onclick="copyUrl('/codes/daily-broadsheet/uploads/<?= $item['filename'] ?>')" class="btn btn-small btn-secondary">Copy</button>
            <a href="index.php?page=media&delete=<?= $item['id'] ?>" class="btn btn-small btn-danger" onclick="return confirm('Delete this file?')">Delete</a>
        </div>
    </div>
    <?php endforeach; ?>
    
    <?php if (empty($media)): ?>
    <div class="empty-state">
        <p>No media files uploaded yet.</p>
    </div>
    <?php endif; ?>
</div>

<script>
function copyUrl(url) {
    navigator.clipboard.writeText(url).then(function() {
        alert('URL copied to clipboard!');
    }, function() {
        prompt('Copy this URL:', url);
    });
}
</script>