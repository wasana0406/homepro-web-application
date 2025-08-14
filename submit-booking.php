<?php
session_start();
include 'config.php';

// Redirect to login if the user is not logged in or is not a customer
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'customer') {
    $_SESSION['appointment_data'] = $_POST; // Store submitted data to re-populate after login
    $_SESSION['booking_message'] = 'Please login to book a service.';
    $_SESSION['message_type'] = 'error';
    header('Location: login.php'); // Redirect to login page
    exit();
}

// If redirected back after login, retrieve and clear the stored appointment data
if (isset($_SESSION['appointment_data'])) {
    $_POST = $_SESSION['appointment_data'];
    unset($_SESSION['appointment_data']);
}

// Extract appointment details from the POST request
$customer_id = $_SESSION['user_id'];
$service_id = $_POST['service_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$date = $_POST['date'];
$time = $_POST['time'];

// Get the provider_id from the service_provider table using the service_id
$query = "SELECT id AS provider_id FROM service_provider WHERE id = '$service_id' AND profile_completed = 1";
$result = mysqli_query($conn, $query);

// Handle cases where the service provider is invalid or not found
if (!$result || mysqli_num_rows($result) === 0) {
    $_SESSION['booking_message'] = 'Invalid service provider selected.';
    $_SESSION['message_type'] = 'error';
    header('Location: booking.php?id=' . $service_id); // Redirect back to the booking page
    exit();
}

$row = mysqli_fetch_assoc($result);
$provider_id = $row['provider_id'];

// Crucial: Check if an appointment already exists for this provider on the selected date
$check_duplicate_sql = "SELECT COUNT(*) AS count FROM appointments WHERE provider_id = '$provider_id' AND appointment_date = '$date' AND status IN ('pending', 'approved')";
$duplicate_result = mysqli_query($conn, $check_duplicate_sql);
$duplicate_row = mysqli_fetch_assoc($duplicate_result);

// If an appointment exists for this provider on this date, prevent booking
if ($duplicate_row['count'] > 0) {
    $_SESSION['booking_message'] = 'This provider already has an appointment booked for this date. Please choose another date or provider.';
    $_SESSION['message_type'] = 'error';
    header('Location: booking.php?id=' . $service_id); // Redirect back to the booking page
    exit();
}

// If no existing appointment, insert the new appointment into the database
$insert = "INSERT INTO appointments (customer_id, provider_id, customer_name, customer_email, appointment_date, appointment_time, status)
            VALUES ('$customer_id', '$provider_id', '$name', '$email', '$date', '$time', 'pending')";

// Check if the insertion was successful and provide feedback
if (mysqli_query($conn, $insert)) {
    $_SESSION['booking_message'] = 'Appointment request sent successfully!';
    $_SESSION['message_type'] = 'success';
    header('Location: customer_dashboard.php'); // Redirect to customer dashboard on success
    exit();
} else {
    $_SESSION['booking_message'] = 'Booking failed. Please try again.';
    $_SESSION['message_type'] = 'error';
    header('Location: booking.php?id=' . $service_id); // Redirect back to the booking page
    exit();
}
?>