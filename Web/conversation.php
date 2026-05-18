<?php
require_once 'functions.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}
global $pdo;
$otherUserId = (int)($_GET['id'] ?? 0);
if ($otherUserId <= 0) {
    die('Invalid user.');
}
$stmt = $pdo->prepare("
    SELECT id
    FROM follows
    WHERE follower_id = ?
    AND following_id = ?
    LIMIT 1
");
$stmt->execute([
    $otherUserId,
    current_user_id()
]);
$allowed = $stmt->fetch();
if (!$allowed) {
    die('This user cannot message you.');
}
$otherUser = get_profile($otherUserId);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && trim($_POST['content'] ?? '') !== '') {
    $stmt = $pdo->prepare("
        INSERT INTO dms (
            sender_id,
            recipient_id,
            content,
            created_at
        )
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([
        current_user_id(),
        $otherUserId,
        trim($_POST['content'])
    ]);
    header("Location: conversation.php?id=$otherUserId");
    exit;
}
$stmt = $pdo->prepare("
    SELECT
        d.*,
        u.username,
        u.profile_picture_url
    FROM dms d
    JOIN users u
        ON u.id = d.sender_id
    WHERE (
        d.sender_id = ?
        AND d.recipient_id = ?
    )
    OR (
        d.sender_id = ?
        AND d.recipient_id = ?
    )
    ORDER BY d.created_at ASC
");
$stmt->execute([
    current_user_id(),
    $otherUserId,
    $otherUserId,
    current_user_id()
]);
$messages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversations</title>
    <?php include 'link.php'; ?>
</head>
<body class="min-h-screen bg-fuchsia-950 text-white">
    <div class="mx-auto grid min-h-screen max-w-7xl md:grid-cols-[220px_minmax(0,1fr)_260px]">
        <?php include 'header.php'; ?>
        <div class="flex min-h-screen flex-col">
            <header class="sticky top-0 z-10 border-b border-pink-800 bg-fuchsia-950 p-4">
                <div class="flex items-center gap-3">
                    <a href="chats.php" class="text-2xl">
                        ←
                    </a>
                    <b class="text-2xl"><?= e($otherUser['username']) ?></b>
                </div>
            </header>
            <div class="flex-1 space-y-4 p-4">
            <?php foreach ($messages as $message): ?>
                <?php $own = $message['sender_id'] === current_user_id(); ?>
                    <div class="flex <?= $own ? 'justify-end' : 'justify-start' ?>">
                        <div class="flex max-w-[75%] items-end gap-3">
                            <?php if (!$own): ?>
                                <div class="h-10 w-10 shrink-0 overflow-hidden rounded-full bg-pink-500">
                                    <?php if (!empty($message['profile_picture_url'])): ?>
                                        <img src="<?= e($message['profile_picture_url']) ?>" alt="pfp" class="h-full w-full object-cover">
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class="flex flex-col <?= $own ? 'items-end' : 'items-start' ?>">
                                <p class="mb-1 text-sm font-bold text-pink-200"><?= e($message['username']) ?></p>
                                <div class="<?= $own ? 'bg-pink-500 text-left' : 'bg-fuchsia-900 text-left' ?> max-w-[420px] break-words rounded-2xl p-4">
                                    <p class="whitespace-pre-wrap break-words"><?= e($message['content']) ?></p>
                                </div>
                            </div>
                            <?php if ($own): ?>
                                <div class="h-10 w-10 shrink-0 overflow-hidden rounded-full bg-pink-500">
                                    <?php if (!empty($message['profile_picture_url'])): ?>
                                        <img src="<?= e($message['profile_picture_url']) ?>" alt="pfp" class="h-full w-full object-cover">
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <form method="POST" class="border-t border-pink-800 p-4">
                <div class="flex gap-3">
                    <input type="text" name="content" placeholder="Write a message..." class="flex-1 rounded-full border border-pink-800 bg-fuchsia-900 px-4 py-3 outline-none">
                    <button type="submit" class="rounded-full bg-pink-500 px-6 py-3 font-bold hover:bg-pink-600">
                        Send
                    </button>
                </div>
            </form>
        </div>
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>