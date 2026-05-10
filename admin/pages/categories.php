<?php
require_once '../includes/functions.php';
requireLogin();

$action = $_GET['action'] ?? 'list';

try {
    $pdo = db();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add'])) {
            $stmt = $pdo->prepare("INSERT INTO categories (slug, name_en, name_ne, color, sort_order) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                generateSlug($_POST['name_en']),
                $_POST['name_en'],
                $_POST['name_ne'] ?? '',
                $_POST['color'] ?? '#C84B31',
                $_POST['sort_order'] ?? 0
            ]);
        } elseif (isset($_POST['update']) && isset($_POST['id'])) {
            $stmt = $pdo->prepare("UPDATE categories SET name_en = ?, name_ne = ?, color = ?, sort_order = ? WHERE id = ?");
            $stmt->execute([
                $_POST['name_en'],
                $_POST['name_ne'] ?? '',
                $_POST['color'] ?? '#C84B31',
                $_POST['sort_order'] ?? 0,
                $_POST['id']
            ]);
        }
        header('Location: index.php?page=categories');
        exit;
    }
    
    if ($action === 'delete' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        header('Location: index.php?page=categories');
        exit;
    }
    
    $categories = $pdo->query("SELECT * FROM categories ORDER BY sort_order")->fetchAll();
    
} catch (Exception $e) {
    $categories = [];
}
?>
<header class="main-header">
    <h1>Categories</h1>
</header>

<div class="content-grid" style="grid-template-columns: 1fr 1fr;">
    <section class="content-card">
        <div class="card-header">
            <h2>All Categories</h2>
        </div>
        <div class="card-body">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Name (EN)</th>
                        <th>Name (NE)</th>
                        <th>Color</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= $cat['sort_order'] ?></td>
                        <td><?= htmlspecialchars($cat['name_en']) ?></td>
                        <td><?= htmlspecialchars($cat['name_ne'] ?? '') ?></td>
                        <td>
                            <span style="display: inline-block; width: 20px; height: 20px; background: <?= $cat['color'] ?>; border-radius: 3px;"></span>
                        </td>
                        <td>
                            <a href="index.php?page=categories&action=edit&id=<?= $cat['id'] ?>" class="btn-action btn-edit">Edit</a>
                            <a href="index.php?page=categories&action=delete&id=<?= $cat['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Delete category?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
    
    <section class="content-card">
        <div class="card-header">
            <h2><?= isset($_GET['action']) && $_GET['action'] === 'edit' ? 'Edit Category' : 'Add Category' ?></h2>
        </div>
        <div class="card-body">
            <?php
            $editCat = null;
            if ($action === 'edit' && isset($_GET['id'])) {
                foreach ($categories as $c) {
                    if ($c['id'] == $_GET['id']) {
                        $editCat = $c;
                        break;
                    }
                }
            }
            ?>
            <form method="POST">
                <?php if ($editCat): ?>
                <input type="hidden" name="id" value="<?= $editCat['id'] ?>">
                <input type="hidden" name="update" value="1">
                <?php else: ?>
                <input type="hidden" name="add" value="1">
                <?php endif; ?>
                
                <div class="form-row">
                    <label>Name (English)</label>
                    <input type="text" name="name_en" value="<?= htmlspecialchars($editCat['name_en'] ?? '') ?>" required>
                </div>
                
                <div class="form-row">
                    <label>Name (Nepali)</label>
                    <input type="text" name="name_ne" value="<?= htmlspecialchars($editCat['name_ne'] ?? '') ?>">
                </div>
                
                <div class="form-row">
                    <label>Color</label>
                    <input type="color" name="color" value="<?= $editCat['color'] ?? '#C84B31' ?>">
                </div>
                
                <div class="form-row">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="<?= $editCat['sort_order'] ?? 0 ?>">
                </div>
                
                <button type="submit" class="btn btn-primary"><?= $editCat ? 'Update' : 'Add' ?> Category</button>
                <?php if ($editCat): ?>
                <a href="index.php?page=categories" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
    </section>
</div>

<style>
.admin-table {
    width: 100%;
    border-collapse: collapse;
}
.admin-table th,
.admin-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid var(--rule);
}
.admin-table th {
    font-size: 0.85rem;
    color: var(--ink-light);
    font-weight: 600;
}
.form-row {
    margin-bottom: 1rem;
}
.form-row label {
    display: block;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--ink-light);
    margin-bottom: 0.375rem;
}
.form-row input[type="text"],
.form-row input[type="number"] {
    width: 100%;
    padding: 0.625rem;
    border: 1px solid var(--rule);
    font-family: 'DM Sans', sans-serif;
}
.form-row input[type="color"] {
    width: 50px;
    height: 35px;
    border: 1px solid var(--rule);
    padding: 0;
}
</style>