<?php
require '../db.php';

$room_number = isset($_GET['room_number']) ? $_GET['room_number'] : '';

if (!empty($room_number)) {
    $stmt = $conn->prepare("SELECT room_name, price FROM exhibit_rooms WHERE room_number = ?");
    $stmt->bind_param("s", $room_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();
    echo json_encode($room);
    $stmt->close();
}

$conn->close();
?>