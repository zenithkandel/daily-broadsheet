<?php
require_once '../backend/config/database.php';

$hash = '$2y$12$MAVvhivnNKhPyoY3fBaSJufPNc/HjiEFIHT1pUZk/64QtxIONWwmW';

try {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = 'admin@news.com'");
    $stmt->execute([$hash]);
    echo "Admin password updated successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}