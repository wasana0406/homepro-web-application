<?php
session_start();
include 'config.php';

// Delete customer
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $delete_query = mysqli_query($conn, "DELETE FROM users WHERE id = $delete_id AND role = 'customer'");
    if ($delete_query) {
        header("Location: admin_customer.php?msg=deleted");
    } else {
        header("Location: admin_customer.php?msg=error");
    }
    exit;
}

// Export to Excel
if (isset($_GET['export']) && $_GET['export'] === 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=customers.xls");
    echo "ID\tName\tEmail\tRegistered Date\n";
    $export_query = mysqli_query($conn, "SELECT * FROM users WHERE role = 'customer' ORDER BY id ASC");
    while ($row = mysqli_fetch_assoc($export_query)) {
        echo $row['id'] . "\t" . $row['name'] . "\t" . $row['email'] . "\t" . $row['created_at'] . "\n";
    }
    exit;
}

// Get all customers
$query = "SELECT * FROM users WHERE role = 'customer' ORDER BY id ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Customer List</title>
    <style>
        * { box-sizing: border-box; }
        body {
            /* Times New Roman font removed from here */
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            padding-top: 70px; /* navbar height */
        }
        .container {
            max-width: 1100px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        h2 {
            text-align: center; /* Title centered */
            color:rgb(10, 12, 14);
            margin-bottom: 30px;
            font-size: 30px;
            font-weight: bold;
        }
        .btn {
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 4px;
            color: white;
            font-size: 14px;
            margin-bottom: 15px;
            display: inline-block;
        }
        .btn-delete { background: #dc3545; }
        .btn-back { background: #28a745; }
        .btn-export { background:rgb(141, 7, 250); }

        .alert {
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            /* Times New Roman font removed from here */
            animation: fadeIn 0.5s ease-in-out;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }
        th, td {
            padding: 14px 16px;
            border: 1px solid #ddd;
            text-align: center;
        }
        td:nth-child(2),
        td:nth-child(3) {
            text-align: left;
        }
        th {
            background: #f0f0f0; /* Table header background: light grey */
            color: #333; /* Table header font color: dark grey (almost black) */
            font-weight: bold; /* Table header font style: bold */
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        @media (max-width: 768px) {
            table { font-size: 13px; }
            .btn { padding: 6px 10px; font-size: 12px; }
            h2 { font-size: 24px; }
        }
    </style>
</head>
<body>

<div class="container">

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert <?= $_GET['msg'] === 'deleted' ? 'alert-success' : 'alert-error' ?>">
            <?= $_GET['msg'] === 'deleted' ? 'Customer deleted successfully!' : 'Something went wrong. Please try again.' ?>
        </div>
    <?php endif; ?>

    <a href="admin_dashboard.php" class="btn btn-back">← Back to Dashboard</a>
    <a href="admin_customer.php?export=excel" class="btn btn-export">Export to Excel</a>
    
    <h2>All Registered Customers</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Registered Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this customer?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">No customers found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    setTimeout(() => {
        const alertBox = document.querySelector('.alert');
        if(alertBox) alertBox.style.display = 'none';
    }, 4000); // hide alert after 4 seconds
</script>

</body>
</html>