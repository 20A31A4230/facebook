<?php
include "../api/config.php";

$current_user_id = $_GET['id'];

// echo json_encode(["msg" => $current_user_id]);
// exit;
//all requests where the current user is the user_id and the status is pending
// $query = "SELECT f.id, u.id as user_id, u.name, u.email, u.profile_pic
//           FROM friends f
//           JOIN users u ON f.user_id = u.id
//           WHERE f.user_id = $current_user_id AND f.status = 'pending'";

$query = "SELECT u.id, u.name, u.email, u.profile_pic
          FROM friends f
          JOIN users u ON f.friend_id = u.id
          WHERE f.user_id = $current_user_id AND f.status = 'pending'";

$result = mysqli_query($connection, $query);

$requests = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $requests[] = $row;
    }
}

echo json_encode($requests);
?>
