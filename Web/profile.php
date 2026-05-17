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
                <h2 class="mt-3 text-2xl font-bold">possibly nickname<!-- otherwise username and the next element "p" might get removed--></h2>
                <p class="text-gray-200">@Username</p>
                <p class="mt-3 mb-3">bio</p>
                <hr class="border-pink-800">
                <div class="mt-4 flex gap-6 text-gray-200">
                    <span><b>0<!-- placeholder for amount of posts --></b>Posts</span>
                    <span><b>0<!-- placeholder for amount of media --></b>Media</span>
                    <span><b>0<!-- placeholder for amount of liked --></b>Liked</span>
                    <span><b>0<!-- placeholder for amount of favorited --></b>Favorites</span>
                </div>
            </div>
        </section>
    </div>
    <?php include 'footer.php'; ?>
</div>
</body>
</html>