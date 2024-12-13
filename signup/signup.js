document.addEventListener("DOMContentLoaded", () => {
    // Clean URL logic: Change "/signup/signup.html" to "/signup"
    if (window.location.pathname === "/signup/signup.html") {
        window.history.pushState({}, "", "/Signup");
    }
})
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("successModal").style.display = "block";
});

document.addEventListener("DOMContentLoaded", () => {
    const signupForm = document.getElementById("signupForm");

    if (signupForm) {
        signupForm.addEventListener("submit", (event) => {
            event.preventDefault();

            // Gather form data
            const formData = new FormData(signupForm);

            // AJAX submission using fetch
            fetch("signup/process_signup.php", {
                method: "POST",
                body: formData
            })
                .then((response) => response.text())
                .then((data) => {
                    const notification = document.getElementById("notification");

                    // Show notification
                    notification.style.display = "block";
                    notification.textContent = data;

                    if (data.includes("Signup successful!")) {
                        notification.className = "success"; // Apply the success class
                        notification.textContent = "Success, return to login page.";
                        notification.style.display = "block";

                        // Redirect to login.html after 3 seconds
                        setTimeout(() => {
                            window.location.href = "../login/login.html";
                        }, 3000);
                    } else {
                        notification.style.color = "red";
                        notification.style.border = "2px solid red";
                        notification.style.padding = "10px";
                        notification.style.borderRadius = "5px";
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("Something went wrong!");
                });
        });
    }
});
