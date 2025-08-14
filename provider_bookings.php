
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $booking_id = $_POST['booking_id'];
  $action = $_POST['action'];

  // Example: Connect to DB and update booking status
  // $conn = new mysqli('localhost', 'username', 'password', 'database');
  // $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
  // $stmt->bind_param("si", $action, $booking_id);
  // $stmt->execute();
  // $stmt->close();
  // $conn->close();

  echo "Booking #$booking_id has been " . ($action === 'accept' ? 'accepted' : 'rejected') . ".";
  // Redirect or show confirmation
}
?>
