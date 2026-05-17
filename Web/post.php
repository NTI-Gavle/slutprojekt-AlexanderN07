<?php
require_once 'functions.php';
$postId = (int)($_GET['id'] ?? 0);
if($postId <=0){
    die('Invalid post');
}
$post = get_post($postId);
if(!$post){
    die('Post not found');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_post_id']) && isset($_SESSION['user_id'])){
    toggle_like(
        (int)$_POST['like_post_id'],
        current_user_id()
    );
    header("location: post.php?id=$postId");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_comment_id']) && isset($_SESSION['user_id'])){
    toggle_comment_like(
        (int)$_POST['like_comment_id'],
        current_user_id()
    );
    header("location: post.php?id=$postId");
    exit;
}
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && trim($_POST['content'] ?? '') !== ''){
    create_comment($postId, current_user_id(), trim($_POST['content']));
    header("Location: post.php?id=$postId");
    exit;
}
$comments = get_comments($postId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>
    <?php include 'link.php'; ?>
</head>
<body class="min-h-screen bg-fuchsia-950 text-white">
<div class="mx-auto grid min-h-screen max-w-7xl md:grid-cols-[220px_minmax(0,1fr)_260px]">
    <?php include 'header.php'; ?>
    <div>
        <div class="block border-b border-pink-800 p-4 font-bold">
            <button onclick="history.back()" class="-ml-3 w-20 h-15 hover:bg-fuchsia-900">←</button>
        </div>
        <article class="border-b border-pink-800 p-4">
            <div class="flex items-center gap-2">
                <b><?= e($post['username']) ?></b>
                <span class="text-gray-300">
                    @<?= e($post['username']) ?>
                    <b>|</b>
                    <?php
                    $date = new DateTime($post['created_at']);        
                    $now = new DateTime();
                    if ($date->format('Y') === $now->format('Y')) {
                        echo e($date->format('M j \a\t H:i'));
                    } else {
                        echo e($date->format('M j, Y \a\t H:i'));
                    }
                    ?>
                </span>
            </div>
            <div class="mt-1">
                <?= e($post['content']) ?>
            </div>
            <div class="mt-3 flex max-w-md justify-between text-gray-200">
                <form method="POST">
                    <input type="hidden" name="like_post_id" value="<?= (int)$post['id'] ?>">
                    <button type="submit">♡ <?= (int)$post['like_count'] ?></button>
                </form>
                <button>↻ </button>
                <button>💬 <?= (int)$post['comment_count'] ?></button>
                <button>☆ <?= (int)$post['favorite_count'] ?></button>
                <button>↗</button>
            </div>
        </article>
        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="POST" class="border-b border-pink-800 p-4">
                <textarea name="content" required placeholder="Write a comment..." class="h-28 w-full resize-none rounded-2xl border border-pink-800 bg-fuchsia-900 p-4 outline-none"></textarea>
                <div class="mt-3 flex justify-end">
                    <button class="rounded-full bg-pink-500 px-6 py-2 font-bold hover:bg-pink-600">
                        Comment
                    </button>
                </div>
            </form>
        <?php endif; ?>
        <?php foreach ($comments as $comment): ?>
            <article class="border-b border-pink-900 p-4">
                <div class="flex items-center gap-2">
                    <b><?= e($comment['username']) ?></b>
                    <span class="text-gray-300">
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
                <a href="comment.php?id=<?= (int)$comment['id'] ?>">
                    <div class="mt-1">
                        <?= e($comment['content']) ?>
                    </div>
                </a>
                <div class="mt-3 flex max-w-md justify-between text-gray-200">
                    <form method="POST">
                        <input type="hidden" name="like_comment_id" value="<?= (int)$comment['id'] ?>">
                        <button type="submit">♡ <?= (int)$comment['like_count'] ?></button>
                    </form>
                    <button>↻ </button>
                    <button>💬 <?= (int)$comment['comment_count'] ?></button>
                    <button>☆ <?= (int)$comment['favorite_count'] ?></button>
                    <button>↗</button>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php include 'footer.php'; ?>
</div>
</body>
</html>