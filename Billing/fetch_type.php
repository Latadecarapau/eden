<?php
require '../db.php';

// Get the room type from the request
$room_type = intval($_GET['room_type']);

// SQL query to fetch rooms based on room type
$sql = "SELECT room_name, room_number, capacity, price FROM exhibit_rooms WHERE id_room = $room_type";
$result = $conn->query($sql);

$rooms = array();
if ($result->num_rows > 0) {
    // Fetch each row as an associative array
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}

// Close connection
$conn->close();

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($rooms);
