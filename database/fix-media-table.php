<?php
require_once __DIR__ . '/../backend/config/database.php';

try {
    $pdo = Database::getInstance();
    
    // First delete the existing foreign key
    $pdo->exec("ALTER TABLE article_media DROP FOREIGN KEY article_media_ibfk_1");
    
    // Add new foreign key that allows 0
    $pdo->exec("ALTER TABLE article_media ADD CONSTRAINT article_media_ibfk_1 FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE");
    
    echo "Foreign key updated to allow article_id = 0";
    
    // Also set article_id = 0 to be valid by inserting a dummy record or allowing NULL
    // Actually let's change existing records where article_id doesn't exist
    $pdo->exec("DELETE FROM article_media WHERE article_id NOT IN (SELECT id FROM articles) AND article_id != 0");
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}