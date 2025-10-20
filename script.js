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

/// modal 
const modal = document.getElementById('course-modal');
const cardBtn = document.querySelector('.card-btn');
const closeModal = document.querySelector('.close-modal');

cardBtn.addEventListener('click', function() {
    modal.style.display = 'block';
});

closeModal.addEventListener('click', function() {
    modal.style.display = 'none';
});

window.addEventListener('click', function(event) {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

/// hamburger
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');

hamburger.addEventListener('click', function() {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
});

//// form-contact - procesa 
document.getElementById('contactForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    document.querySelectorAll('.error').forEach(error => {
        error.style.display = 'none';
    });
    

    document.getElementById('successMessage').style.display = 'none';
    
 
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const message = document.getElementById('message').value.trim();
    
})


/// search bar

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const cards = document.querySelectorAll('.card');

    searchInput.addEventListener('input', () => {
        const searchText = searchInput.value.toLowerCase();

        cards.forEach(card => {
            const cardText = card.querySelector('h3').textContent.toLowerCase() + 
                             card.querySelector('p').textContent.toLowerCase();

            if (cardText.includes(searchText)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

});