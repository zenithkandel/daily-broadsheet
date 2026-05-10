<?php
class AuthController {
    private $userModel;

    public function __construct(User $userModel) {
        $this->userModel = $userModel;
    }

    public function login() {
        $errors = [];
        $email = '';
        $password = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $errors[] = "Email and password are required";
            } else {
                $user = $this->userModel->verifyPassword($email, $password);
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['avatar'] = $user['avatar'] ?? null;
                    header('Location: index.php');
                    exit;
                } else {
                    $errors[] = "Invalid email or password";
                }
            }
        }

        include __DIR__ . '/../../admin/login.php';
    }

    public function logout() {
        session_destroy();
        header('Location: ../admin/login.php');
        exit;
    }

    public function requireAuth(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../admin/login.php');
            exit;
        }
    }
}

function handleAuth() {
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../models/User.php';
    
    $userModel = new User(db());
    $auth = new AuthController($userModel);
    
    $action = $_GET['action'] ?? 'login';
    
    match ($action) {
        'login' => $auth->login(),
        'logout' => $auth->logout(),
        default => $auth->login()
    };
}