function toggleTheme(){
    const body = document.body;
    const next = body.classList.contains('theme-light') ? 'dark' : 'light';
    body.classList.toggle('theme-light', next === 'light');
    body.classList.toggle('theme-dark', next === 'dark');
    localStorage.setItem('theme', next);
}
document.addEventListener('DOMContentLoaded', () => {
    const saved = localStorage.getItem('theme') || 'dark';
    document.body.classList.add(saved === 'light' ? 'theme-light' : 'theme-dark');
});
function register() {
    document.getElementById('login-container').classList.toggle('hidden');
    document.getElementById('register-container').classList.toggle('hidden');
}
function login() {
    document.getElementById('login-container').classList.toggle('hidden');
    document.getElementById('reglog-container').classList.toggle('hidden');
}
function reglog() {
    document.getElementById('reglog-container').classList.toggle('hidden');
    const login = document.getElementById('login-container');
    const register = document.getElementById('register-container');
    if (!login.classList.contains('hidden')) {
        login.classList.add('hidden');
    }
    if (!register.classList.contains('hidden')) {
        register.classList.add('hidden');
    }
}
function openLogin() {
    document.getElementById('reglog-container').classList.remove('hidden');
    document.getElementById('login-container').classList.remove('hidden');
    document.getElementById('register-container').classList.add('hidden');
}
function openRegister() {
    document.getElementById('reglog-container').classList.remove('hidden');
    document.getElementById('register-container').classList.remove('hidden');
    document.getElementById('login-container').classList.add('hidden');
}
document.addEventListener('DOMContentLoaded', () => {
    if (document.body.dataset.openLogin === 'true') {
        openLogin();
    }
    if (document.body.dataset.openRegister === 'true') {
        openRegister();
    }
});