<?php
session_start();
include "db_connect.php"; // Your database connection file

header("Content-Type: application/json"); // ✅ Ensures JSON response

$user_id = $_SESSION['user']; // Get the logged-in user ID

$query = "SELECT * FROM notifications WHERE user_id = ? AND is_read = FALSE ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] =  [
        "message" => $row['message'],
        "timestamp" => $row['created_at']
    ];
}

$stmt->close();
$conn->close();

echo json_encode($notifications);
?>
