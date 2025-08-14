
<?php  
include 'config.php';  

$res1 = mysqli_query($conn, "SELECT COUNT(*) AS total_providers FROM service_provider");  
$total_providers = mysqli_fetch_assoc($res1)['total_providers'];  

$res2 = mysqli_query($conn, "SELECT COUNT(*) AS total_customers FROM users WHERE role = 'customer'");  
$total_customers = mysqli_fetch_assoc($res2)['total_customers'];  

$res3 = mysqli_query($conn, "SELECT COUNT(*) AS pending_appointments FROM appointments WHERE status = 'pending'");  
$pending_appointments = mysqli_fetch_assoc($res3)['pending_appointments'];  

$res4 = mysqli_query($conn, "SELECT COUNT(*) AS rejected_appointments FROM appointments WHERE status = 'rejected'");  
$rejected_appointments = mysqli_fetch_assoc($res4)['rejected_appointments'];  

$res5 = mysqli_query($conn, "SELECT COUNT(*) AS completed_appointments FROM appointments WHERE status = 'completed'");  
$completed_appointments = mysqli_fetch_assoc($res5)['completed_appointments'];  
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    body {
      background-color: #f8f9fa;
      overflow-x: hidden;
    }

    .sidebar {
      height: 100vh;
      width: 250px;
      position: fixed;
      top: 0;
      left: -250px;
      background-color: #343a40;
      padding-top: 20px;
      color: white;
      transition: left 0.3s ease;
      z-index: 1050;
    }

    .sidebar.active {
      left: 0;
    }

    .sidebar h4 {
      text-align: center;
      margin-bottom: 20px;
    }

    .sidebar a {
      color: white;
      padding: 12px 20px;
      display: flex;
      align-items: center;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .sidebar a i {
      font-size: 1.4rem;
      min-width: 24px;
      text-align: center;
    }

    .sidebar a:hover {
      background-color: #495057;
    }

    .sidebar a span {
      margin-left: 12px;
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1040;
      display: none;
    }

    .overlay.active {
      display: block;
    }

    .content {
      padding: 30px;
      transition: margin-left 0.3s ease;
    }

    @media (min-width: 992px) {
      .sidebar {
        left: 0;
      }
      .overlay {
        display: none !important;
      }
      .content {
        margin-left: 250px;
      }
      .navbar {
        margin-left: 250px;
      }
    }

    .card:hover {
      transform: scale(1.05);
      transition: 0.3s;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .card i {
      font-size: 2rem;
      margin-bottom: 10px;
    }

    .search-bar {
      width: 300px;
    }

    #sidebarToggle {
      display: inline-block;
    }

    @media (min-width: 992px) {
      #sidebarToggle {
        display: none;
      }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <h4>Hi Admin!</h4>
  <a href="#"><i class="bi bi-speedometer2"></i> <span>Dashboard</span></a>
  <a href="admin_provider.php"><i class="bi bi-person-vcard"></i> <span>Service Providers</span></a>
  <a href="admin_customer.php"><i class="bi bi-people-fill"></i> <span>Customers</span></a>
  <a href="admin_appointment.php"><i class="bi bi-calendar-check"></i> <span>Appointments</span></a>
  <!--<a href="#"><i class="bi bi-gear-wide-connected"></i> <span>Services</span></a> -->
  <a href="admin_report.php"><i class="bi bi-file-earmark-bar-graph"></i> <span>Reports</span></a>
  <a href="logout.php" class="text-danger" onclick="return confirm('Are you sure you want to logout?')">
    <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
  </a>
</div>

<!-- Overlay for mobile -->
<div class="overlay" id="overlay"></div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
  <div class="container-fluid">
    <button class="btn btn-outline-secondary d-lg-none me-2" id="sidebarToggle" aria-label="Toggle sidebar">
      <i class="bi bi-list"></i>
    </button>
    <span class="navbar-brand fw-bold text-primary">Admin Dashboard</span>
    <form class="d-flex ms-auto">
      <input class="form-control me-2 search-bar" type="search" placeholder="Search..." aria-label="Search" />
      <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
    </form>
  </div>
</nav>

<!-- Main Content -->
<div class="content" id="mainContent">
  <h3 class="fw-bold text-secondary mb-4">Dashboard Overview</h3>
  <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-4">
    <div class="col">
      <div class="card bg-success text-white text-center shadow-lg rounded-4">
        <div class="card-body">
          <i class="bi bi-person-badge-fill"></i>
          <h5 class="card-title mt-2">Providers</h5>
          <p class="fs-3 fw-bold"><?php echo $total_providers; ?></p>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card bg-secondary text-white text-center shadow-lg rounded-4">
        <div class="card-body">
          <i class="bi bi-people-fill"></i>
          <h5 class="card-title mt-2">Customers</h5>
          <p class="fs-3 fw-bold"><?php echo $total_customers; ?></p>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card bg-warning text-dark text-center shadow-lg rounded-4">
        <div class="card-body">
          <i class="bi bi-hourglass-split"></i>
          <h5 class="card-title mt-2">Pending</h5>
          <p class="fs-3 fw-bold"><?php echo $pending_appointments; ?></p>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card bg-danger text-white text-center shadow-lg rounded-4">
        <div class="card-body">
          <i class="bi bi-x-circle-fill"></i>
          <h5 class="card-title mt-2">Rejected</h5>
          <p class="fs-3 fw-bold"><?php echo $rejected_appointments; ?></p>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card bg-primary text-white text-center shadow-lg rounded-4">
        <div class="card-body">
          <i class="bi bi-check2-all"></i>
          <h5 class="card-title mt-2">Completed</h5>
          <p class="fs-3 fw-bold"><?php echo $completed_appointments; ?></p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Sidebar toggle script -->
<script>
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const toggleBtn = document.getElementById('sidebarToggle');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
  });

  overlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
  });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>