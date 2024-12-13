document.addEventListener("DOMContentLoaded", () => {
    if (window.location.pathname === "/login/forgot-password.html") {
        window.history.pushState({}, "", "/Forgot-Password");
    }

    document.addEventListener("DOMContentLoaded", () => {
        const forgotPasswordForm = document.getElementById("forgotPasswordForm");

        if (forgotPasswordForm) {
            forgotPasswordForm.addEventListener("submit", (event) => {
                const emailAddress = document.getElementById("emailAddress").value.trim();
                const securityQuestion = document.getElementById("securityQuestion").value;
                const securityAnswer = document.getElementById("securityAnswer").value.trim();

                // Validate inputs
                if (!emailAddress || !securityQuestion || !securityAnswer) {
                    alert("All fields are required.");
                    event.preventDefault();
                    return;
                }

                // SQL Injection Prevention
                const sqlInjectionPattern = /['"=;]/;
                if (sqlInjectionPattern.test(emailAddress) || sqlInjectionPattern.test(securityAnswer)) {
                    alert("One or more fields contain invalid characters.");
                    event.preventDefault();
                    return;
                }

                // Buffer Overflow Prevention
                if (emailAddress.length > 255 || securityAnswer.length > 255) {
                    alert("One or more fields exceed the maximum length of 255 characters.");
                    event.preventDefault();
                    return;
                }
            });
        }
    });
});
