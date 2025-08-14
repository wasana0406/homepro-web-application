


<?php
session_start();
include('config.php'); // DB connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['user_id'];

// Get appointment ID from URL
if (isset($_GET['id'])) {
    $appointment_id = intval($_GET['id']);

    // Verify this appointment belongs to this customer and is still pending
    $check_query = mysqli_query($conn, "
        SELECT * FROM appointments 
        WHERE id = '$appointment_id' AND customer_id = '$customer_id' AND status = 'Pending'
    ");

    if (mysqli_num_rows($check_query) > 0) {
        // Cancel the appointment
        $cancel_query = mysqli_query($conn, "
            UPDATE appointments SET status = 'cancelled' 
            WHERE id = '$appointment_id'
        ");

        if ($cancel_query) {
            $_SESSION['msg'] = "Appointment cancelled successfully.";
        } else {
            $_SESSION['msg'] = "Failed to cancel appointment. Please try again.";
        }
    } else {
        $_SESSION['msg'] = "Invalid appointment or already processed.";
    }
} else {
    $_SESSION['msg'] = "No appointment selected.";
}

// Redirect back to dashboard
header("Location: customer_dashboard.php");
exit();
?>






