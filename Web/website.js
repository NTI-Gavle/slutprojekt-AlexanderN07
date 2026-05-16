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