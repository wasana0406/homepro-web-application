<?php
$servername = "localhost";
$username = "root"; // Change if needed
$password = "";
$database = "clean_homepro";


$conn = mysqli_connect($servername, $username, $password, $database,4306);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>