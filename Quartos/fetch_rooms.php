<?php
include '../db.php';

// Fetch room details and their images
$sql = "
    SELECT er.room_name, er.room_number, er.capacity, er.price,id_room, er.Description, ri.image_url 
    FROM exhibit_rooms er 
    LEFT JOIN room_images ri ON er.room_number = ri.room_number 
";
$result = $conn->query($sql);

$rooms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $room_number = $row['room_number'];

        if (!isset($rooms[$room_number])) {
            $rooms[$room_number] = [
                'room_name' => $row['room_name'],
                'room_number' => $row['room_number'],
                'capacity' => $row['capacity'],
                'price' => $row['price'],
                'id_room' => $row['id_room'],
                'Description' => $row['Description'],
                'images' => []
            ];
        }
        $rooms[$room_number]['images'][] = $row['image_url'];
    }
}
echo json_encode(array_values($rooms));

$conn->close();
