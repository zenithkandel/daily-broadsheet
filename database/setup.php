<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $sql = file_get_contents(__DIR__ . '/schema.sql');
    $pdo->exec($sql);

    echo "Database setup completed successfully!\n";
    echo "Database: daily_broadsheet\n";
    echo "Admin login: admin@news.com\n";
    echo "Password: 8038@Zenith\n";

} catch (PDOException $e) {
    die("Setup failed: " . $e->getMessage());
}