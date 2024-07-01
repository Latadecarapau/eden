<?php
require '../db.php';

if (isset($_GET['room_number'])) {
    $room_number = $_GET['room_number'];

    // Prepare the SQL query to get room details based on the room number
    $stmt = $conn->prepare("SELECT capacity, price FROM exhibit_rooms WHERE room_number = ?");
    $stmt->bind_param("s", $room_number); // Assuming room_number is a string
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch room details
    if ($room = $result->fetch_assoc()) {
        echo json_encode($room);
    } else {
        echo json_encode(['error' => 'Room not found']);
    }

    $stmt->close();
}

$conn->close();
?>
