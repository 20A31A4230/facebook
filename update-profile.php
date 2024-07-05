<?php
session_start();

include './config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_SESSION['user']['id'];
    $name = $_POST['name'];
    $profile_pic_name = $_SESSION['user']['image'];
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $profile_pic = $_FILES['profile_pic'];
        $profile_pic_name = time() . '_' . $profile_pic['name'];
        move_uploaded_file($profile_pic['tmp_name'], '../uploads/' . $profile_pic_name);
    }

    $query = "UPDATE users SET name='$name', profile_pic='$profile_pic_name' WHERE id='$id'";
    if (mysqli_query($connection, $query)) {
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['image'] = $profile_pic_name;
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'failed']);
    }
}
?>
