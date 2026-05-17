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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post_id'])) {
    $stmt = $pdo->prepare("
        DELETE FROM posts
        WHERE id = ?
    ");
    $stmt->execute([
        (int)$_POST['delete_post_id']
    ]);
    header('Location: manage-posts.php');
    exit;
}
$stmt = $pdo->query("
    SELECT
        p.*,
        u.username
    FROM posts p
    JOIN users u
        ON u.id = p.user_id
    ORDER BY p.created_at DESC
");
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
    <?php include 'link.php'; ?>
</head>
<body class="min-h-screen bg-fuchsia-950 text-white">
<div class="mx-auto max-w-5xl p-6">
    <a href="settings.php" class="mb-6 inline-block text-pink-400 hover:text-pink-300">
        ← Back
    </a>
    <h1 class="mb-6 text-4xl font-bold">Manage Posts</h1>
    <div class="space-y-4">
        <?php foreach ($posts as $post): ?>
            <div class="rounded-2xl border border-pink-800 bg-fuchsia-900 p-4">
                <div class="mb-2 flex items-center gap-2">
                    <b><?= e($post['username']) ?></b>
                    <span class="text-gray-300">#<?= (int)$post['id'] ?></span>
                </div>
                <p><?= e($post['content']) ?></p>
                <form method="POST" class="mt-4">
                    <input type="hidden" name="delete_post_id" value="<?= (int)$post['id'] ?>">
                    <button type="submit" class="rounded-xl bg-red-500 px-4 py-2 hover:bg-red-600">
                        Delete Post
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>