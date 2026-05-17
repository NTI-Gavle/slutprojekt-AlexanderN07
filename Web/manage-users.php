<?php
require_once 'functions.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}
$user = get_profile(current_user_id());
if ((int)$user['is_admin'] !== 1) {
    die('Access denied.');
}
global $pdo;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    $deleteUserId = (int)$_POST['delete_user_id'];
    if ($deleteUserId !== current_user_id()) {
        $stmt = $pdo->prepare("
            DELETE FROM users
            WHERE id = ?
        ");
        $stmt->execute([
            $deleteUserId
        ]);
    }
    header('Location: manage-users.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_admin_id'])) {
    $targetUserId = (int)$_POST['toggle_admin_id'];
    $stmt = $pdo->prepare("
        UPDATE users
        SET is_admin = NOT is_admin
        WHERE id = ?
    ");
    $stmt->execute([
        $targetUserId
    ]);
    header('Location: manage-users.php');
    exit;
}
$stmt = $pdo->query("
    SELECT
        id,
        username,
        email,
        is_admin
    FROM users
    ORDER BY id DESC
");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <?php include 'link.php'; ?>
</head>
<body class="min-h-screen bg-fuchsia-950 text-white">
<div class="mx-auto max-w-5xl p-6">
    <a href="settings.php" class="mb-6 inline-block text-pink-400 hover:text-pink-300">
        ← Back
    </a>
    <h1 class="mb-6 text-4xl font-bold">Manage Users</h1>
    <div class="space-y-4">
        <?php foreach ($users as $u): ?>
            <div class="rounded-2xl border border-pink-800 bg-fuchsia-900 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xl font-bold"><?= e($u['username']) ?></p>
                        <p class="text-gray-300"><?= e($u['email']) ?></p>
                        <?php if ((int)$u['is_admin'] === 1): ?>
                            <p class="mt-1 text-pink-400">Admin</p>
                        <?php endif; ?>
                    </div>
                    <div class="flex gap-2">
                        <?php if ((int)$u['id'] !== current_user_id()): ?>
                            <form method="POST">
                                <input type="hidden" name="toggle_admin_id" value="<?= (int)$u['id'] ?>">
                                <button type="submit" class="rounded-xl bg-pink-500 px-4 py-2 hover:bg-pink-600">
                                    <?php if ((int)$u['is_admin'] === 1): ?>
                                        Remove Admin
                                    <?php else: ?>
                                        Make Admin
                                    <?php endif; ?>
                                </button>
                            </form>
                            <form method="POST">
                                <input type="hidden" name="delete_user_id" value="<?= (int)$u['id'] ?>">
                                <button type="submit" class="rounded-xl bg-red-500 px-4 py-2 hover:bg-red-600">
                                    Delete
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>