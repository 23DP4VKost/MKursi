// Melna tema
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    
    // saglab tema
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        themeToggle.textContent = 'Mainīt temu uz balto';
    }
    
    // Mainit tekst ar temu
    themeToggle.addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        
        if (document.body.classList.contains('dark-mode')) {
            localStorage.setItem('theme', 'dark');
            themeToggle.textContent = 'Mainīt temu uz balto';
        } else {
            localStorage.setItem('theme', 'light');
            themeToggle.textContent = 'Mainīt temu uz melno';
        }
    });
    
});