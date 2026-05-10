<?php $baseUrl = '/codes/daily-broadsheet'; ?>
<header class="site-header">
    <div class="header-top">
        <div class="breaking-ticker">
            <span class="ticker-label"><i class="fa-solid fa-bolt"></i> BREAKING</span>
            <span class="ticker-content">Global markets rally as tech stocks surge to new highs...</span>
        </div>
    </div>
    <nav class="main-nav">
        <button class="mobile-menu-toggle"><i class="fa-solid fa-bars"></i></button>
        <ul class="nav-list">
            <li><a href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
            <li><a href="index.php?page=category&slug=news"><i class="fa-duotone fa-newspaper"></i> News</a></li>
            <li><a href="index.php?page=category&slug=politics"><i class="fa-duotone fa-landmark"></i> Politics</a></li>
            <li><a href="index.php?page=category&slug=sports"><i class="fa-duotone fa-football"></i> Sports</a></li>
            <li><a href="index.php?page=category&slug=business"><i class="fa-duotone fa-chart-line-up"></i> Business</a></li>
            <li><a href="index.php?page=category&slug=entertainment"><i class="fa-duotone fa-film"></i> Entertainment</a></li>
            <li><a href="index.php?page=category&slug=technology"><i class="fa-duotone fa-microchip"></i> Technology</a></li>
        </ul>
        <div class="nav-right">
            <div class="nav-search">
                <form action="index.php" method="GET">
                    <input type="hidden" name="page" value="search">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <input type="text" name="q" placeholder="Search news...">
                </form>
            </div>
            <div class="theme-toggle">
                <button id="theme-toggle-btn" onclick="toggleTheme()" title="Toggle dark mode">
                    <i class="fa-solid fa-moon" id="theme-icon"></i>
                </button>
            </div>
        </div>
    </nav>
</header>

<style>
.nav-right {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.theme-toggle {
    display: flex;
    align-items: center;
}

#theme-toggle-btn {
    background: transparent;
    border: 1px solid var(--rule);
    padding: 0.5rem;
    border-radius: 50%;
    cursor: pointer;
    color: var(--ink);
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 1rem;
}

#theme-toggle-btn:hover {
    background: var(--paper-dark);
    color: var(--accent);
}

/* Dark mode styles */
:root {
    --animation-duration: 0.6s;
    --animation-easing: cubic-bezier(0.34, 1.56, 0.64, 1);
}

body.dark-mode {
    --paper: #1a1a1a;
    --paper-dark: #2a2a2a;
    --ink: #f5f5f5;
    --ink-light: #b0b0b0;
    --ink-faded: #707070;
    --rule: #404040;
    --dark-overlay: #0a0a0a;
}

body.dark-mode .site-header,
body.dark-mode .masthead,
body.dark-mode .article-card,
body.dark-mode .content-card,
body.dark-mode .stat-card,
body.dark-mode .sidebar-card {
    background: #242424 !important;
    border-color: #404040 !important;
}

body.dark-mode .masthead {
    background: #0a0a0a !important;
}

body.dark-mode .nav-search input,
body.dark-mode .form-group input,
body.dark-mode .form-group select,
body.dark-mode .form-group textarea {
    background: #2a2a2a;
    border-color: #404040;
    color: #f5f5f5;
}

body.dark-mode .btn-primary {
    background: #e55a3c;
}

body.dark-mode .lang-switch a {
    color: rgba(255,255,255,0.5);
}

body.dark-mode .lang-switch a.active {
    background: #e55a3c;
}

body.dark-mode .article-card {
    background: #242424;
}

body.dark-mode .site-footer {
    background: #0a0a0a;
}
</style>