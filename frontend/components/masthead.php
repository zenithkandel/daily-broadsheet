<?php
$currentLang = $_SESSION['lang'] ?? 'en';
$currentPage = $_GET['page'] ?? 'home';
$currentId = $_GET['id'] ?? '';
$currentSlug = $_GET['slug'] ?? '';
$q = $_GET['q'] ?? '';

// Build current URL to preserve when switching language
$preserveParams = [];
if ($currentPage !== 'home' && $currentPage !== '') {
    $preserveParams['page'] = $currentPage;
}
if ($currentId) {
    $preserveParams['id'] = $currentId;
}
if ($currentSlug) {
    $preserveParams['slug'] = $currentSlug;
}
if ($q) {
    $preserveParams['q'] = $q;
}

// Build URLs for language switch
$enParams = array_merge($preserveParams, ['lang' => 'en']);
$neParams = array_merge($preserveParams, ['lang' => 'ne']);
$enUrl = '?' . http_build_query($enParams);
$neUrl = '?' . http_build_query($neParams);

// If no params, just use ?lang=
if (empty($preserveParams)) {
    $enUrl = '?lang=en';
    $neUrl = '?lang=ne';
}
?>
<div class="masthead-wrapper">
    <div class="masthead">
        <div class="masthead-left">
            <span class="date"><?php echo date('l, F j, Y'); ?></span>
        </div>
        <div class="masthead-center">
            <a href="index.php" class="masthead-logo">The Daily Broadsheet</a>
        </div>
        <div class="masthead-right">
            <div class="lang-switch">
                <a href="<?= $enUrl ?>" class="<?= $currentLang === 'en' ? 'active' : '' ?>">ENG</a>
                <span class="divider">/</span>
                <a href="<?= $neUrl ?>" class="<?= $currentLang === 'ne' ? 'active' : '' ?>">NEP</a>
            </div>
        </div>
    </div>
</div>