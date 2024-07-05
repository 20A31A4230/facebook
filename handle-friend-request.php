<?php
session_start();
include "../api/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = $_POST['user_id'];
    $friend_id = $_POST['friend_id'];
    $status = $_POST['status'];

    echo json_encode(["msg" => $status, $user_id, $friend_id]);
    exit;
    if ($status == 'accepted') {
        $query = "UPDATE friends SET status = 'accepted' WHERE user_id = $user_id AND friend_id = $friend_id";
    } elseif ($status == 'rejected') {
        $query = "DELETE FROM friends WHERE user_id = $friend_id AND friend_id = $user_id";
    }

    if (mysqli_query($connection, $query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($connection)]);
    }
}
?>
