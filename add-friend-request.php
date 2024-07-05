<?php
include "../api/config.php";
session_start();

$user_id = $_SESSION['user']['id'];
$friend_id = $_POST['friend_id'];

$query = "INSERT INTO friends (user_id, friend_id, status) VALUES ($user_id, $friend_id, 'pending')";
$result = mysqli_query($connection, $query);

$response = [];
if ($result) {
    $response['status'] = 'success';
} else {
    $response['status'] = 'failed';
    $response['error'] = mysqli_error($connection);
}

echo json_encode($response);
?>
