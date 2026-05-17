<?php
require_once 'functions.php';
$commentId = (int)($_GET['id'] ?? 0);
if ($commentId <= 0){
    die('Invalid comment.');
}
$comment = get_comment($commentId);
if (!$comment){
    die('Comment not found.');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && trim($_POST['content'] ?? '') !== ''){
    create_comment(
        (int)$comment['post_id'],
        current_user_id(),
        trim($_POST['content']),
        $commentId
    );
    header("Location: comment.php?id=$commentId");
    exit;
}

$replies = get_comment_replies($commentId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment</title>
    <?php include 'link.php'; ?>
</head>
<body class="min-h-screen bg-fuchsia-950 text-white">
<div class="mx-auto grid min-h-screen max-w-7xl md:grid-cols-[220px_minmax(0,1fr)_260px]">
    <?php include 'header.php'; ?>
    <div>
        <a href="home.php" class="block border-b border-pink-800 p-4 font-bold hover:bg-fuchsia-900">←</a>
        <article class="border-b border-pink-800 p-4">
            <div class="flex gap-3">
                <div class="h-10 w-10 shrink-0 rounded-full bg-pink-500">
                    <img src="" alt="pfp" class="h-10 w-10 rounded-full object-cover">
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <b><?= e($comment['username']) ?></b>
                        <span class="text-gray-400">
                            @<?= e($comment['username']) ?>
                            <b>|</b>
                            <?php
                            $date = new DateTime($comment['created_at']);        
                            $now = new DateTime();
                            if ($date->format('Y') === $now->format('Y')) {
                                echo e($date->format('M j \a\t H:i'));
                            } else {
                                echo e($date->format('M j, Y \a\t H:i'));
                            }
                            ?>
                        </span>
                    </div>
                    <div class="mt-3 text-lg">
                        <?= e($comment['content']) ?>
                    </div>
                    <div class="mt-4 flex max-w-md justify-between text-gray-300">
                        <button type="button"> ♡ <?= (int)$comment['like_count'] ?></button>
                        <button type="button">↻</button>
                        <button type="button">💬 <?= (int)$comment['comment_count'] ?></button>
                        <button type="button">☆ <?= (int)$comment['favorite_count'] ?></button>
                        <button type="button">↗</button>
                    </div>
                </div>
            </div>
        </article>
        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="POST" class="border-b border-pink-800 p-4">
                <textarea name="content" required placeholder="Write a reply..." class="h-28 w-full resize-none rounded-2xl border border-pink-800 bg-fuchsia-900 p-4 outline-none"></textarea>
                <div class="mt-3 flex justify-end">
                    <button class="rounded-full bg-pink-500 px-6 py-2 font-bold hover:bg-pink-600">
                        Reply
                    </button>
                </div>
            </form>
        <?php endif; ?>
        <?php foreach ($replies as $reply): ?>
            <article class="border-b border-pink-900 p-4">
                <div class="flex items-center gap-2">
                    <b><?= e($reply['username']) ?></b>
                    <span class="text-gray-300">
                        @<?= e($reply['username']) ?>
                        <b>|</b>
                        <?php
                        $date = new DateTime($reply['created_at']);        
                        $now = new DateTime();
                        if ($date->format('Y') === $now->format('Y')) {
                            echo e($date->format('M j \a\t H:i'));
                        } else {
                            echo e($date->format('M j, Y \a\t H:i'));
                        }
                        ?>
                    </span>
                </div>
                <a href="comment.php?id=<?= (int)$reply['id'] ?>">
                    <div class="mt-1">
                        <?= e($reply['content']) ?>
                    </div>
                    <div class="mt-3 flex max-w-md justify-between text-gray-200">
                        <button>♡ <?= (int)$reply['like_count'] ?></button>
                        <button>↻ </button>
                        <button>💬 <?= (int)$reply['comment_count'] ?></button>
                        <button>☆ <?= (int)$reply['favorite_count'] ?></button>
                        <button>↗</button>
                    </div>
                </a>
            </article>
        <?php endforeach; ?>
    </div>
    <?php include 'footer.php'; ?>
</div>
</body>
</html>