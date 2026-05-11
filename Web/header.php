<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="website.js" defer></script>
    <link rel="stylesheet" href="website.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-fuchsia-950 text-white">
    <header class="bg-fuchsia-950 text-white md:flex sticky top-0 flex-row absolute justify-center items-center space-x-4">
        <h2 class="border-b-5 border-pink-500">Discover</h2>
        <h2 class="border-b-2 border-pink-800">Following</h2>
    </header>
    <div class="mx-auto max-w-7xl grid grid-cols.1 md:grid-cols-[220px_minmax(0,1fr)_260px] min-h-screen">
        <aside class="hidden md:flex sticky top-0 h-screen flex-col border-r p-5 border-yellow-400">
            <input class="mb-8 inline-flex rounded-full border px-5 py-1 text-sm font-bold border-red" placeholder="Search"></input>
            <nav class="space-y-3 text-xl font-semibold">
                <a href="home.php" class="block hover:text-fuchsia-800">Home</a>
                <a href="notifications.php" class="block hover:text-fuchsia-800">Notifications</a>
                <a href="chats.php" class="block hover:text-fuchsia-800">Chats</a>
                <a href="profile.php" class="block hover:text-fuchsia-800">Profile</a>
                <a href="settings.php" class="block hover:text-fuchsia-800">Settings</a>
            </nav>
            <a href="" class="mt-auto rounded-full px-5 py-3 text-center font-bold text-white bg-pink-500 hover:bg-pink-600">+ New Post</a>
        </aside>
    </div>
</body>
</html>