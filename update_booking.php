<?php
session_start();
include 'config.php';

if (!isset($_SESSION['provider_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action']; // accept / reject
    $customer_email = $_POST['email'];
    $customer_name = $_POST['name'];

    // Booking status set
    if ($action == 'accept') {
        $new_status = 'completed';
    } elseif ($action == 'reject') {
        $new_status = 'rejected';
    } else {
        header("Location: provider_dashboard.php");
        exit();
    }

    // Update booking status
    $update = mysqli_query($conn, "UPDATE appointments SET status = '$new_status' WHERE id = '$booking_id'");

    if ($update) {
        // Future: Send email or message to customer here.
        // Redirect back to dashboard
        header("Location: provider_dashboard.php");
        exit();
    } else {
        echo "Error updating booking.";
    }
}
?>