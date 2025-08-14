<?php
session_start();
include 'config.php'; 

if (!isset($_SESSION['provider_id'])) {
    header("Location: login.php");
    exit();
}

$provider_id = $_SESSION['provider_id'];

// Provider ගේ වත්මන් තත්ත්වය සහ admin_message දත්ත සමුදායෙන් ලබා ගන්න
$stmt_status = $conn->prepare("SELECT status, admin_message FROM service_provider WHERE id = ?");
$stmt_status->bind_param("i", $provider_id);
$stmt_status->execute();
$result_status = $stmt_status->get_result();
$provider_status = '';
$admin_message = ''; // Admin message variable එක initialize කරන්න

if ($result_status->num_rows > 0) {
    $row_status = $result_status->fetch_assoc();
    $provider_status = $row_status['status'];
    $admin_message = $row_status['admin_message']; // මෙතැනින් admin_message එක ලබා ගනී
} else {
    // service_provider table එකේ record එකක් නැත්නම්,
    // (ප්‍රොෆයිල් සම්පූර්ණ කර නැතිනම්)
    // profile complete කරන පිටුවට යොමු කරන්න.
    header("Location: provider_profile.php");
    exit();
}
$stmt_status->close();

// Admin අනුමැතිය ලැබෙන තෙක් dashboard එකේ ක්‍රියාකාරීත්වය සීමා කරන්න
if ($provider_status != 'approved') {
    // Provider dashboard එකේ අන්තර්ගතය මෙතැනින් පටන් ගන්න
    // නමුත් ක්‍රියාකාරී බොත්තම් සහ links disabled කරන්න.
    // නැතහොත්, වෙනම පණිවිඩයක් පමණක් පෙන්වන පිටුවකට යොමු කරන්න.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Provider Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            font-family: 'Segoe UI', sans-serif;
            font-size: 1.1rem;
        }
        .container {
            text-align: center;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .alert {
            padding: 20px;
            border-radius: 8px;
            font-size: 1.1rem;
        }
        .alert h4 {
            font-size: 1.4rem;
            margin-bottom: 15px;
            color: inherit;
        }
        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .btn {
            font-size: 1rem;
            padding: 10px 20px;
        }
    </style>
</head>
<body>


    <div class="container">
        <?php if ($provider_status == 'pending'): ?>
            <div class="alert alert-warning" role="alert">
                <h4><i class="bi bi-hourglass-split"></i> Profile Awaiting Approval</h4>
                <p>Your profile is currently under review by the administrator. You will gain full access once it is approved.</p>
                <p>Thank you for your patience!</p>
                <a href="#" class="btn btn-secondary mt-3">Review My Profile Details</a>
            </div>

          
        <?php elseif ($provider_status == 'rejected'): ?>
            <div class="alert alert-danger" role="alert">
                <h4><i class="bi bi-x-circle-fill"></i> Profile Rejected</h4>
                <p>Unfortunately, your profile has been rejected by the administrator.</p>
                <?php if (!empty($admin_message)): ?>
                    <p>Reason: <?php echo htmlspecialchars($admin_message); ?></p>
                <?php else: ?>
                    <p>Please update your profile information and resubmit for review.</p>
                <?php endif; ?>
                <a href="#" class="btn btn-primary mt-3">Update Profile</a>
            </div>
        <?php else: // වෙනත් නොදන්නා status එකක් තිබේ නම්
            ?>


            <div class="alert alert-info" role="alert">
                <h4><i class="bi bi-info-circle-fill"></i> Account Status</h4>
                <p>There is an issue with your account status. Please contact support.</p>
            </div>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-outline-secondary mt-4">Logout</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    exit(); // Dashboard එකේ ඉතිරි කොටස load වීම නවත්වන්න
}
// ----------------------------------------------------
// මේකෙන් පහළට ඔබගේ provider_dashboard.php එකේ සාමාන්‍ය අන්තර්ගතය එනු ඇත.
// මේ කොටස ක්‍රියාත්මක වන්නේ $provider_status == 'approved' නම් පමණයි.
// ----------------------------------------------------
?>
<?php
if (!isset($_SESSION['provider_id'])) {
    header("Location: login.php");
    exit();
}

$provider_id = $_SESSION['provider_id'];

// Fetch provider data
$query = mysqli_query($conn, "SELECT * FROM service_provider WHERE id = '$provider_id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Service Provider Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body, html {
      height: 100%;
      margin: 0;
      background-color: #f8f9fa;
    }
    .dashboard-container {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }
    .sidebar {
      min-width: 280px;
      background-color: #343a40;
      color: white;
      padding: 2rem 1.5rem;
      display: flex;
      flex-direction: column;
      position: sticky;
      top: 0;
      height: 100vh;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
    }
    .sidebar a:hover {
      color: #ffc107;
    }
    .sidebar ul {
      padding-left: 0;
      list-style: none;
    }
    .main-content {
      flex-grow: 1;
      overflow-y: auto;
      padding: 2rem;
    }
    .card {
      margin-bottom: 2rem;
    }
    .profile-img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    .profile-img:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    .btn-outline-primary {
      transition: all 0.3s ease-in-out;
    }
    .btn-outline-primary:hover {
      background-color: #0d6efd;
      color: white;
      border-color: #0d6efd;
    }
  </style>
</head>
<body>

<div class="dashboard-container">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div>
      <h4 class="mb-4">Dashboard</h4>
      <ul class="nav flex-column">
        <li class="nav-item mb-2"><a href="#" class="nav-link p-0">My Profile</a></li>
        <li class="nav-item mb-2"><a href="#" class="nav-link p-0">Bookings</a></li>
        <li class="nav-item mb-2"><a href="#" class="nav-link p-0">Reviews</a></li>
        <li class="nav-item mt-3"><a href="logout.php" class="nav-link text-danger p-0" onclick="return confirmLogout()">Logout</a></li>
      </ul>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="main-content">

    <!-- Profile -->
    <div id="profile" class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">My Profile</h5>
      </div>
      <div class="card-body">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center">
          <div class="me-md-4 mb-3 mb-md-0 text-center">
            <?php if (!empty($data['profile_image'])): ?>
              <img src="uploads/<?= htmlspecialchars($data['profile_image']) ?>" class="profile-img border border-3 border-white shadow-sm" alt="Profile Image" />
            <?php else: ?>
              <div class="profile-img bg-secondary d-flex justify-content-center align-items-center text-white">
                No Image
              </div>
            <?php endif; ?>
          </div>
          <div>
            <h4 class="fw-bold"><?= htmlspecialchars($data['name']) ?></h4>
            <ul class="list-unstyled">
              <li><strong>Name:</strong> <?= htmlspecialchars($data['name']) ?></li>
              <li><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></li>
              <li><strong>Phone:</strong> <?= htmlspecialchars($data['phone']) ?></li>
              <li><strong>Address:</strong> <?= htmlspecialchars($data['address']) ?></li>
              <li><strong>Category:</strong> <?= htmlspecialchars($data['category']) ?></li>
              <li><strong>Experience:</strong> <?= htmlspecialchars($data['experience']) ?> years</li>
              <li><strong>Description:</strong> <?= htmlspecialchars($data['description']) ?></li>
              <li><strong>Service Price:</strong> Rs. <?= htmlspecialchars($data['price']) ?></li>
            </ul>
            <a href="provider_edit_profile.php" class="btn btn-outline-primary btn-sm mt-2">Edit Profile</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bookings -->
    <div id="bookings" class="card">
      <div class="card-header bg-warning text-dark">Customer Bookings</div>
      <div class="card-body table-responsive">
        <table class="table table-bordered align-middle mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Customer</th>
              <th>Email</th>
              <th>Service</th>
              <th>Date & Time</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $appointments = mysqli_query($conn, "
            SELECT a.*, sp.category AS service_name
            FROM appointments a
            LEFT JOIN service_provider sp ON a.provider_id = sp.id
            WHERE a.provider_id = '$provider_id'
            ORDER BY a.id DESC
          ");

          while ($row = mysqli_fetch_assoc($appointments)) {
          ?>
            <tr>
              <td><?= htmlspecialchars($row['customer_id']) ?></td>
              <td><?= htmlspecialchars($row['customer_name']) ?></td>
              <td><?= htmlspecialchars($row['customer_email']) ?></td>
              <td><?= htmlspecialchars($row['service_name']) ?></td>
              <td><?= htmlspecialchars($row['appointment_date']) ?> <?= htmlspecialchars($row['appointment_time']) ?></td>
              <td><?= htmlspecialchars($row['status']) ?></td>
              <td>
                <?php if (strtolower($row['status']) == 'pending'): ?>
                  <form method="POST" action="update_booking.php" class="d-flex gap-2">
                    <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($row['customer_email']) ?>">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($row['customer_name']) ?>">
                    <button type="submit" name="action" value="accept" class="btn btn-sm btn-success">Accept</button>
                    <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>
                  </form>
                <?php else: ?>
                  <span class="badge bg-secondary"><?= ucfirst($row['status']) ?></span>
                <?php endif; ?>
              </td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </div>

  </main>

</div>

<script>
  function confirmLogout() {
    return confirm("Are you sure you want to logout?");
  }
</script>

</body>
</html>
