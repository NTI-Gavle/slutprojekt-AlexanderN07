<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chats</title>
    <script src="website.js" defer></script>
    <link rel="stylesheet" href="website.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-fuchsia-950 text-white">
<div class="mx-auto grid min-h-screen max-w-7xl md:grid-cols-[220px_minmax(0,1fr)_260px]">
    <?php include 'header.php'; ?>
    <div>
        <header class="sticky top-0 z-10 flex items-center border-b border-pink-800 bg-fuchsia-950 p-4 justify-between">
            <h1 class="text-2xl font-bold">Chats</h1>
            <a href="" class="mt-auto rounded-full px-5 py-3 text-center font-bold text-white bg-pink-500 hover:bg-pink-600">+ New Post</a>
        </header>
        <section class="divide-y divide-pink-800">
            <a href="" class="block p-4 hover:bg-fuchsia-300">
                <div class="flex gap-3 p-4">
                    <div class="h-10 w-10 rounded-full bg-pink-500">
                        <img src="" alt="pfp" class="h-10 w-10 rounded-full">
                    </div>
                    <div>
                        <p class="font-bold">name of chat</p>
                        <p class="text-gray-200">Open conversation</p>
                    </div>
                </div>
            </a>
        </section>
    </div>
    <?php include 'footer.php'; ?>
</div>
</body>
</html>