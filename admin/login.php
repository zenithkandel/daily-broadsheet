<?php
session_start();
$pageTitle = 'Login - The Daily Broadsheet Admin';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../includes/functions.php';
    require_once '../backend/config/database.php';
    require_once '../backend/models/User.php';
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $errors = [];
    
    if (empty($email) || empty($password)) {
        $errors[] = "Email and password are required";
    } else {
        $userModel = new User(db());
        $user = $userModel->verifyPassword($email, $password);
        if ($user) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php');
            exit;
        } else {
            $errors[] = "Invalid email or password";
        }
    }
} else {
    session_start();
    if (isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://zenithkandel.com.np/fontawesome/zenith-icons.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --paper: #F5F0E8;
            --paper-dark: #EDE8DF;
            --ink: #1A1A1A;
            --ink-light: #4A4A4A;
            --accent: #C84B31;
            --accent-dark: #9A3623;
            --rule: #D4CFC7;
        }
        
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--paper);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
        }
        
        .login-container {
            background: #fff;
            padding: 3rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 
                0 4px 6px rgba(0,0,0,0.05),
                0 10px 20px rgba(0,0,0,0.08);
            border: 1px solid var(--rule);
            position: relative;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--accent);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            color: var(--ink);
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: var(--ink-light);
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--ink-light);
            margin-bottom: 0.5rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid var(--rule);
            font-size: 1rem;
            font-family: 'DM Sans', sans-serif;
            background: var(--paper);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(200, 75, 49, 0.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: var(--accent);
            color: #fff;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 0.5rem;
        }
        
        .btn-login:hover {
            background: var(--accent-dark);
        }
        
        .error-message {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }
        
        .back-link a {
            color: var(--ink-light);
            text-decoration: none;
        }
        
        .back-link a:hover {
            color: var(--accent);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>The Daily Broadsheet</h1>
            <p>Admin Portal</p>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-login">Sign In</button>
        </form>
        
        <div class="back-link">
            <a href="../index.php">&larr; Back to Website</a>
        </div>
    </div>
</body>
</html>