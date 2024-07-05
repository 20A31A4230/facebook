<?php
// include "./api/config.php";

// && isset($_POST['captcha'] add this in if
include "db.php";


session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action']) {
        // Login
        if ($_POST['action'] == 'login') {
            if (isset($_POST['email']) && isset($_POST['password'])) {

                $email = $_POST['email'];
                $password = $_POST['password'];
                $query = "SELECT * FROM users WHERE email = '$email'";
                $res = mysqli_query($connection, $query);

                if ($row = mysqli_fetch_assoc($res)) {

                    if ($row['email'] != $_POST['email']) {
                        echo json_encode(["status" => "failed", "Error" => "Incorrect Email"]);
                    }
                    if ($row['password'] != $_POST['password']) {
                        echo json_encode(["status" => "failed", "Error" => "Incorrect Password"]);
                    } else {
                        // if ($_SESSION['CODE'] != $_POST['captcha']) {
                        //     echo json_encode(["status" => "failed", "Error" => "Incorrect captcha"]);
                        // } 
                        // else {
                            $_SESSION['user'] = [
                                'email' => $row['email'],
                                "id" => $row['id'],
                                "name" => $row['name'],
                                "image" => $row['profile_pic']
                            ];
                            echo json_encode(["status" => "success", "user" => $row]);
                        // }
                    }
                }
            }
        }
    }
}


// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//     if (isset($_GET['action'])) {
//         if ($_GET['action'] == 'logout') {
//             session_destroy();
//             echo json_encode(["status" => "success"]);
//         }
//     }
// }
