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
<div class="masthead">
    <div class="masthead-left">
        <span class="volume"><i class="fa-duotone fa-book-open"></i> Vol. 1</span>
        <span class="edition">Issue 42</span>
    </div>
    <div class="masthead-center">
        <a href="index.php" class="masthead-logo">The Daily Broadsheet</a>
    </div>
    <div class="masthead-right">
        <span class="date"><?php echo date('F j, Y'); ?></span>
        <div class="lang-switch">
            <a href="<?= $enUrl ?>" class="<?= $currentLang === 'en' ? 'active' : '' ?>">EN</a>
            <span class="divider">|</span>
            <a href="<?= $neUrl ?>" class="<?= $currentLang === 'ne' ? 'active' : '' ?>">NE</a>
        </div>
    </div>
</div>