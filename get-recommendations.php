<?php
session_start();
include '../api/config.php';

// $email = $_SESSION['user']['email'];

$current_user_id = $_GET['id'];
// echo json_encode(["msg" => $id]);
// exit;

// $query = "SELECT * FROM users WHERE email <> '$email'";

// $query = "SELECT u.id, u.name, u.email, u.profile_pic
//         FROM users u
//         WHERE u.id <> $id
//         AND u.id NOT IN (
//             SELECT f.friend_id
//             FROM friends f
//             WHERE f.user_id = $id
//             AND f.status = 'accepted'
//         )";

// $query = "SELECT u.id, u.name, u.email, u.profile_pic
//         FROM users u
//         WHERE u.id <> $id
//         AND u.id NOT IN (
//             SELECT f.friend_id
//             FROM friends f
//             WHERE f.user_id = $id
//         )";


$query = "SELECT u.id, u.name, u.email, u.profile_pic
          FROM users u
          WHERE u.id <> $current_user_id
          AND u.id NOT IN (
              SELECT f.friend_id
              FROM friends f
              WHERE f.user_id = $current_user_id AND f.status = 'accepted'
          )
          AND u.id NOT IN (
              SELECT f.user_id
              FROM friends f
              WHERE f.friend_id = $current_user_id AND f.status = 'accepted'
          )
          AND u.id NOT IN (
              SELECT f.friend_id
              FROM friends f
              WHERE f.user_id = $current_user_id AND f.status = 'pending'
          )
          AND u.id NOT IN (
              SELECT f.user_id
              FROM friends f
              WHERE f.friend_id = $current_user_id AND f.status = 'pending'
          )";

$result = mysqli_query($connection, $query);

$recommendations = [];
while ($row = mysqli_fetch_assoc($result)) {
    $recommendations[] = $row;
}

echo json_encode($recommendations);
?>
