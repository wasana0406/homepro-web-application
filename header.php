

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>HomePro</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

  <style>
    body {
      background-color: #f8f9fa;
    }

    .custom-navbar {
      background-color:rgb(0, 100, 0); /* Dark Green */
    }

    .navbar-brand img {
      width: 85px;
      max-width: 100%;
      height: auto;
      margin-right: 10px;
    }

    .navbar-nav .nav-link {
      font-size: 20px;
      font-weight: bold;
      color: white !important;
    }

    .btn-green {
      font-size: 18px;
      background-color: #198754;
      color: white;
      border: none;
      margin-left: 10px;
      padding: 8px 18px;
      font-weight: bold;
    }

    .btn-green:hover {
      background-color: #157347;
    }

    .admin-icon {
      font-size: 22px;
      color: white;
      margin-left: 15px;
      transition: color 0.3s;
      cursor: pointer;
    }

    .admin-icon:hover {
      color: rgb(73, 240, 73);
    }

    .navbar {
      padding: 0rem 1rem;
    }

    /* Modal Styling */
    .modal-dialog {
      margin: 0 auto;
    }

    .modal-content {
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      background-color: #ffffff;
    }

    .modal-content h4 {
      color: #198754;
    }

    .form-control {
      border-radius: 0.75rem;
      padding: 1rem;
      font-size: 16px;
    }

    .btn-primary {
      background-color: #198754;
      border: none;
      font-weight: bold;
    }

    .btn-primary:hover {
      background-color: #157347;
      box-shadow: 0 0 10px rgba(21, 115, 71, 0.6);
    }

    .form-floating > label {
      color: #6c757d;
    }

    @media (max-width: 576px) {
      .modal-dialog {
        max-width: 90%;
      }
    }
  </style>
</head>
<body>

<!-- NAVIGATION BAR -->
<nav class="navbar navbar-expand-lg shadow-sm custom-navbar">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center me-auto" href="#">
      <img src="css/image/4.png" alt="Logo" />
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="service.php">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
      </ul>

      <!-- Mobile Buttons -->
      <div class="d-lg-none mt-3 px-3 w-100">
        <div class="d-flex mobile-buttons align-items-center justify-content-center">
          <a href="login.php" class="btn btn-green w-100">Login / Sign Up</a>
          <span class="admin-icon" data-bs-toggle="modal" data-bs-target="#adminLoginModal">
            <i class="fas fa-user-shield"></i>
          </span>
        </div>
      </div>
    </div>

    <!-- Desktop Buttons -->
    <div class="d-none d-lg-flex ms-auto align-items-center">
      <a href="login.php" class="btn btn-green">Login / Sign Up</a>
      <span class="admin-icon" data-bs-toggle="modal" data-bs-target="#adminLoginModal">
        <i class="fas fa-user-shield"></i>
      </span>
    </div>
  </div>
</nav>


<!-- ADMIN LOGIN MODAL -->
<div class="modal fade" id="adminLoginModal" tabindex="-1" aria-labelledby="adminLoginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px; width: 90%;">


  
<div class="modal-content">

<button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
      <form action="admin_login.php" method="POST">
        <div class="mb-4 text-center">
          <i class="fas fa-user-shield fa-3x text-primary mb-2"></i>
          <h4 class="fw-bold">Admin Login</h4>
        </div>

        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="admin_username" name="admin_username" placeholder="Username" required />
          <label for="admin_username">Username</label>
        </div>

        <div class="form-floating mb-4">
          <input type="password" class="form-control" id="admin_password" name="admin_password" placeholder="Password" required />
          <label for="admin_password">Password</label>
        </div>

        <button type="submit" name="admin_login" class="btn btn-primary w-100 rounded-pill py-2">
          Login
        </button>

        <small class="d-block text-center mt-3 text-muted fst-italic">Admin access only</small>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>