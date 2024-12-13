document.addEventListener("DOMContentLoaded", () => {
    // Clean URL logic: Change "/login/login.html" to "/login"
    if (window.location.pathname === "/login/login.html") {
        window.history.pushState({}, "", "/Login");
    }

    // Login form validation
    const loginForm = document.getElementById("loginForm");

    if (loginForm) {
        loginForm.addEventListener("submit", (event) => {
            const fields = ["email", "password"];
            let isValid = true;

            fields.forEach((field) => {
                const input = document.getElementById(field);

                // SQL Injection Prevention
                const sqlInjectionPattern = /['"=;]/;
                if (sqlInjectionPattern.test(input.value)) {
                    alert(`${field} contains invalid characters. Please remove them.`);
                    isValid = false;
                }

                // Buffer Overflow Prevention
                if (input.value.length > 255) {
                    alert(`${field} is too long. Maximum length is 255 characters.`);
                    isValid = false;
                }
            });

            if (!isValid) {
                event.preventDefault(); // Prevent form submission
            }
        });
    }
});
