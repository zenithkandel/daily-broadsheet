<?php
require_once __DIR__ . '/../../includes/functions.php';
$lang = $_SESSION['lang'] ?? 'en';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - The Daily Broadsheet</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@400;500;700&family=Lora:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <script src="https://zenithkandel.com.np/fontawesome/zenith-icons.js"></script>
    <style>
        .error-page {
            min-height: 60vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 4rem 2rem;
        }
        .error-code {
            font-family: 'Playfair Display', serif;
            font-size: 8rem;
            font-weight: 700;
            color: var(--accent);
            line-height: 1;
            margin-bottom: 1rem;
            opacity: 0.3;
        }
        .error-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .error-message {
            color: var(--ink-light);
            max-width: 500px;
            margin-bottom: 2rem;
        }
        .error-actions {
            display: flex;
            gap: 1rem;
        }
        .btn-home {
            padding: 0.875rem 2rem;
            background: var(--accent);
            color: #fff;
            text-decoration: none;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            border-radius: 4px;
        }
        .btn-home:hover {
            background: var(--accent-dark);
        }
        .btn-back {
            padding: 0.875rem 2rem;
            background: transparent;
            color: var(--ink);
            border: 1px solid var(--rule);
            text-decoration: none;
            font-family: 'DM Sans', sans-serif;
            border-radius: 4px;
        }
        .btn-back:hover {
            background: var(--paper-dark);
        }
    </style>
</head>
<body class="home-page">
    <?php include __DIR__ . '/../components/masthead.php'; ?>
    <div class="main-container">
        <?php include __DIR__ . '/../components/header.php'; ?>
        
        <main class="content-wrapper">
            <div class="error-page">
                <div class="error-code">404</div>
                <h1 class="error-title">Page Not Found</h1>
                <p class="error-message">
                    The page you're looking for has been moved, deleted, or never existed. 
                    Try searching for what you need or go back to the homepage.
                </p>
                <div class="error-actions">
                    <a href="index.php" class="btn-home"><i class="fa-solid fa-house"></i> Back to Home</a>
                    <a href="javascript:history.back()" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Go Back</a>
                </div>
            </div>
        </main>
        
        <?php include __DIR__ . '/../components/footer.php'; ?>
    </div>
</body>
</html>