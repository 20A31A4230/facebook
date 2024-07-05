<!-- <?php
include "../api/config.php";
// session_start();

$id = $_GET['id'];

echo json_encode(['msg' => $id]);
exit;

$query = "
    SELECT f.id, u.name, u.profile_pic, f.user_id, f.friend_id
    FROM friends f
    JOIN users u ON (u.id = f.user_id OR u.id = f.friend_id)
    WHERE (f.user_id = $id OR f.friend_id = $id) AND f.status = 'pending'
";

$result = mysqli_query($connection, $query);

$pendingRequests = [];
while ($row = mysqli_fetch_assoc($result)) {
    if ($row['user_id'] != $id) {
        $row['requester'] = true; // friend request sent to the current user
    } else {
        $row['requester'] = false; // friend request sent by the current user
    }
    $pendingRequests[] = $row;
}

echo json_encode($pendingRequests);
?> -->



<!-- <?php
        session_start();
        include '../api/config.php';

        $id = $_SESSION['user']['id'];

        // $query = "SELECT * FROM friends JOIN users ON friends.user_id = users.id WHERE friends.friend_id = $id AND friends.status = 'pending'";

        $query = "SELECT *
          FROM friends
          JOIN users ON friends.friend_id = users.id
          WHERE friends.user_id = $id AND friends.status = 'pending'";

        $result = mysqli_query($connection, $query);

        $pendingRequests = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $pendingRequests[] = $row;
        }

        echo json_encode($pendingRequests);
        ?> -->