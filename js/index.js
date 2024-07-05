document.addEventListener('DOMContentLoaded', function () {
    const loginTab = document.getElementById("login-tab");
    const signupTab = document.getElementById("signup-tab");
    const loginFormContainer = document.getElementById("login-form-container");
    const signupFormContainer = document.getElementById("signup-form-container");

    loginTab.addEventListener("click", function () {
        loginFormContainer.classList.add("active");
        signupFormContainer.classList.remove("active");
        loginTab.classList.add("active");
        signupTab.classList.remove("active");
    });

    signupTab.addEventListener("click", function () {
        signupFormContainer.classList.add("active");
        loginFormContainer.classList.remove("active");
        signupTab.classList.add("active");
        loginTab.classList.remove("active");
    });
});