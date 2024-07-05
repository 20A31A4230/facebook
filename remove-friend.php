<?php
include "../api/config.php";
session_start();

$user_id = $_SESSION['user']['id'];
$friend_id = $_POST['friend_id'];
// echo json_encode(['msg' => $user_id, $friend_id]);
// exit;

$query1 = "DELETE FROM friends WHERE (user_id = $user_id AND friend_id = $friend_id) OR (user_id = $friend_id AND friend_id = $user_id)";
$result1 = mysqli_query($connection, $query1);

$response = [];
if ($result1) {
    $response['status'] = 'success';
} else {
    $response['status'] = 'failed';
    $response['error'] = mysqli_error($connection);
}

echo json_encode($response);
?>
