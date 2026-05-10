<?php
session_start();

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