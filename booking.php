<?php
session_start();
include 'config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM service_provider WHERE id = $id AND profile_completed = 1";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "Invalid service ID.";
    exit;
}

$service = mysqli_fetch_assoc($result);

// check login
$loggedIn = isset($_SESSION['user_id']);

// Retrieve and clear booking message from session
$bookingMessage = '';
$messageType = ''; // 'success' or 'error'
if (isset($_SESSION['booking_message'])) {
    $bookingMessage = $_SESSION['booking_message'];
    $messageType = $_SESSION['message_type'];
    unset($_SESSION['booking_message']);
    unset($_SESSION['message_type']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Service: <?= htmlspecialchars($service['name']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/booking.css">
    <style>
       
    </style>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container">
        <div class="left-panel">
            <div class="service-header">
                <img src="uploads/<?= htmlspecialchars($service['profile_image']) ?>" alt="Profile image of <?= htmlspecialchars($service['name']) ?>">
                <div class="service-info">
                    <div class="category-tag"><?= htmlspecialchars($service['category']) ?></div>
                    <h2><?= htmlspecialchars($service['name']) ?></h2>
                    <p><strong>Email:</strong> <?= htmlspecialchars($service['email']) ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($service['address']) ?></p>
                    <p><strong>Contact:</strong> <?= htmlspecialchars($service['phone']) ?></p>           
                    <p class="price"><strong>Price: Rs. </strong> <?= htmlspecialchars(number_format($service['price'], 2)) ?></p>
                </div>
            </div>

            <div class="description-section">
                <h3>About This Service</h3>
                <p><?= nl2br(htmlspecialchars($service['description'])) ?></p>
            </div>
        </div>

        <div class="right-panel">
            <?php if ($bookingMessage): ?>
                <div class="booking-message <?= htmlspecialchars($messageType) ?>">
                    <?= htmlspecialchars($bookingMessage) ?>
                </div>
            <?php endif; ?>

            <form class="appointment-form" action="submit-booking.php" method="POST">
                <h2>Book Your Appointment</h2>
                <input type="hidden" name="service_id" value="<?= htmlspecialchars($service['id']) ?>">

                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="date">Preferred Date:</label>
                <input type="date" id="date" name="date" required>

                <label for="time">Preferred Time:</label>
                <input type="time" id="time" name="time" required>

                <button type="submit">Confirm Booking</button>
            </form>
        </div>
    </div>
</body>
</html>