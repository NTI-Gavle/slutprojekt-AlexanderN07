<?php
require_once 'functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && trim($_POST['content'] ?? '') !== ''){
    create_post(current_user_id(), trim($_POST['content']));
    header('Location: home.php');
    exit;
};
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'login') {
        include 'login.php';
    } elseif (isset($_POST['action']) && $_POST['action'] == 'register') {
        include 'register.php';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_post_id']) && isset($_SESSION['user_id'])){
    toggle_like(
        (int)$_POST['like_post_id'],
        current_user_id()
    );
    header('location: home.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite_post_id']) && isset($_SESSION['user_id'])){
    toggle_favorite(
        (int)$_POST['favorite_post_id'],
        current_user_id()
    );
    header('Location: home.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repost_post_id']) && isset($_SESSION['user_id'])){
    toggle_repost(
        (int)$_POST['repost_post_id'],
        null,
        current_user_id()
    );
    header("Location: home.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repost_comment_id']) && isset($_SESSION['user_id'])){
    toggle_repost(
        null,
        (int)$_POST['repost_comment_id'],
        current_user_id()
    );
    header("Location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <?php include 'link.php'; ?>
</head>
<body class="min-h-screen bg-fuchsia-950 text-white"
      data-open-login="<?= !empty($error_message) && ($_POST['action'] ?? '') === 'login' ? 'true' : 'false' ?>"
      data-open-register="<?= !empty($error_message) && ($_POST['action'] ?? '') === 'register' ? 'true' : 'false' ?>">
<div class="mx-auto grid min-h-screen max-w-7xl md:grid-cols-[220px_minmax(0,1fr)_260px]">
    <?php include 'header.php'; ?>
    <main class="border-x border-pink-800 pb-20 md:pb-0">
        <header class="sticky top-0 z-10 flex bg-fuchsia-950 border-b border-pink-800">
            <button class="flex-1 py-4 text-center font-bold border-b-4 border-pink-500">
                Discover
            </button>
            <button class="flex-1 py-4 text-center text-pink-300">
                Following
            </button>
        </header>
        <?php if (isset($_SESSION['user_id'])): ?>
            <form id="compose" method="post" class="border-b border-pink-800 p-4">
                <textarea
                    name="content"
                    class="h-28 w-full resize-none rounded-2xl border border-pink-800 bg-transparent p-4 outline-none"
                    placeholder="What's happening?"></textarea>
                <div class="mt-3 flex items-center justify-between">
                    <div class="flex gap-4 text-pink-400">
                        <button type="button">♡</button>
                        <button type="button">▣</button>
                        <button type="button">☺</button>
                        <button type="button">↗</button>
                    </div>
                    <button class="rounded-full bg-pink-500 px-6 py-2 font-bold text-white hover:bg-pink-600">
                        Post
                    </button>
                </div>
            </form>
        <?php endif; ?>
        <?php foreach (get_posts() as $post) include 'post-card.php'; ?>
    </main>
    <?php include 'footer.php'; ?>
</div>
</body>
</html>