<?php

require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    die('You must be logged in.');
}

$profileId = (int)($_GET['id'] ?? current_user_id());

$profile = get_profile($profileId);
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite_post_id']) && isset($_SESSION['user_id'])){
    toggle_favorite(
        (int)$_POST['favorite_post_id'],
        current_user_id()
    );
    header("Location: profile.php?tab=$tab");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite_comment_id']) && isset($_SESSION['user_id'])){
    toggle_comment_favorite(
        (int)$_POST['favorite_comment_id'],
        current_user_id()
    );
    header("Location: profile.php?tab=$tab");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repost_post_id']) && isset($_SESSION['user_id'])){
    toggle_repost(
        (int)$_POST['repost_post_id'],
        null,
        current_user_id()
    );
    header("Location: profile.php?tab=$tab");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repost_comment_id']) && isset($_SESSION['user_id'])){
    toggle_repost(
        null,
        (int)$_POST['repost_comment_id'],
        current_user_id()
    );
    header("Location: profile.php?tab=$tab");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile_media'])) {
    global $pdo;
    $updates = [];
    $values = [];
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $pfpName = time() . '_' . $_FILES['profile_picture']['name'];
        move_uploaded_file(
            $_FILES['profile_picture']['tmp_name'],
            'uploads/' . $pfpName
        );
        $updates[] = "profile_picture_url = ?";
        $values[] = 'uploads/' . $pfpName;
    }
    if (isset($_FILES['banner_picture']) && $_FILES['banner_picture']['error'] === 0) {
        $bannerName = time() . '_' . $_FILES['banner_picture']['name'];
        move_uploaded_file(
            $_FILES['banner_picture']['tmp_name'],
            'uploads/' . $bannerName
        );
        $updates[] = "banner_picture_url = ?";
        $values[] = 'uploads/' . $bannerName;
    }
    if (!empty($updates)) {
        $values[] = current_user_id();
        $stmt = $pdo->prepare("
            UPDATE users
            SET " . implode(', ', $updates) . "
            WHERE id = ?
        ");
        $stmt->execute($values);
    }
    header('Location: profile.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['follow_user_id']) && isset($_SESSION['user_id'])) {
    toggle_follow(
        (int)$_POST['follow_user_id'],
        current_user_id()
    );
    header("Location: profile.php?id=$profileId");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_bio']) && isset($_SESSION['user_id'])) {
    global $pdo;
    $bio = trim($_POST['bio']);
    $stmt = $pdo->prepare("
        UPDATE users
        SET bio = ?
        WHERE id = ?
    ");
    $stmt->execute([
        $bio,
        current_user_id()
    ]);
    header("Location: profile.php?id=$profileId");
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
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="update_profile_media">
                <label class="block relative cursor-pointer">
                    <?php if (!empty($profile['banner_picture_url'])): ?>
                        <img src="<?= e($profile['banner_picture_url']) ?>" alt="banner" class="h-40 w-full object-cover">
                    <?php else: ?>
                        <div class="h-40 bg-purple-500"></div>
                    <?php endif; ?>
                    <input type="file" name="banner_picture" class="hidden" onchange="this.form.submit()">
                </label>
            </form>
            <div class="relative px-4 pb-4">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="update_profile_media">
                    <label class="relative z-20 block -mt-12 h-25 w-25 cursor-pointer overflow-hidden rounded-full border-4 border-pink-300 bg-pink-500">
                        <?php if (!empty($profile['profile_picture_url'])): ?>
                            <img src="<?= e($profile['profile_picture_url']) ?>" alt="pfp" class="h-full w-full object-cover">
                        <?php endif; ?>
                        <input type="file" name="profile_picture" class="hidden" onchange="this.form.submit()">
                    </label>
                </form>
                <div class="flex gap-6 flex-row">
                    <div class="flex flex-col">
                        <h2 class="mt-3 text-2xl font-bold"><?= e($profile['username']) ?></h2>
                        <p class="text-gray-200">@<?= e($profile['username']) ?></p>
                        <div class="mt-2 flex gap-4 text-sm text-gray-300">
                            <button type="button" onclick="openSettingsPopup('followers-popup')" class="cursor-pointer hover:text-pink-400">
                                <b><?= (int)$profile['follower_count'] ?></b>
                                Followers
                            </button>
                            <button type="button" onclick="openSettingsPopup('following-popup')" class="cursor-pointer hover:text-pink-400">
                                <b><?= (int)$profile['following_count'] ?></b>
                                Following
                            </button>
                        </div>
                    </div>
                    <?php if ($profileId !== current_user_id()): ?>
                        <form method="POST" class="mt-4">
                            <input type="hidden" name="follow_user_id" value="<?= (int)$profile['id'] ?>">
                            <button type="submit" class="rounded-full bg-pink-500 px-6 py-2 font-bold hover:bg-pink-600 cursor-pointer">
                                <?php if (is_following($profile['id'], current_user_id())): ?>
                                    Unfollow
                                <?php else: ?>
                                    Follow
                                <?php endif; ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
                <?php if ($profileId === current_user_id()): ?>
                    <form method="POST" class="mt-3 mb-3">
                        <textarea name="bio" placeholder="Write your bio..." class="w-full resize-none rounded-2xl border border-pink-800 bg-fuchsia-900 p-4 outline-none"><?= e($profile['bio'] ?? '') ?></textarea>
                        <button type="submit" name="update_bio" class="mt-3 rounded-full bg-pink-500 px-6 py-2 font-bold hover:bg-pink-600 cursor-pointer">
                            Save Bio
                        </button>
                    </form>
                <?php else: ?>
                    <div class="mt-4 mb-4 max-w-fit overflow-hidden rounded-2xl border border-pink-800 bg-fuchsia-950 p-4">
                        <b class="border-b-3 border-pink-500">Bio:</b>
                        <p class="mt-3 break-all whitespace-pre-wrap"><?= e($profile['bio'] ?? '') ?></p>
                    </div>
                <?php endif; ?>
                <hr class="border-pink-800">
                <div class="mt-4 flex gap-6 text-gray-200">
                    <?php if($profileId === current_user_id()): ?>
                        <a href="profile.php?tab=posts" class="hover:text-pink-400 cursor-pointer"><b><?= (int)$profile['post_count'] + (int)$profile['repost_count'] ?></b> Posts</a>
                    <?php else: ?>
                        <p><b><?= (int)$profile['post_count'] + (int)$profile['repost_count'] ?></b> Posts</p>
                    <?php endif; ?>
                    <?php if($profileId === current_user_id()): ?>
                        | <a href="profile.php?tab=likes" class="hover:text-pink-400 cursor-pointer"><b><?= (int)$profile['liked_count'] ?></b> Liked</a> |
                        <a href="profile.php?tab=favorites" class="hover:text-pink-400 cursor-pointer"><b><?= (int)$profile['favorite_count'] ?></b> Favorites</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php foreach ($content as $post): ?>
                <?php include 'post-card.php'; ?>
            <?php endforeach; ?>
        </section>
    </div>
    <div id="followers-popup" class="hidden fixed inset-0 z-100 flex items-center justify-center bg-black/50 p-4" onclick="closeSettingsPopup('followers-popup')">
        <div onclick="event.stopPropagation()" class="w-full max-w-xl rounded-3xl border border-pink-800 bg-fuchsia-900 p-6">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-3xl font-bold">Followers</h2>
                <button onclick="closeSettingsPopup('followers-popup')" class="text-3xl cursor-pointer">
                    ×
                </button>
            </div>
            <div class="space-y-3">
                <?php foreach (get_followers($profileId) as $user): ?>
                    <a href="profile.php?id=<?= (int)$user['id'] ?>" class="flex items-center gap-3 rounded-2xl p-3 hover:bg-fuchsia-950">
                        <div class="h-12 w-12 overflow-hidden rounded-full bg-pink-500">
                            <?php if (!empty($user['profile_picture_url'])): ?>
                                <img src="<?= e($user['profile_picture_url']) ?>" alt="pfp" class="h-full w-full object-cover">
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="font-bold"><?= e($user['username']) ?></p>
                            <p class="text-gray-300">@<?= e($user['username']) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div id="following-popup" class="hidden fixed inset-0 z-100 flex items-center justify-center bg-black/50 p-4" onclick="closeSettingsPopup('following-popup')">
        <div onclick="event.stopPropagation()" class="w-full max-w-xl rounded-3xl border border-pink-800 bg-fuchsia-900 p-6">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-3xl font-bold">Following</h2>
                <button onclick="closeSettingsPopup('following-popup')" class="text-3xl cursor-pointer">
                    ×
                </button>
            </div>
            <div class="space-y-3">
                <?php foreach (get_following($profileId) as $user): ?>
                    <a href="profile.php?id=<?= (int)$user['id'] ?>" class="flex items-center gap-3 rounded-2xl p-3 hover:bg-fuchsia-950">
                        <div class="h-12 w-12 overflow-hidden rounded-full bg-pink-500">
                            <?php if (!empty($user['profile_picture_url'])): ?>
                                <img src="<?= e($user['profile_picture_url']) ?>" alt="pfp" class="h-full w-full object-cover">
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="font-bold"><?= e($user['username']) ?></p>
                            <p class="text-gray-300">@<?= e($user['username']) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</div>
</body>
</html>