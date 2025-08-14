

<?php
session_start();
include 'config.php';

// Provider ID
$provider_id = $_SESSION['provider_id'];

// Get existing provider data
$query = mysqli_query($conn, "SELECT * FROM service_provider  WHERE id = '$provider_id'");
$data = mysqli_fetch_assoc($query);

// Update form submitted
if (isset($_POST['update'])) {
    $name        = $_POST['name'];
    $email      = $_POST['email'];
    $phone       = $_POST['phone'];
    $address     = $_POST['address'];
    $category    = $_POST['category'];
    $experience  = $_POST['experience'];
    $description = $_POST['description'];
    $price       = $_POST['price'];

    // Image handling
    $image_name = $_FILES['profile_image']['name'];
    $image_tmp  = $_FILES['profile_image']['tmp_name'];
    $image_path = "uploads/" . basename($image_name);

    if (!empty($image_name)) {
        move_uploaded_file($image_tmp, $image_path);
        $image_query = ", profile_image = '$image_name'";
    } else {
        $image_query = "";
    }

    // Update query
    $update = mysqli_query($conn, "UPDATE service_provider  SET 
        name = '$name',
        email = '$email',
        phone = '$phone',
        address = '$address',
        category = '$category',
        experience = '$experience',
        description = '$description',
        price = '$price'
        $image_query
        WHERE id = '$provider_id'
    ");

    if ($update) {
        echo "<script>alert('Profile updated successfully!'); window.location='provider_dashboard.php';</script>";
    } else {
        echo "<script>alert('Update failed!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Profile</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f0f4f7, #d9e4f5);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .card {
            width: 100%;
            max-width: 450px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
            text-align: center;
            font-size: 1.4rem;
            font-weight: bold;
            padding: 20px 10px;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .form-label {
            font-weight: 600;
            color: #333;
        }
        .form-control {
            border-radius: 10px;
        }
        .profile-img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #4facfe;
            margin-top: 10px;
        }
        .btn-success {
            background: linear-gradient(135deg, #43e97b, #38f9d7);
            border: none;
        }
        .btn-success:hover {
            background: linear-gradient(135deg, #2cc97b, #30e9d7);
        }
        .btn-secondary {
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">Edit Profile</div>
    <div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($data['phone']) ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="address" value="<?= htmlspecialchars($data['address']) ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <input type="text" name="category" value="<?= htmlspecialchars($data['category']) ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Experience (years)</label>
                <input type="number" name="experience" value="<?= htmlspecialchars($data['experience']) ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($data['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Service Price (Rs.)</label>
                <input type="number" name="price" value="<?= htmlspecialchars($data['price']) ?>" class="form-control" required>
            </div>

            <div class="mb-3 text-center">
                <label class="form-label d-block">Profile Image</label>
                <input type="file" name="profile_image" class="form-control mb-2">
                <?php if (!empty($data['profile_image'])): ?>
                    <img src="uploads/<?= $data['profile_image'] ?>" class="profile-img">
                <?php endif; ?>
            </div>

            <button type="submit" name="update" class="btn btn-success w-100 mb-2">Save Changes</button>
            <a href="provider_dashboard.php" class="btn btn-secondary w-100">Cancel</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
