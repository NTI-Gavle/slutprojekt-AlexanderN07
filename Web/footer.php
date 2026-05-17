<aside class="hidden md:block sticky top-0 h-screen border-l border-pink-800 p-5">
    <?php if (isset($_SESSION['user_id'])): ?>
        <h2 class="mb-6 text-3xl font-bold">Following</h2>
        <div class="space-y-5">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-full bg-pink-500">
                    <img src="" alt="pfp" class="h-10 w-10 rounded-full">
                </div>
                <div>
                    <p class="font-bold">placeholder user</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <h2 class="mb-6 text-3xl font-bold">Log in to see following</h2>
    <?php endif; ?>
</aside>
<nav class="fixed inset-x-0 bottom-0 z-20 flex justify-around border-t border-pink-800 bg-fuchsia-950 py-3 md:hidden">
    <a href="home.php">Home</a>
    <a href="notifications.php">Notif</a>
    <a href="chats.php">Chat</a>
    <a href="profile.php">Profile</a>
    <a href="settings.php">Settings</a>
</nav>