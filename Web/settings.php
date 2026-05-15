<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <script src="website.js" defer></script>
    <link rel="stylesheet" href="website.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
<body class="min-h-screen bg-fuchsia-950 text-white">
<div class="mx-auto grid min-h-screen max-w-7xl md:grid-cols-[220px_minmax(0,1fr)_260px]">
    <?php include 'header.php'; ?>
    <div>
        <header class="sticky top-0 z-10 flex items-center border-b border-pink-800 bg-fuchsia-950 p-4">
            <h1 class="text-2xl font-bold">Settings</h1>
        </header>
        <section class="p-4 text-2xl font-bold space-y-5">
            <a href="" class="block hover:text-pink-700">Account</a>
            <a href="" class="block hover:text-pink-700">Security</a>
            <a href="" class="block hover:text-pink-700">Moderation</a>
            <a href="" class="block hover:text-pink-700">Style</a>
            <a href="" class="block hover:text-pink-700">Help</a>
            <a href="" class="block hover:text-pink-700">About</a>
            <hr class="border-pink-800">
            <a href="" class="block text-pink-700">Log out</a>
        </section>
    </div>
    <?php include 'footer.php'; ?>
</div>
</body>
</html>