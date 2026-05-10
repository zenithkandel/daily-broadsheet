<div class="masthead">
    <div class="masthead-left">
        <span class="volume"><i class="fa-duotone fa-book-open"></i> Vol. 1</span>
        <span class="edition">Issue 42</span>
    </div>
    <div class="masthead-center">
        <a href="index.php" class="masthead-logo">The Daily Broadsheet</a>
        <span class="tagline">A nod to classic print newspapers reimagined for the web</span>
    </div>
    <div class="masthead-right">
        <span class="weather"><i class="fa-duotone fa-sun"></i> 22°C Kathmandu</span>
        <span class="date"><?php echo date('F j, Y'); ?></span>
        <div class="lang-switch">
            <a href="?lang=en" class="<?= ($_SESSION['lang'] ?? 'en') === 'en' ? 'active' : '' ?>">EN</a>
            <span class="divider">|</span>
            <a href="?lang=ne" class="<?= ($_SESSION['lang'] ?? 'en') === 'ne' ? 'active' : '' ?>">NE</a>
        </div>
    </div>
</div>