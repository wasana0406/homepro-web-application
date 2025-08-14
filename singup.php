

<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    
    if (mysqli_query($conn, $sql)) {
        if ($role == 'service_provider') {
            $user_id = mysqli_insert_id($conn);
            session_start();
            $_SESSION['provider_id'] = $user_id;
            $_SESSION['role'] = 'service_provider';
            header("Location: provider_profile.php"); // Provider goes to profile form
            exit();
        } else {
            header("Location: login.php"); // Customer goes to login
            exit();
        }
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="form-container">
    <div class="icon-circle">
            <i class="fas fa-user-plus"></i>
        </div>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="customer">Customer</option>
                <option value="service_provider">Service Provider</option>
            </select>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
