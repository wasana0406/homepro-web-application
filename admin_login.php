<?php
session_start();
include('config.php'); // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admin_login'])) {
  $username = trim($_POST['admin_username']);
  $password = trim($_POST['admin_password']);

  if (!empty($username) && !empty($password)) {
      $sql = "SELECT * FROM admin WHERE username = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
          $admin = $result->fetch_assoc();

          // Direct password match (not secure)
          if ($password === $admin['password']) {
              $_SESSION['admin_id'] = $admin['id'];
              $_SESSION['admin_username'] = $admin['username'];
              header("Location: admin_dashboard.php");
              exit();
          } else {
              echo "<script>alert('Invalid password'); window.history.back();</script>";
          }
      } else {
          echo "<script>alert('Admin not found'); window.history.back();</script>";
      }
  } else {
      echo "<script>alert('Please fill all fields'); window.history.back();</script>";
  }
}
?>