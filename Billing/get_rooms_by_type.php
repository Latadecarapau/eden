<?php
require '../db.php';

if (isset($_GET['type_of_room'])) {
    $type_of_room = $_GET['type_of_room'];

    // Prepare the SQL query to get room numbers based on the room type
    $stmt = $conn->prepare("SELECT room_number FROM exhibit_rooms WHERE id_room = ?");
    $stmt->bind_param("i", $type_of_room); // Assuming id_room is an integer
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch room numbers
    $rooms = [];
    while ($room = $result->fetch_assoc()) {
        $rooms[] = $room['room_number'];
    }

    // Return the room numbers as a JSON response
    echo json_encode($rooms);
    
    $stmt->close();
}

$conn->close();
?>
