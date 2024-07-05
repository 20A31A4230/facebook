<?php
// Include database configuration
include 'config.php';

// Check if 'id' parameter exists in the GET request
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    // echo json_encode(["msg" => $user_id]);
    // exit;
    // Query to fetch user details based on user_id

    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = mysqli_query($connection, $query);
    if ($result) {
        $user = mysqli_fetch_assoc($result);
        // Return user details as JSON response
        echo json_encode(["status" => "success", "msg" =>$user]);
    } else {
        // Handle query error
        // http_response_code(500);
        echo json_encode(array("status" => "failed",'error' => 'Failed to fetch user details.'));
    }
} else {
    // Handle missing 'id' parameter
    // http_response_code(400);
    echo json_encode(array('error' => 'Missing user ID.'));
}

// Close database connection
// mysqli_close($connection);
?>
