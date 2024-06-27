document.addEventListener("DOMContentLoaded", function() {
    const loginButton = document.querySelector('input[type="submit"]');
    
    loginButton.addEventListener("click", function() {
        loginButton.classList.add("btn-clicked");
    });
});
