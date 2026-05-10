<footer class="site-footer">
    <div class="footer-grid">
        <div class="footer-section">
            <h4>The Daily Broadsheet</h4>
            <p>A nod to classic print newspapers reimagined for the web.</p>
        </div>
        <div class="footer-section">
            <h4>Categories</h4>
            <ul>
                <li><a href="index.php?page=category&slug=news">News</a></li>
                <li><a href="index.php?page=category&slug=politics">Politics</a></li>
                <li><a href="index.php?page=category&slug=sports">Sports</a></li>
                <li><a href="index.php?page=category&slug=business">Business</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Admin</h4>
            <ul>
                <li><a href="admin/login.php">Login</a></li>
                <li><a href="admin/index.php">Dashboard</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Newsletter</h4>
            <form class="newsletter-form">
                <input type="email" placeholder="Your email">
                <button type="submit">Subscribe</button>
            </form>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> The Daily Broadsheet. All rights reserved.</p>
    </div>
</footer>