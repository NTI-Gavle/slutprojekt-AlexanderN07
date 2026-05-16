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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="website.js" defer></script>
    <link rel="stylesheet" href="website.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-fuchsia-950 text-white">
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
        <?php foreach (get_posts() as $post) include 'post-card.php'; ?>
    </main>
    <?php include 'footer.php'; ?>
</div>
</body>
</html>