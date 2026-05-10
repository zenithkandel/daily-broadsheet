<?php
class AuthMiddleware {
    public static function handle(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../admin/login.php');
            exit;
        }
    }

    public static function handleAdmin(): void {
        self::handle();
        if (($_SESSION['role'] ?? '') !== 'admin') {
            die('Admin access required');
        }
    }

    public static function handleEditor(): void {
        self::handle();
        if (!in_array($_SESSION['role'] ?? '', ['admin', 'editor'])) {
            die('Editor access required');
        }
    }
}