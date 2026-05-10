<?php
require_once __DIR__ . '/../backend/config/database.php';

$hash = password_hash('8038@Zenith', PASSWORD_DEFAULT);
echo "Hash: " . $hash . "<br>";

try {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = 'admin@news.com'");
    $stmt->execute([$hash]);
    echo "Admin password updated successfully!";
    
    // Verify
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = 'admin@news.com'");
    $stmt->execute();
    $user = $stmt->fetch();
    echo "User found: " . ($user ? 'Yes' : 'No') . "<br>";
    if ($user) {
        echo "Password verification: " . (password_verify('8038@Zenith', $user['password_hash']) ? 'PASS' : 'FAIL');
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}