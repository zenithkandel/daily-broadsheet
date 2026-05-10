<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../backend/config/database.php';

function db(): PDO {
    return Database::getInstance();
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isEditor(): bool {
    return isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'editor']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: ../admin/login.php');
        exit;
    }
}

function requireAdmin(): void {
    requireLogin();
    if (!isAdmin()) {
        die('Access denied');
    }
}

function requireEditor(): void {
    requireLogin();
    if (!isEditor()) {
        die('Access denied');
    }
}

function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function old(string $key): string {
    return $_POST[$key] ?? '';
}

function h(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function formatDate(string $date): string {
    return date('M d, Y', strtotime($date));
}

function timeAgo(string $date): string {
    $time = strtotime($date);
    $diff = time() - $time;
    
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . ' min ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 604800) return floor($diff / 86400) . ' days ago';
    return formatDate($date);
}

function generateSlug(string $text): string {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

function normalizeImagePath(?string $path): string {
    $baseUrl = '/codes/daily-broadsheet';
    $placeholder = $baseUrl . '/frontend/assets/images/placeholder.jpg';
    
    if (empty($path)) {
        return $placeholder;
    }
    
    if (preg_match('/^https?:\/\//', $path)) {
        return $path;
    }
    
    if (str_starts_with($path, '/')) {
        return $path;
    }
    
    if (str_starts_with($path, 'uploads/')) {
        return $baseUrl . '/' . $path;
    }
    
    if (str_starts_with($path, 'media_library/')) {
        return $baseUrl . '/uploads/' . $path;
    }
    
    return $baseUrl . '/' . $path;
}

function getBreakingNews(string $lang = 'en'): array {
    try {
        $pdo = db();
        $stmt = $pdo->prepare("
            SELECT a.id, ac.title 
            FROM articles a 
            JOIN article_content ac ON a.id = ac.article_id 
            WHERE a.status = 'published' AND a.breaking = 1 AND ac.lang = ?
            ORDER BY a.published_at DESC LIMIT 5
        ");
        $stmt->execute([$lang]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN | PDO::FETCH_UNIQUE);
    } catch (Exception $e) {
        return [];
    }
}

function adsEnabled(): bool {
    try {
        $pdo = db();
        $stmt = $pdo->query("SELECT setting_value FROM site_settings WHERE setting_key = 'show_ads'");
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] === '1' : true;
    } catch (Exception $e) {
        return true;
    }
}

function getAdSettings(): array {
    try {
        $pdo = db();
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings WHERE setting_key LIKE 'adsense_%'");
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    } catch (Exception $e) {
        return [];
    }
}