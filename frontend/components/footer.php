<footer class="site-footer">
    <div class="footer-grid">
        <div class="footer-section footer-about">
            <h4><i class="fa-duotone fa-newspaper"></i> The Daily Broadsheet</h4>
            <p>A nod to classic print newspapers reimagined for the web. Bringing you the latest news with a handcrafted aesthetic.</p>
            <div class="social-links">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
            </div>
        </div>
        <div class="footer-section">
            <h4><i class="fa-solid fa-folder"></i> Categories</h4>
            <ul>
                <li><a href="index.php?page=category&slug=news">News</a></li>
                <li><a href="index.php?page=category&slug=politics">Politics</a></li>
                <li><a href="index.php?page=category&slug=sports">Sports</a></li>
                <li><a href="index.php?page=category&slug=business">Business</a></li>
                <li><a href="index.php?page=category&slug=entertainment">Entertainment</a></li>
                <li><a href="index.php?page=category&slug=technology">Technology</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4><i class="fa-solid fa-link"></i> Quick Links</h4>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="admin/login.php"><i class="fa-solid fa-lock"></i> Admin Login</a></li>
                <li><a href="index.php?page=search">Search</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4><i class="fa-solid fa-envelope-open"></i> Newsletter</h4>
            <p>Get the latest news delivered to your inbox.</p>
            <form class="newsletter-form">
                <input type="email" placeholder="Your email address">
                <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="footer-bottom-content">
            <p>&copy; <?php echo date('Y'); ?> The Daily Broadsheet. All rights reserved.</p>
            <p class="made-with">Made with <i class="fa-solid fa-heart" style="color: var(--accent);"></i> for news lovers</p>
        </div>
    </div>
</footer>