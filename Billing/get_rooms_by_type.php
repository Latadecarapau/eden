<?php
require '../db.php';

$type_of_room = isset($_GET['type_of_room']) ? $_GET['type_of_room'] : '';

if (!empty($type_of_room)) {
    $stmt = $conn->prepare("SELECT room_number FROM exhibit_rooms WHERE id_room = ?");
    $stmt->bind_param("i", $type_of_room);
    $stmt->execute();
    $result = $stmt->get_result();
    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
    echo json_encode($rooms);
    $stmt->close();
}

$conn->close();
?>
