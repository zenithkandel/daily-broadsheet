<?php
require_once __DIR__ . '/../backend/config/database.php';

try {
    $pdo = Database::getInstance();
    
    // Drop the foreign key
    $pdo->exec("ALTER TABLE article_media DROP FOREIGN KEY article_media_ibfk_1");
    
    // Change article_id to allow NULL
    $pdo->exec("ALTER TABLE article_media MODIFY COLUMN article_id INT NULL");
    
    // Re-add foreign key with NULL allowed
    $pdo->exec("ALTER TABLE article_media ADD CONSTRAINT article_media_ibfk_1 FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE");
    
    // Insert a test record with NULL
    $stmt = $pdo->prepare("INSERT INTO article_media (article_id, type, filename) VALUES (NULL, 'image', 'media_library/images/test.jpg')");
    $stmt->execute();
    
    echo "Success! Inserted with NULL article_id. ID: " . $pdo->lastInsertId();
    
    // Now update the media.php to use NULL instead of 0
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}