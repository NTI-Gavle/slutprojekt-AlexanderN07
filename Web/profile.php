<?php

require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    die('You must be logged in.');
}

$profile = get_profile(current_user_id());
if (!$profile) {
    die('Profile not found.');
}
$tab = $_GET['tab'] ?? 'posts';

$allowedTabs = [
    'posts',
    'likes',
    'favorites'
];

if (!in_array($tab, $allowedTabs, true)) {
    $tab = 'posts';
}

$content = get_profile_content(
    current_user_id(),
    $tab
);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_post_id']) && isset($_SESSION['user_id'])){
    toggle_like(
        (int)$_POST['like_post_id'],
        current_user_id()
    );
    header("location: profile.php?tab=$tab");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_comment_id']) && isset($_SESSION['user_id'])){
    toggle_comment_like(
        (int)$_POST['like_comment_id'],
        current_user_id()
    );
    header("location: profile.php?tab=$tab");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <?php include 'link.php'; ?>
</head>
<body>
<body class="min-h-screen bg-fuchsia-950 text-white">
<div class="mx-auto grid min-h-screen max-w-7xl md:grid-cols-[220px_minmax(0,1fr)_260px]">
    <?php include 'header.php'; ?>
    <div>
        <header class="sticky top-0 z-10 flex items-center border-b border-pink-800 bg-fuchsia-950 p-4">
            <h1 class="text-2xl font-bold">Profile</h1>
        </header>
        <section>
            <div class="h-40 bg-purple-500">
                <img src="" alt="banner" class="h-40 bg-purple-500">
            </div>
            <div class="px-4 pb-4">
                <div class="-mt-12 h-25 w-25 rounded-full border-4 border-pink-300 bg-pink-500">
                    <img src="" alt="pfp" class="h-25 w-25 rounded-full">
                </div>
                <h2 class="mt-3 text-2xl font-bold"><?= e($profile['username']) ?></h2>
                <p class="text-gray-200">@<?= e($profile['username']) ?></p>
                <p class="mt-3 mb-3"><?= e($profile['bio'] ?? '') ?></p>
                <hr class="border-pink-800">
                <div class="mt-4 flex gap-6 text-gray-200">
                    <a href="profile.php?tab=posts" class="hover:text-pink-400"><b><?= (int)$profile['post_count'] ?></b> Posts</a> |
                    <a href="profile.php?tab=likes" class="hover:text-pink-400"><b><?= (int)$profile['liked_count'] ?></b> Liked</a> |
                    <a href="profile.php?tab=favorites" class="hover:text-pink-400"><b><?= (int)$profile['favorite_count'] ?></b> Favorites</a>
                </div>
            </div>
            <?php foreach ($content as $post): ?>
                <?php include 'post-card.php'; ?>
            <?php endforeach; ?>
        </section>
    </div>
    <?php include 'footer.php'; ?>
</div>
</body>
</html>