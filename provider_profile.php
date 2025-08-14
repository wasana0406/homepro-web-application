<?php
session_start();
include 'config.php'; // ඔබේ database සම්බන්ධතා file එක

// Session එකේ provider_id එකක් නොමැති නම්, login පිටුවට යොමු කරන්න
if (!isset($_SESSION['provider_id'])) {
    header("Location: login.php");
    exit();
}

$provider_id = $_SESSION['provider_id'];
$error = '';
$success = '';
$current_status = '';
$admin_message = ''; // Admin message variable එක initialize කරන්න

// දැනටමත් profile එකේ තත්ත්වය ලබා ගන්න
$stmt_check = $conn->prepare("SELECT status, admin_message FROM service_provider WHERE id = ?");
$stmt_check->bind_param("i", $provider_id); // id එක int නිසා "i"
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    $row_check = $result_check->fetch_assoc();
    $current_status = $row_check['status'];
    $admin_message = $row_check['admin_message']; // මෙතැනින් admin_message එක ලබා ගනී
} else {
    // පළමු වතාවට profile එක සම්පූර්ණ කරනවා නම්, service_provider table එකට id එක insert කරන්න
    // මෙය වැදගත් වන්නේ UPDATE ක්‍රියාත්මක වීමට record එකක් තිබිය යුතු නිසාය.
    // මෙය signup.php එකේදීත් කළ හැක, නමුත් මෙහිදී ආරක්ෂිතව insert කරයි.
    $stmt_insert_initial = $conn->prepare("INSERT INTO service_provider (id, status) VALUES (?, 'pending')");
    $stmt_insert_initial->bind_param("i", $provider_id);
    if (!$stmt_insert_initial->execute()) {
        $error = "Failed to initialize provider record: " . $stmt_insert_initial->error;
    }
    $stmt_insert_initial->close();
}
$stmt_check->close();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Input validation (ඔබට අවශ්‍ය පරිදි තවත් validation එකතු කළ හැක)
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    $category = htmlspecialchars($_POST['category']);
    $experience = htmlspecialchars($_POST['experience']);
    $description = htmlspecialchars($_POST['description']);
    $price = htmlspecialchars($_POST['price']);

    // ගොනු උඩුගත කිරීම් (File Uploads)
    $profile_image_name_tmp = $_FILES['profile_image']['tmp_name'];
    $profile_image_original_name = $_FILES['profile_image']['name'];
    $sip_image_name_tmp = $_FILES['sip_receipt']['tmp_name'];
    $sip_image_original_name = $_FILES['sip_receipt']['name'];

    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // ගොනු වල unique නාමයන් ජනනය කිරීම
    $profile_image_unique_name = uniqid('profile_', true) . '.' . strtolower(pathinfo($profile_image_original_name, PATHINFO_EXTENSION));
    $sip_image_unique_name = uniqid('sip_', true) . '.' . strtolower(pathinfo($sip_image_original_name, PATHINFO_EXTENSION));

    $profile_image_path = $upload_dir . $profile_image_unique_name;
    $sip_image_path = $upload_dir . $sip_image_unique_name;

    // ගොනු වර්ග පරීක්ෂා කිරීම
    $allowed_profile_extensions = ['jpg', 'jpeg', 'png'];
    $allowed_sip_extensions = ['jpg', 'jpeg', 'png', 'pdf'];

    $profile_extension = strtolower(pathinfo($profile_image_original_name, PATHINFO_EXTENSION));
    $sip_extension = strtolower(pathinfo($sip_image_original_name, PATHINFO_EXTENSION));

    if (!in_array($profile_extension, $allowed_profile_extensions)) {
        $error = "Profile image must be JPG, JPEG, or PNG.";
    } elseif (!in_array($sip_extension, $allowed_sip_extensions)) {
        $error = "SIP receipt must be JPG, JPEG, PNG, or PDF.";
    } else {
        // ගොනු upload කිරීම
        if (move_uploaded_file($profile_image_name_tmp, $profile_image_path) && move_uploaded_file($sip_image_name_tmp, $sip_image_path)) {
            // දත්ත සමුදායට යාවත්කාලීන කිරීම (Prepared Statement භාවිතයෙන්)
            // status එක 'approved' හෝ 'rejected' නම්, නැවත 'pending' ලෙස සකසයි.
            $stmt = $conn->prepare("UPDATE service_provider SET
                                    name = ?, email = ?, phone = ?, address = ?, category = ?, experience = ?,
                                    description = ?, price = ?, profile_image = ?, sip_receipt_image = ?,
                                    profile_completed = 1, status = 'pending', admin_message = NULL
                                    WHERE id = ?");

            $stmt->bind_param("ssssssssssi", $name, $email, $phone, $address, $category, $experience, $description, $price, $profile_image_unique_name, $sip_image_unique_name, $provider_id);

            if ($stmt->execute()) {
                $success = "Profile submitted successfully! It is now awaiting admin approval.";
                // Update එක සාර්ථක වූ පසු, $current_status එක 'pending' ලෙස යාවත්කාලීන කරන්න.
                $current_status = 'pending';
                $admin_message = ''; // Admin message එක clear කරන්න
                // මෙතැනින් තමයි වැදගත්ම වෙනස්කම!
                echo "<script>alert('Profile submitted successfully! It is now awaiting admin approval.');
                      window.location.href='provider_dashboard.php';</script>";
                exit(); // මෙය අත්‍යවශ්‍යයි!
            } else {
                $error = "Database Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Failed to upload one or more images.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Complete Profile</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            font-size: 1.1rem;
        }
        .profile-card {
            width: 100%;
            max-width: 550px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            background-color: white;
            margin: 40px 0;
        }
        .profile-header {
            background-color: #1406dd;
            color: white;
            padding: 25px;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            text-align: center;
            font-size: 1.5rem;
        }
        .form-label {
            font-weight: 600;
            font-size: 1.1rem;
            color: #333;
        }
        .form-label i {
            margin-right: 6px;
            color: #0d6efd;
        }
        .form-control,
        .form-select {
            font-size: 1.05rem;
            padding: 10px;
        }
        .alert-bank {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 1rem;
        }
        .alert-bank strong {
            font-size: 1.1rem;
        }
        .btn {
            font-size: 1.1rem;
            padding: 10px;
        }
        @media (max-width: 768px) {
            .profile-card { max-width: 90%; }
        }
    </style>
</head>
<body>
<div class="card profile-card p-3">
    <div class="profile-header">
        <h3>COMPLETE YOUR PROFILE</h3>
        <p class="mb-0">Fill in your service provider details</p>
    </div>

    <div class="card-body p-4">
        <?php if (isset($error) && $error != '') echo "<div class='alert alert-danger'>$error</div>"; ?>
        <?php if (isset($success) && $success != '') echo "<div class='alert alert-success'>$success</div>"; ?>

        <?php if ($current_status == 'approved'): ?>
            <div class="alert alert-info">
                Your profile has already been **approved**. You can update your details if needed.
            </div>
        <?php elseif ($current_status == 'pending'): ?>
            <div class="alert alert-warning">
                Your profile is currently **awaiting admin approval**. You can still update your details if necessary.
            </div>
        <?php elseif ($current_status == 'rejected'): ?>
            <div class="alert alert-danger">
                Your profile has been **rejected** by the admin.
                <?php if (!empty($admin_message)): ?>
                    Reason: <?php echo htmlspecialchars($admin_message); ?>
                <?php else: ?>
                    Please update your details and resubmit.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-person-fill"></i> Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="John Perera" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-envelope-fill"></i> Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-telephone-fill"></i> Phone Number</label>
                <input type="text" name="phone" class="form-control" placeholder="07XXXXXXXX" pattern="\d{10}" maxlength="10" minlength="10" required title="Phone number must be exactly 10 digits">
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-geo-alt-fill"></i> Address</label>
                <textarea name="address" class="form-control" rows="2" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-tools"></i> Service Category</label>
                <select name="category" class="form-select" required>
                    <option value="">Choose Category</option>
                    <option value="Electric">Electric</option>
                    <option value="Cleaning">Cleaning</option>
                    <option value="Repair">Repair</option>
                    <option value="Painting">Painting</option>
                    <option value="Shifting">Shifting</option>
                    <option value="Plumbing">Plumbing</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-award-fill"></i> Experience</label>
                <input type="text" name="experience" class="form-control" placeholder="e.g. 3 years" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-card-text"></i> Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Describe your service" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-currency-rupee"></i> Service Price</label>
                <input type="text" name="price" class="form-control" placeholder="e.g. 1000 LKR" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-image-fill"></i> Profile Image</label>
                <input type="file" name="profile_image" class="form-control" accept=".jpg,.jpeg,.png" required>
            </div>

            <div class="alert-bank">
                <strong>Admin Bank Details:</strong><br>
                LKR : 500.00<br>
                Bank: Sampath Bank<br>
                Account Name: homePro Pvt Ltd<br>
                Account Number: 1234567890<br>
                Branch: Nugegoda
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-receipt"></i> SIP Payment Receipt</label>
                <input type="file" name="sip_receipt" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit Profile</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>