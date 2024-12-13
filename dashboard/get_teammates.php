<?php
function getTeammates($conn, $eventId, $currentUserId) {
    // Prepare the query to fetch teammates for the given event, excluding the current user
    $query = "
        SELECT CONCAT(users.firstName, ' ', users.lastName) AS teammateName
        FROM usersevents AS ue
        JOIN userstable AS users ON ue.userId = users.id
        WHERE ue.eventId = ? AND ue.userId != ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $eventId, $currentUserId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Collect teammates' names into an array
    $teammates = [];
    while ($row = $result->fetch_assoc()) {
        $teammates[] = $row['teammateName'];
    }

    // Return a comma-separated list of teammates or "No teammates" if none found
    return !empty($teammates) ? implode(", ", $teammates) : "No teammates";
}
?>
