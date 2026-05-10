<?php
require_once __DIR__ . '/../backend/config/database.php';

try {
    $pdo = Database::getInstance();
    
    // Test insert
    $stmt = $pdo->prepare("INSERT INTO article_media (article_id, type, filename) VALUES (0, 'image', 'media_library/images/test.jpg')");
    $stmt->execute();
    
    echo "Insert successful! ID: " . $pdo->lastInsertId();
    
    // Verify
    $count = $pdo->query("SELECT COUNT(*) FROM article_media")->fetchColumn();
    echo "<br>Total records: $count";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}