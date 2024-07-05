

<?php
include "../api/config.php";
// session_start();

$id = $_GET['id'];

$query = "
    SELECT f.id, u.name, u.profile_pic, f.user_id, f.friend_id, f.status
    FROM friends f
    JOIN users u ON u.id = f.user_id
    WHERE (f.friend_id = $id AND f.status = 'pending')
    OR (f.friend_id = $id AND f.status = 'accepted' AND NOT EXISTS (
        SELECT 1
        FROM friends f2
        WHERE f2.user_id = $id AND f2.friend_id = f.user_id AND f2.status = 'accepted'
    ))
";

$result = mysqli_query($connection, $query);

$pendingRequests = [];
while ($row = mysqli_fetch_assoc($result)) {
    $pendingRequests[] = $row;
}

echo json_encode($pendingRequests);
?>


