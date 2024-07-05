<?php
session_start();
include '../api/config.php';

$id = $_SESSION['user']['id'];
$query = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($connection, $query);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode($row);
} else {
    echo json_encode(['status' => 'failed']);
}
?>
