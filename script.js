document.addEventListener("DOMContentLoaded", () => {
    // Clean URL logic: Change "/login/login.html" to "/login"
    if (window.location.pathname === "/index.html") {
        window.history.pushState({}, "", "/Welcome");
    }

// Add event listeners to all links on the page
    document.addEventListener("DOMContentLoaded", () => {
        // Select all anchor elements
        const links = document.querySelectorAll("a");

        // Add hover effects to each link
        links.forEach(link => {
            // When the mouse enters the link
            link.addEventListener("mouseenter", () => {
                link.style.fontWeight = "bold";
                link.style.textDecoration = "underline";
            });

            // When the mouse leaves the link
            link.addEventListener("mouseleave", () => {
                link.style.fontWeight = "normal";
                link.style.textDecoration = "none";
            });
        });
    });
});
