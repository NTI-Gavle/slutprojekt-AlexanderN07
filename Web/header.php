<aside class="hidden md:flex sticky top-0 h-screen flex-col border-r border-pink-800 p-5">
    <input class="mb-8 inline-flex rounded-full border px-5 py-1 text-sm font-bold border-red" placeholder="Search"></input>
    <nav class="space-y-4 text-2xl font-semibold">
        <a href="home.php" class="block hover:text-pink-700">Home</a>
        <a href="notifications.php" class="block hover:text-pink-700">Notifications</a>
        <a href="chats.php" class="block hover:text-pink-700">Chat</a>
        <a href="profile.php" class="block hover:text-pink-700">Profile</a>
        <a href="settings.php" class="block hover:text-pink-700">Settings</a>
    </nav>
    <div class="mt-auto space-y-3">
        <?php if (isset($_SESSION['user_id'])): ?>
            <button onclick="openPostModal()" class="mt-auto rounded-full px-5 py-3 text-center font-bold text-white bg-pink-500 hover:bg-pink-600">+ New Post</button>
        <?php endif; ?>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <button type="button" onclick="login()" class="mt-auto rounded-full px-5 py-3 text-center font-bold text-white bg-pink-500 hover:bg-pink-600">Login/Register</button>
        <?php endif; ?>
    </div>
</aside>
<div id="reglog-container" class="hidden fixed inset-0 z-100 flex items-center justify-center" onclick="reglog()">
    <div id="login-container" class="hidden w-full max-w-md rounded-3xl border border-pink-800 bg-fuchsia-900 p-8 shadow-2xl" onclick="event.stopPropagation()">
        <h1 class="mb-6 text-center text-4xl font-bold">
            Sign In
        </h1>
        <?php if (!empty($error_message)): ?>
            <div class="mb-4 rounded-xl bg-red-500/20 border border-red-400 px-4 py-3 text-sm text-red-200">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <form action="" method="POST" class="space-y-4">
        
            <input type="hidden" name="action" value="login">
        
            <input
                type="text"
                id="username"
                name="username"
                autocomplete="off"
                placeholder="Username"
                required
                class="w-full rounded-2xl border border-pink-800 bg-fuchsia-950 px-4 py-3 outline-none transition focus:border-pink-500">
        
            <input
                type="password"
                id="password"
                name="password"
                autocomplete="off"
                placeholder="Password"
                required
                class="w-full rounded-2xl border border-pink-800 bg-fuchsia-950 px-4 py-3 outline-none transition focus:border-pink-500">
        
            <button
                type="submit"
                class="w-full rounded-2xl bg-pink-500 py-3 font-bold transition hover:bg-pink-600">
                Login
            </button>
        
        </form>
        
        <p class="mt-6 text-center text-gray-300">
            Don't have an account?
            <button
                id="registerbtn"
                onclick="register()"
                class="font-semibold text-pink-400 hover:text-pink-300 cursor-pointer">
                Register here
            </button>
        </p>
    </div>
    <div id="register-container" class="hidden w-full max-w-md rounded-3xl border border-pink-800 bg-fuchsia-900 p-8 shadow-2xl" onclick="event.stopPropagation()">
        
        <h1 class="mb-6 text-center text-4xl font-bold">
            Create Account
        </h1>
        
        <?php if (!empty($error_message)): ?>
            <div class="mb-4 rounded-xl bg-red-500/20 border border-red-400 px-4 py-3 text-sm text-red-200">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <form action="" method="POST" class="space-y-4">
        
            <input type="hidden" name="action" value="register">
        
            <input
                type="text"
                id="reg_username"
                name="username"
                placeholder="Username"
                required
                class="w-full rounded-2xl border border-pink-800 bg-fuchsia-950 px-4 py-3 outline-none transition focus:border-pink-500">
            <input
                type="email"
                id="reg_email"
                name="email"
                placeholder="Email"
                required
                class="w-full rounded-2xl border border-pink-800 bg-fuchsia-950 px-4 py-3 outline-none transition focus:border-pink-500">
            <input
                type="password"
                id="reg_password"
                name="password"
                placeholder="Password"
                required
                class="w-full rounded-2xl border border-pink-800 bg-fuchsia-950 px-4 py-3 outline-none transition focus:border-pink-500">
        
            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                placeholder="Confirm Password"
                required
                class="w-full rounded-2xl border border-pink-800 bg-fuchsia-950 px-4 py-3 outline-none transition focus:border-pink-500">
        
            <button
                type="submit"
                class="w-full rounded-2xl bg-pink-500 py-3 font-bold transition hover:bg-pink-600">
                Register
            </button>
        
        </form>
        
        <p class="mt-6 text-center text-gray-300">
            Already have an account?
            <button
                id="registerbtn2"
                onclick="register()"
                class="font-semibold text-pink-400 hover:text-pink-300 cursor-pointer">
                Login here
            </button>
        </p>
    </div>
</div>