<div class="masthead">
    <div class="masthead-left">
        <span class="volume">Vol. 1</span>
    </div>
    <div class="masthead-center">
        <a href="index.php" class="masthead-logo">The Daily Broadsheet</a>
    </div>
    <div class="masthead-right">
        <span class="date"><?php echo date('F j, Y'); ?></span>
        <div class="lang-switch">
            <a href="?lang=en" class="<?= ($_SESSION['lang'] ?? 'en') === 'en' ? 'active' : '' ?>">EN</a>
            <span class="divider">|</span>
            <a href="?lang=ne" class="<?= ($_SESSION['lang'] ?? 'en') === 'ne' ? 'active' : '' ?>">NE</a>
        </div>
    </div>
</div>