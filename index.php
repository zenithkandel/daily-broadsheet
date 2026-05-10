<?php
require_once 'includes/functions.php';

$page = $_GET['page'] ?? 'home';
$id = $_GET['id'] ?? null;
$slug = $_GET['slug'] ?? null;
$category = $_GET['category'] ?? null;

$lang = $_GET['lang'] ?? 'en';
$_SESSION['lang'] = $lang;

switch ($page) {
    case 'article':
        include 'frontend/pages/article.php';
        break;
    case 'category':
        include 'frontend/pages/category.php';
        break;
    case 'author':
        include 'frontend/pages/author.php';
        break;
    case 'search':
        include 'frontend/pages/search.php';
        break;
    default:
        include 'frontend/pages/index.php';
}