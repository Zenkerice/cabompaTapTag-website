<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "cabompa_nfc"
);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

?>