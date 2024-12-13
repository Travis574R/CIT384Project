<?php
session_start();
if (!isset($_SESSION["userId"])) {
    echo "You must be logged in to add an event.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "admin", "Poop123", "hitboarddb");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $userId = $_SESSION["userId"];
    $eventName = $conn->real_escape_string($_POST["eventName"]);
    $eventDate = $conn->real_escape_string($_POST["eventDate"]);
    $eventLocation = $conn->real_escape_string($_POST["eventLocation"]);

    // Check if the event already exists
    $eventCheckQuery = $conn->prepare("SELECT eventId FROM eventstable WHERE eventName = ? AND eventDate = ?");
    $eventCheckQuery->bind_param("ss", $eventName, $eventDate);
    $eventCheckQuery->execute();
    $eventCheckResult = $eventCheckQuery->get_result();

    if ($eventCheckResult->num_rows > 0) {
        // Event exists, get its ID
        $event = $eventCheckResult->fetch_assoc();
        $eventId = $event["eventId"];
    } else {
        // Event does not exist, insert it into EventsTable
        $insertEventQuery = $conn->prepare("INSERT INTO eventstable (eventName, eventDate, eventLocation, createdBy) VALUES (?, ?, ?, ?)");
        $insertEventQuery->bind_param("sssi", $eventName, $eventDate, $eventLocation, $userId);

        if ($insertEventQuery->execute()) {
            $eventId = $conn->insert_id;
        } else {
            echo "Error adding event: " . $conn->error;
            exit();
        }
    }

    // Add the event to the user's events
    $userEventQuery = $conn->prepare("INSERT INTO usersevents (userId, eventId) VALUES (?, ?)");
    $userEventQuery->bind_param("ii", $userId, $eventId);

    if ($userEventQuery->execute()) {
        echo "Event added successfully!";
    } else {
        echo "Error adding event to your events: " . $conn->error;
    }

    $conn->close();
}
?>
