    document.addEventListener("DOMContentLoaded", () => {
        const tabs = document.querySelectorAll(".tab");
        const buttons = document.querySelectorAll(".menu button");

        buttons.forEach((button) => {
            button.addEventListener("click", () => {
                console.log(`${button.id} clicked!`); //Testing tabs
                tabs.forEach((tab) => tab.classList.remove("active"));
                document.getElementById(button.id.replace("Btn", "")).classList.add("active");
            });
        });

        // Handle event removal
        document.querySelectorAll(".remove-event").forEach((button) => {
            button.addEventListener("click", (event) => {
                const eventId = button.dataset.eventId;
                fetch("remove_event.php", {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify({eventId})
                })
                    .then((response) => response.text())
                    .then((data) => {
                        alert(data);
                        window.location.href = "dashboard.php";
                    })
                    .catch((error) => console.error("Error:", error));
            });
        });

         //Handle adding event  from user-event tab
        document.querySelectorAll(".add-event").forEach((button) => {
            button.addEventListener("click", (event) => {
                const eventId = button.dataset.eventId;
                fetch("add_event.php", {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify({eventId})
                })
                    .then((response) => response.text())
                    .then((data) => {
                        alert(data);
                        window.location.href = "dashboard.php";
                    })
                    .catch((error) => console.error("Error:", error));
            });
        });
        // Handle adding a new event
        document.getElementById("addEventForm").addEventListener("submit", (e) => {
            e.preventDefault();
            console.log("event added!");
            const formData = new FormData(e.target);
            fetch("add_event.php", {
                method: "POST",
                body: formData
            })
                .then((response) => response.text())
                .then((data) => {
                    alert(data);
                    window.location.href = "dashboard.php";
                })
                .catch((error) => console.error("Error:", error));
        });


        //CalendarTab add to user-event
        const calendarButtons = document.querySelectorAll(".cal-add-userEvent");
        calendarButtons.forEach((button) => {
            button.addEventListener("click", () => {
                console.log("Button Clicked!");
                const eventId = button.getAttribute("data-event-id");
                console.log(`Event ID: ${eventId}`);

                // Validate eventId
                if (!eventId) {
                    alert("Invalid event data.");
                    return;
                }

                // Prepare the data to send
                const formData = new FormData();
                formData.append("eventId", eventId);

                // Send the request to add the event
                fetch("add_userEvent.php", {
                    method: "POST",
                    body: formData
                })
                    .then((response) => response.json())
                    .then((data) => {
                        console.log("Server Response:", data);
                        if (data.success) {
                            alert(data.message);
                            window.location.href = "dashboard.php";
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch((error) => {
                        console.error("Fetch Error:", error);
                        alert("An error occurred while adding the event.");
                    });
            });
        });
    });