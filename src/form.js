document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const messageInput = document.getElementById('message');
    const nameError = document.getElementById('nameError');
    const emailError = document.getElementById('emailError');
    const messageError = document.getElementById('messageError');
    const successMessage = document.getElementById('successMessage');

    nameInput.addEventListener('input', validateName);
    emailInput.addEventListener('input', validateEmail);
    messageInput.addEventListener('input', validateMessage);

    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const isNameValid = validateName();
        const isEmailValid = validateEmail();
        const isMessageValid = validateMessage();
        
        if (isNameValid && isEmailValid && isMessageValid) {
            showSuccessMessage();
            
 
            contactForm.reset();
            
            setTimeout(() => {
                successMessage.classList.remove('show');
            }, 5000);
        }
    });


    function validateName() {
        const name = nameInput.value.trim();
        
        if (name === '') {
            showError(nameInput, nameError, 'Lūdzu, ievadiet savu vārdu');
            return false;
        }
        
        if (name.length < 2) {
            showError(nameInput, nameError, 'Vārdam jābūt vismaz 2 rakstzīmes garam');
            return false;
        }
        
        hideError(nameInput, nameError);
        return true;
    }

    function validateEmail() {
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email === '') {
            showError(emailInput, emailError, 'Lūdzu, ievadiet e-pasta adresi');
            return false;
        }
        
        if (!emailRegex.test(email)) {
            showError(emailInput, emailError, 'Lūdzu, ievadiet derīgu e-pasta adresi');
            return false;
        }
        
        hideError(emailInput, emailError);
        return true;
    }

    function validateMessage() {
        const message = messageInput.value.trim();
        
        if (message === '') {
            showError(messageInput, messageError, 'Lūdzu, ievadiet ziņojumu');
            return false;
        }
        
        if (message.length < 10) {
            showError(messageInput, messageError, 'Ziņojumam jābūt vismaz 10 rakstzīmes garam');
            return false;
        }
        
        hideError(messageInput, messageError);
        return true;
    }


    function showError(input, errorElement, message) {
        errorElement.textContent = message;
        errorElement.classList.add('show');
        input.classList.add('error-field');
        

        input.style.animation = 'none';
        setTimeout(() => {
            input.style.animation = 'shake 0.5s ease-in-out';
        }, 10);
    }

    function hideError(input, errorElement) {
        errorElement.classList.remove('show');
        input.classList.remove('error-field');
    }

    function showSuccessMessage() {
        successMessage.classList.add('show');
        

        successMessage.style.animation = 'fadeIn 0.5s ease-in-out';
    }
});