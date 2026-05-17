<?php
require_once 'functions.php';
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}
$user = get_profile(current_user_id());
if (!$user) {
    die('User not found.');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    if ($username !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("
            SELECT id
            FROM users
            WHERE username = ?
            AND id != ?
            LIMIT 1
        ");
        $stmt->execute([
            $username,
            current_user_id()
        ]);
        $existing = $stmt->fetch();
        if (!$existing) {
            $stmt = $pdo->prepare("
                UPDATE users
                SET
                    username = ?,
                    email = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $username,
                $email,
                current_user_id()
            ]);
            $_SESSION['username'] = $username;
            header('Location: settings.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <?php include 'link.php'; ?>
</head>
<body class="min-h-screen bg-fuchsia-950 text-white">
    <div class="mx-auto grid min-h-screen max-w-7xl md:grid-cols-[220px_minmax(0,1fr)_260px]">
        <?php include 'header.php'; ?>
        <div>
            <header class="sticky top-0 z-10 flex items-center border-b border-pink-800 bg-fuchsia-950 p-4">
                <h1 class="text-2xl font-bold">Settings</h1>
            </header>
            <section class="p-4 text-2xl font-bold space-y-5">
                <button onclick="openSettingsPopup('account-popup')" class="block hover:text-pink-700 cursor-pointer">Account</button>
                <?php if ((int)$user['is_admin'] === 1): ?>
                    <button onclick="openSettingsPopup('moderation-popup')" class="block hover:text-pink-700 cursor-pointer">Moderation</button>
                <?php endif; ?>
                <button onclick="openSettingsPopup('help-popup')" class="block hover:text-pink-700 cursor-pointer">Help</button>
                <button onclick="openSettingsPopup('about-popup')" class="block hover:text-pink-700 cursor-pointer">About</button>
                <hr class="border-pink-800">
                <a href="logout.php" class="block text-pink-700">Log out</a>
            </section>
        </div>
        <?php include 'footer.php'; ?>
        <div id="account-popup" class="hidden fixed inset-0 z-100 flex items-center justify-center bg-black/50 p-4" onclick="closeSettingsPopup('account-popup')">
            <div onclick="event.stopPropagation()" class="w-full max-w-2xl rounded-3xl border border-pink-800 bg-fuchsia-900 p-8">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-3xl font-bold">Account</h2>
                    <button onclick="closeSettingsPopup('account-popup')" class="text-3xl cursor-pointer">
                        ×
                    </button>
                </div>
                <form method="POST" class="space-y-4">
                    <div>
                        <label for="username" class="block text-gray-300 mb-2">
                            Username
                        </label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="<?= e($user['username']) ?>"
                            required
                            class="w-full rounded-2xl border border-pink-800 bg-fuchsia-950 px-4 py-3 outline-none focus:border-pink-500">
                    </div>
                    <div>
                        <label for="email" class="block text-gray-300 mb-2">
                            Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?= e($user['email']) ?>"
                            required
                            class="w-full rounded-2xl border border-pink-800 bg-fuchsia-950 px-4 py-3 outline-none focus:border-pink-500">
                    </div>
                    <button type="submit" name="update_account" class="rounded-2xl bg-pink-500 px-6 py-3 font-bold hover:bg-pink-600">
                        Save
                    </button>
                </form>
            </div>
        </div>
        <?php if ((int)$user['is_admin'] === 1): ?>
        <div id="moderation-popup" class="hidden fixed inset-0 z-100 flex items-center justify-center bg-black/50 p-4" onclick="closeSettingsPopup('moderation-popup')">
            <div onclick="event.stopPropagation()" class="w-full max-w-2xl rounded-3xl border border-pink-800 bg-fuchsia-900 p-8">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-3xl font-bold">Moderation</h2>
                    <button onclick="closeSettingsPopup('moderation-popup')" class="text-3xl cursor-pointer">
                        ×
                    </button>
                </div>
                <div class="space-y-4">
                    <a href="manage-users.php" class="block w-full rounded-2xl border border-pink-800 p-4 text-left hover:border-pink-500">
                        Manage Users
                    </a>
                    <a href="manage-posts.php" class="block w-full rounded-2xl border border-pink-800 p-4 text-left hover:border-pink-500">
                        Manage Posts
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div id="help-popup" class="hidden fixed inset-0 z-100 flex items-center justify-center bg-black/50 p-4" onclick="closeSettingsPopup('help-popup')">
            <div onclick="event.stopPropagation()" class="w-full max-w-2xl rounded-3xl border border-pink-800 bg-fuchsia-900 p-8">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-3xl font-bold">Help</h2>
                    <button onclick="closeSettingsPopup('help-popup')" class="text-3xl cursor-pointer">
                        ×
                    </button>
                </div>
                <div class="space-y-4 text-gray-300">
                    <p>Need help with your account or posts?</p>
                    <p>Well that's too bad.</p>
                </div>
            </div>
        </div>
        <div id="about-popup" class="hidden fixed inset-0 z-100 flex items-center justify-center bg-black/50 p-4" onclick="closeSettingsPopup('about-popup')">
            <div onclick="event.stopPropagation()" class="w-full max-w-2xl rounded-3xl border border-pink-800 bg-fuchsia-900 p-8">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-3xl font-bold">About</h2>
                    <button onclick="closeSettingsPopup('about-popup')" class="text-3xl cursor-pointer">
                        ×
                    </button>
                </div>
                <div class="space-y-4 text-gray-300">
                    <p>Social media platform project built with:</p>
                    <ul class="list-disc pl-6">
                        <li>PHP</li>
                        <li>MySQL</li>
                        <li>TailwindCSS</li>
                        <li>JavaScript</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>