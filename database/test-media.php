<?php
require_once __DIR__ . '/../backend/config/database.php';

try {
    $pdo = Database::getInstance();
    
    // Check table exists
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables: " . implode(', ', $tables) . "<br><br>";
    
    // Check article_media
    $count = $pdo->query("SELECT COUNT(*) FROM article_media")->fetchColumn();
    echo "article_media count: $count<br><br>";
    
    if ($count > 0) {
        $media = $pdo->query("SELECT * FROM article_media ORDER BY id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($media);
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}