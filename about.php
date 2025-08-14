

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Home Services</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/about.css">
    
    <!-- Header/Navbar -->
    <?php include('header.php'); ?>   
</head>
<body>



<!-- Main Content -->
<div class="container py-5">
    <h1 class="text-center section-title">About Us</h1>
    <p class="text-center subtext">We’re committed to connecting you with trusted professionals for all your home service needs.</p>

    <div class="about-section row align-items-center mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <img src="image/about.png" class="img-fluid rounded" alt="About Home Service">
        </div>
        <div class="col-lg-6">
            <h3><i class="fas fa-hands-helping about-icon"></i>Our Mission</h3>
            <p>To simplify home maintenance by providing a one-stop solution for booking trusted, professional services from the comfort of your home.</p>

            <h3 class="mt-4"><i class="fas fa-bolt about-icon"></i>Why Choose Us</h3>
            <ul class="about-text">
                <li>Reliable and verified service providers</li>
                <li>Easy booking process</li>
                <li>Affordable pricing</li>
                <li>Fast customer support</li>
            </ul>
        </div>
    </div>

    <div class="team-section text-center">
        <h2 class="mb-5">Meet Our Team</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="team-card p-3">
                    <img src="image/about1.jpg" alt="Team Member 1" class="team-img mb-3">
                    <h5>Priyanta Silva</h5>
                    <p>Founder & CEO</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="team-card p-3">
                    <img src="image/about2.jpg" alt="Team Member 2" class="team-img mb-3">
                    <h5>Nilushee Samaranayaka</h5>
                    <p>Operations Manager</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="team-card p-3">
                    <img src="image/about3.jpg" alt="Team Member 3" class="team-img mb-3">
                    <h5>Asela Bandara</h5>
                    <p>Technical Lead</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer include -->
<?php include('footer.php'); ?>



<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


