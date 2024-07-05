<?php
// include "./api/config.php";
include "./db.php";

session_start();
//  Db connection to add-user in user table 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action']) {


        // echo json_encode(["msg" => $_POST, "file" => $_FILES['file']['name']]);
        // exit;
        // echo json_encode(["msg" => $_POST, "file" => $target_file]);
        // exit;

        // if ($_SESSION['CODE'] == $_POST['captcha']) {
            if ($_POST['action'] == 'add-user') {
                // insert query here
                $file = $_FILES['file']['name'];
                $target_dir = 'uploads/';
                $target_file = $target_dir . $file;
                if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {

                    $name = $_POST['username'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $phone = $_POST['number'];
                    $query = "INSERT INTO users (name, email, password, phone_no, profile_pic) VALUES ('$name', '$email', '$password', '$phone', '$file')";
                    // echo json_encode(["msg" => $query]);
                    // exit;
                    $result = mysqli_query($connection, $query);
                  
                    if ($result) {
                        $_SESSION['user'] = [
                            "email" => $email,
                            "name" => $name,
                        ];
                        echo json_encode(["status" => "success", "action" => "User added Successfully!! Now you can login"]);
                    } else {
                        $error =  mysqli_error($connection);
                        echo json_encode(["status" => "failed", "action" => $error]);
                    }
                }
                else{
                    echo json_encode(["status" => "failed", "action" => "File not Uploaded"]);
                }
            } 
            else {
                echo  json_encode(["status" => "failed", "action" => mysqli_error($connection)]);
            }

        // else {
        //     echo json_encode(["status" => "failed", "action" => "Incorrect Captcha"]);
        // }
    }
}
