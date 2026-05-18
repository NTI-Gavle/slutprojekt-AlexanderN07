<?php

require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chats</title>
    <?php include 'link.php'; ?>
</head>
<body class="min-h-screen bg-fuchsia-950 text-white">
<div class="mx-auto grid min-h-screen max-w-7xl md:grid-cols-[220px_minmax(0,1fr)_260px]">
    <?php include 'header.php'; ?>
    <div>
        <header class="sticky top-0 z-10 flex items-center border-b border-pink-800 bg-fuchsia-950 p-4 justify-between">
            <h1 class="text-2xl font-bold">Chats</h1>
            <button onclick="openSettingsPopup('new-chat-popup')" class="mt-auto rounded-full px-5 py-3 text-center font-bold text-white bg-pink-500 hover:bg-pink-600">+ New Chat</button>
        </header>
        <section class="divide-y divide-pink-800">
            <?php foreach (get_chat_users(current_user_id()) as $chatUser): ?>
                <a href="conversation.php?id=<?= (int)$chatUser['id'] ?>" class="block p-4 hover:bg-fuchsia-900 transition">
                    <div class="flex gap-3">
                        <div class="h-12 w-12 overflow-hidden rounded-full bg-pink-500">
                            <?php if (!empty($chatUser['profile_picture_url'])): ?>
                                <img src="<?= e($chatUser['profile_picture_url']) ?>" alt="pfp" class="h-full w-full object-cover">
                            <?php endif; ?>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold"><?= e($chatUser['username']) ?></p>
                            <p class="text-gray-300">Open conversation</p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </section>
        <div id="new-chat-popup" class="hidden fixed inset-0 z-100 flex items-center justify-center bg-black/50 p-4" onclick="closeSettingsPopup('new-chat-popup')">
            <div onclick="event.stopPropagation()" class="w-full max-w-xl rounded-3xl border border-pink-800 bg-fuchsia-900 p-6">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-3xl font-bold">New Chat</h2>
                    <button onclick="closeSettingsPopup('new-chat-popup')" class="text-3xl">
                        ×
                    </button>
                </div>
                <div class="space-y-3">
                    <?php foreach (get_chat_users(current_user_id()) as $chatUser): ?>
                        <a href="conversation.php?id=<?= (int)$chatUser['id'] ?>" class="flex items-center gap-3 rounded-2xl p-3 hover:bg-fuchsia-950">
                            <div class="h-12 w-12 overflow-hidden rounded-full bg-pink-500">
                                <?php if (!empty($chatUser['profile_picture_url'])): ?>
                                    <img src="<?= e($chatUser['profile_picture_url']) ?>" alt="pfp" class="h-full w-full object-cover">
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="font-bold"><?= e($chatUser['username']) ?></p>
                                <p class="text-gray-300">Start conversation</p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</div>
</body>
</html>