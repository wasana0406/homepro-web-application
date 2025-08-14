<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        if ($user['role'] == 'customer') {
            if (isset($_SESSION['appointment_data'])) {
                echo "<script>window.location.href='submit-booking.php';</script>";
                exit();
            } else {
                echo "<script>alert('Login successful'); window.location.href='customer_dashboard.php';</script>";
                exit();// new
            }
        } elseif ($user['role'] == 'service_provider') { // new  provider login
            $provider_sql = "SELECT profile_completed FROM service_provider WHERE id='{$user['id']}'";
            $provider_result = mysqli_query($conn, $provider_sql);
            $provider_data = mysqli_fetch_assoc($provider_result);

            $_SESSION['provider_id'] = $user['id'];

            if ($provider_data && $provider_data['profile_completed'] == 1) {
                echo "<script>alert('Login successful'); window.location.href='provider_dashboard.php';</script>";
                exit();
            } else {
                echo "<script>alert('Please complete your profile'); window.location.href='provider_profile.php';</script>";
                exit();
            }
        }
    } else {
        echo "<script>alert('Invalid email or password!'); window.location.href='login.php';</script>";
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   
</head>
<body>
    <div class="form-container">
        <div class="icon-circle">
            <i class="fas fa-user"></i>
        </div> 
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="singup.php">Sign Up</a></p>
    </div>
</body>
</html>

