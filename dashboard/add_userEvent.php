<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["userId"])) {
    echo "You must be logged in to add an event.";
    exit();
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Database connection
    $conn = new mysqli("localhost", "admin", "Poop123", "hitboarddb");
    if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

    // Sanitize inputs
    $userId = $_SESSION["userId"];
    $eventId = isset($_POST["eventId"]) ? intval($_POST["eventId"]) : null;

    if (!$eventId) {
        echo json_encode(["success" => false, "message" => "Invalid event ID."]);
        $conn->close();
        exit();
    }

    // Check if the event already exists in the user's events
    $checkQuery = $conn->prepare("SELECT * FROM usersevents WHERE userId = ? AND eventId = ?");
    $checkQuery->bind_param("ii", $userId, $eventId);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Event already added to your events."]);
        $conn->close();
        exit();
    }

    // Insert the event into the user's events
    $insertQuery = $conn->prepare("INSERT INTO usersevents (userId, eventId) VALUES (?, ?)");
    $insertQuery->bind_param("ii", $userId, $eventId);

    if ($insertQuery->execute()) {
        echo json_encode(["success" => true, "message" => "Event added successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add event: " . $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
