<?php
require_once '../includes/functions.php';
requireAdmin();

$message = '';

try {
    $pdo = db();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role, bio) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['name'], $_POST['email'], $password, $_POST['role'], $_POST['bio'] ?? '']);
            $message = 'User created successfully!';
        } elseif (isset($_POST['update']) && isset($_POST['id'])) {
            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ?, bio = ?, password_hash = ? WHERE id = ?");
                $stmt->execute([$_POST['name'], $_POST['email'], $_POST['role'], $_POST['bio'] ?? '', $password, $_POST['id']]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ?, bio = ? WHERE id = ?");
                $stmt->execute([$_POST['name'], $_POST['email'], $_POST['role'], $_POST['bio'] ?? '', $_POST['id']]);
            }
            $message = 'User updated successfully!';
        }
    }
    
    if (isset($_GET['delete'])) {
        if ($_GET['delete'] == $_SESSION['user_id']) {
            $message = 'You cannot delete yourself.';
        } else {
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$_GET['delete']]);
            $message = 'User deleted.';
        }
    }
    
    $users = $pdo->query("SELECT id, name, email, role, bio, created_at FROM users ORDER BY created_at DESC")->fetchAll();
    
} catch (Exception $e) {
    $users = [];
}

$editUser = null;
if (isset($_GET['edit'])) {
    foreach ($users as $u) {
        if ($u['id'] == $_GET['edit']) {
            $editUser = $u;
            break;
        }
    }
}
?>
<header class="main-header">
    <h1>Users</h1>
</header>

<?php if ($message): ?>
<div class="alert <?= strpos($message, 'success') !== false ? 'success' : 'error' ?>">
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<div class="content-grid" style="grid-template-columns: 1fr 1fr;">
    <section class="content-card">
        <div class="card-header">
            <h2>All Users</h2>
        </div>
        <div class="card-body">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><span class="status status-<?= $user['role'] ?>"><?= $user['role'] ?></span></td>
                        <td>
                            <a href="index.php?page=users&edit=<?= $user['id'] ?>" class="btn-action btn-edit">Edit</a>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <a href="index.php?page=users&delete=<?= $user['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Delete user?')">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
    
    <section class="content-card">
        <div class="card-header">
            <h2><?= $editUser ? 'Edit User' : 'Add User' ?></h2>
        </div>
        <div class="card-body">
            <form method="POST">
                <?php if ($editUser): ?>
                <input type="hidden" name="id" value="<?= $editUser['id'] ?>">
                <input type="hidden" name="update" value="1">
                <?php else: ?>
                <input type="hidden" name="add" value="1">
                <?php endif; ?>
                
                <div class="form-row">
                    <label>Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($editUser['name'] ?? '') ?>" required>
                </div>
                
                <div class="form-row">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($editUser['email'] ?? '') ?>" required>
                </div>
                
                <div class="form-row">
                    <label>Password <?= $editUser ? '(leave empty to keep current)' : '' ?></label>
                    <input type="password" name="password" <?= $editUser ? '' : 'required' ?>>
                </div>
                
                <div class="form-row">
                    <label>Role</label>
                    <select name="role">
                        <option value="author" <?= ($editUser['role'] ?? '') === 'author' ? 'selected' : '' ?>>Author</option>
                        <option value="editor" <?= ($editUser['role'] ?? '') === 'editor' ? 'selected' : '' ?>>Editor</option>
                        <option value="admin" <?= ($editUser['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <label>Bio</label>
                    <textarea name="bio" rows="3"><?= htmlspecialchars($editUser['bio'] ?? '') ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary"><?= $editUser ? 'Update' : 'Add' ?> User</button>
                <?php if ($editUser): ?>
                <a href="index.php?page=users" class="btn btn-secondary">Cancel</a>
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
.form-row input,
.form-row select,
.form-row textarea {
    width: 100%;
    padding: 0.625rem;
    border: 1px solid var(--rule);
    font-family: 'DM Sans', sans-serif;
}
.btn-action {
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
    font-family: 'DM Sans', sans-serif;
    border: 1px solid var(--rule);
    background: #fff;
    color: var(--ink);
    text-decoration: none;
    border-radius: 3px;
    cursor: pointer;
}
.btn-edit {
    border-color: var(--accent);
    color: var(--accent);
}
.btn-delete {
    border-color: var(--danger);
    color: var(--danger);
}
.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 4px;
}
.alert-success {
    background: #efe;
    border: 1px solid #cfc;
    color: #363;
}
.alert-error {
    background: #fee;
    border: 1px solid #fcc;
    color: #633;
}
.status-admin { background: var(--accent); color: #fff; }
.status-editor { background: var(--dark-overlay); color: #fff; }
.status-author { background: var(--paper-dark); color: var(--ink); }
</style>