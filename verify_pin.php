<?php
session_start();
include 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $pin = trim($_POST['pin']);
    $user_id = intval($_POST['user_id']);

    // Get the stored hashed PIN
    $query = mysqli_query($conn, "SELECT edit_pin FROM users WHERE id='$user_id'");
    $user = mysqli_fetch_assoc($query);

    if($user){

        if(password_verify($pin, $user['edit_pin'])){

            // PIN correct → allow editing
            $_SESSION['edit_access'] = $user_id;

            header("Location: dashboard.php");
            exit();

        } else {

            // Wrong PIN
            header("Location: profile.php?error=wrongpin");
            exit();

        }

    } else {

        header("Location: profile.php");
        exit();

    }

}
?>