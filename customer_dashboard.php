<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT name FROM users WHERE id = '$customer_id'");
$customer = mysqli_fetch_assoc($result);
$customer_name = $customer['name'] ?? 'User';
$customer_initial = strtoupper(substr($customer_name, 0, 1));

function getAvatarColor($name) {
    $colors = ['#ffc107', '#0d6efd', '#198754', '#dc3545', '#6610f2', '#20c997', '#fd7e14', '#6f42c1'];
    $hash = crc32($name);
    $index = $hash % count($colors);
    return $colors[$index];
}

$avatar_color = getAvatarColor($customer_name);

$query = mysqli_query($conn, "
    SELECT a.id, a.appointment_date, a.appointment_time, a.status,
           sp.category AS service_name, sp.name AS provider_name
    FROM appointments a
    JOIN service_provider sp ON a.provider_id = sp.id
    WHERE a.customer_id = '$customer_id'
    ORDER BY a.appointment_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Customer Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
      padding-top: 70px; /* navbar height */
    }

    .navbar {
      background-color: #0B3D91 !important; /* Deep Navy Blue */
    }

    .avatar-circle {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      color: #fff;
      font-weight: 600;
      font-size: 18px;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: <?= $avatar_color ?>;
      user-select: none;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    /* Appointment card */
    .appointments-card {
      border-radius: 0.5rem;
      box-shadow: 0 0.125rem 0.25rem rgb(0 0 0 / 0.075);
      background-color: #fff;
      padding: 1.5rem;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <div class="avatar-circle me-2"><?= $customer_initial ?></div>
      <span class="fw-semibold"><?= htmlspecialchars($customer_name) ?></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav ms-auto align-items-center gap-3">
        <li class="nav-item">
          <a class="nav-link fw-medium fs-6" href="#appointments">📅 Appointments</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-light fw-semibold" href="logout.php" onclick="return confirmLogout()">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-4">

  <?php if (isset($_SESSION['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
      <?= htmlspecialchars($_SESSION['msg']); unset($_SESSION['msg']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- Appointments Section -->
  <section id="appointments" class="appointments-card">
    <h3 class="mb-4 text-primary fw-bold border-bottom pb-2">My Appointments</h3>

    <?php if (mysqli_num_rows($query) > 0): ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light text-uppercase small">
            <tr>
              <th>Provider</th>
              <th>Service</th>
              <th>Date & Time</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)): 
              $status = strtolower($row['status']);
              $badgeClass = match($status) {
                'pending' => 'warning',
                'completed' => 'success',
                'rejected' => 'danger',
                'cancelled' => 'secondary',
                default => 'secondary',
              };
            ?>
            <tr>
              <td><strong><?= htmlspecialchars($row['provider_name']) ?></strong></td>
              <td><?= htmlspecialchars($row['service_name']) ?></td>
              <td>
                <div><?= date('M d, Y', strtotime($row['appointment_date'])) ?></div>
                <small class="text-muted"><?= date('h:i A', strtotime($row['appointment_time'])) ?></small>
              </td>
              <td>
                <span class="badge bg-<?= $badgeClass ?> text-capitalize"><?= htmlspecialchars($row['status']) ?></span>
              </td>
              <td class="text-center">
                <?php if ($status === 'pending'): ?>
                  <a href="customer_cancel_appointment.php?id=<?= $row['id'] ?>" 
                     class="btn btn-outline-danger btn-sm" title="Cancel Appointment" 
                     onclick="return confirm('Are you sure you want to cancel this appointment?');">
                    <i class="bi bi-x-circle"></i> Cancel
                  </a>
                <?php elseif ($status === 'completed'): ?>
                  <span class="text-success fw-semibold">Completed</span>
                <?php elseif ($status === 'rejected'): ?>
                  <span class="text-danger fw-semibold">Rejected</span>
                <?php else: ?>
                  <span class="text-muted fst-italic">Cancelled</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-center text-muted fst-italic mb-0">You have no appointments yet.</p>
    <?php endif; ?>
  </section>

</div>

<script>
  function confirmLogout() {
    return confirm("Are you sure you want to logout?");
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
