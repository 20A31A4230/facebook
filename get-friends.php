<?php
session_start();
include '../api/config.php';

$id = $_SESSION['user']['id'];
$status = $_GET['status'];

// $query ="SELECT *
//           FROM friends
//           JOIN users ON friends.friend_id = users.id
//           WHERE friends.user_id = $id AND friends.status = '$status'";
          
$query =  "SELECT u.id, u.name, u.email, u.profile_pic
          FROM friends f1
          JOIN users u ON f1.friend_id = u.id
          WHERE f1.user_id = $id AND f1.status = 'accepted'
          AND EXISTS (
              SELECT 1
              FROM friends f2
              WHERE f2.user_id = f1.friend_id
              AND f2.friend_id = $id
              AND f2.status = 'accepted'
          )";

$result = mysqli_query($connection, $query);
$friends = [];

while ($row = mysqli_fetch_assoc($result)) {
    $friends[] = $row;
}

// echo json_encode(["msg"=> $id, "status"=> $friends]);
// exit;

echo json_encode($friends);
?>
