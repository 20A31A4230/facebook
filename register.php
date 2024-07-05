<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Main Page</title>
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/axios.js"></script>

</head>
<style>
    h1 {
        text-align: center;
    }
</style>

<?php
// include "navbar.php";
session_start();
?>

<body>

    <div class="container">
        <form id="login_form">
            <h2 class="text-center mb-4">Register Page</h2>
            <input type="hidden" name="action" value="login">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your name" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your Email" required>
                <label for="email">Email</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                <label for="password">Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="number" class="form-control" id="number" name="number" placeholder="Enter your number" required>
                <label for="number">Phone Number</label>
            </div>
            <div class="input-group mb-3">
                <input type="file" name="file" class="form-control" id="file">
            </div>

            <!-- <div class="form-floating mb-3">
                <input type="text" name="captcha" class="form-control" id="captcha" placeholder="Enter your captcha" required>
                <label for="captcha">Captcha</label>
            </div> -->
           
            <!-- <div class="mb-3">
                <img id="captcha_image" style="height:50px; width:auto;margin-top:1rem;" src="./captcha.php">
                <div style="display:inline; font-size:22px;cursor: pointer;" onclick="reload_captcha()"><span>&circlearrowright;</span></div>
            </div> -->
            <button type="button" class="btn btn-primary m-2" onclick="register()">Register</button>
        </form>
    </div>

    <script>
        // function reload_captcha() {
        //     var r = Math.random();
        //     document.getElementById("captcha_image").src = "./captcha.php?rand=" + r;
        // }

        async function register() {
            var form = document.getElementById("login_form");
            console.log(form);

            var form_data = new FormData(form);
            form_data.append("action", "add-user");
            const res = await axios.post("http://localhost/facebook/add-user.php", form_data);
            console.log(res.data);
            const status = await res.data.status;
            console.log(status);
            if (status == "success") {
                window.alert("User added Successfully ! Now you can login.");
                window.location.replace("http://localhost/facebook/login.php");
            } else if (status == "failed") {
                window.alert(res.data.action);
            }
        }
    </script>

</body>

</html>