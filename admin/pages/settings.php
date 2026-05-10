<?php
require_once '../includes/functions.php';
requireAdmin();

$message = '';

try {
    $pdo = db();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach ($_POST as $key => $value) {
            $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
            $stmt->execute([$key, $value, $value]);
        }
        
        if (!isset($_POST['show_ads'])) {
            $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES ('show_ads', '0') ON DUPLICATE KEY UPDATE setting_value = '0'");
            $stmt->execute();
        }
        
        $message = 'Settings saved successfully!';
    }
    
    $settings = $pdo->query("SELECT setting_key, setting_value FROM site_settings")->fetchAll(PDO::FETCH_KEY_PAIR);
    
} catch (Exception $e) {
    $settings = [];
}

$defaults = [
    'site_name' => 'The Daily Broadsheet',
    'site_tagline' => 'A nod to classic print newspapers reimagined for the web',
    'site_email' => 'editor@dailybroadsheet.com',
    'default_language' => 'en',
    'show_ads' => '1',
    'adsense_client_id' => '',
    'adsense_ad_slot_728x90' => '',
    'adsense_ad_slot_300x250' => '',
    'facebook_url' => '',
    'twitter_url' => '',
    'youtube_url' => ''
];

$settings = array_merge($defaults, $settings);
?>
<header class="main-header">
    <h1>Settings</h1>
</header>

<?php if ($message): ?>
<div class="alert alert-success">
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<form method="POST">
    <div class="settings-grid">
        <section class="content-card">
            <div class="card-header">
                <h2>General Settings</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <label>Site Name</label>
                    <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name']) ?>">
                </div>
                
                <div class="form-row">
                    <label>Tagline</label>
                    <input type="text" name="site_tagline" value="<?= htmlspecialchars($settings['site_tagline']) ?>">
                </div>
                
                <div class="form-row">
                    <label>Contact Email</label>
                    <input type="email" name="site_email" value="<?= htmlspecialchars($settings['site_email']) ?>">
                </div>
                
                <div class="form-row">
                    <label>Default Language</label>
                    <select name="default_language">
                        <option value="en" <?= $settings['default_language'] === 'en' ? 'selected' : '' ?>>English</option>
                        <option value="ne" <?= $settings['default_language'] === 'ne' ? 'selected' : '' ?>>Nepali</option>
                    </select>
                </div>
            </div>
        </section>
        
        <section class="content-card">
            <div class="card-header">
                <h2>AdSense Settings</h2>
            </div>
            <div class="card-body">
                <div class="form-row toggle-row">
                    <label>Show Ads</label>
                    <div class="toggle-switch">
                        <input type="checkbox" name="show_ads" id="show_ads" value="1" <?= ($settings['show_ads'] ?? '1') === '1' ? 'checked' : '' ?>>
                        <label for="show_ads" class="toggle-label"></label>
                    </div>
                </div>
                
                <div class="form-row">
                    <label>AdSense Client ID (ca-pub-...)</label>
                    <input type="text" name="adsense_client_id" value="<?= htmlspecialchars($settings['adsense_client_id']) ?>" placeholder="ca-pub-xxxxxxxxxxxxxxxx">
                </div>
                
                <div class="form-row">
                    <label>Leaderboard Ad Slot (728x90)</label>
                    <input type="text" name="adsense_ad_slot_728x90" value="<?= htmlspecialchars($settings['adsense_ad_slot_728x90']) ?>">
                </div>
                
                <div class="form-row">
                    <label>Rectangle Ad Slot (300x250)</label>
                    <input type="text" name="adsense_ad_slot_300x250" value="<?= htmlspecialchars($settings['adsense_ad_slot_300x250']) ?>">
                </div>
                
                <p style="font-size: 0.8rem; color: var(--ink-faded); margin-top: 1rem;">
                    AdSense ads will only appear on the frontend after you configure these settings.
                </p>
            </div>
        </section>
        
        <section class="content-card">
            <div class="card-header">
                <h2>Social Media</h2>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <label>Facebook URL</label>
                    <input type="url" name="facebook_url" value="<?= htmlspecialchars($settings['facebook_url']) ?>" placeholder="https://facebook.com/...">
                </div>
                
                <div class="form-row">
                    <label>Twitter URL</label>
                    <input type="url" name="twitter_url" value="<?= htmlspecialchars($settings['twitter_url']) ?>" placeholder="https://twitter.com/...">
                </div>
                
                <div class="form-row">
                    <label>YouTube URL</label>
                    <input type="url" name="youtube_url" value="<?= htmlspecialchars($settings['youtube_url']) ?>" placeholder="https://youtube.com/...">
                </div>
            </div>
        </section>
    </div>
    
    <div class="settings-actions">
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </div>
</form>