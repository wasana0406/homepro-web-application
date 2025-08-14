<?php
session_start();
include 'config.php';

$query = "
    SELECT a.*, u.name AS customer_name, sp.name AS provider_name
    FROM appointments a
    LEFT JOIN users u ON a.customer_id = u.id
    LEFT JOIN service_provider sp ON a.provider_id = sp.id
    ORDER BY a.created_at DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Appointments</title>
    <style>
        body {
           font-family: 'Roboto', sans-serif;
            background: #f2f6f9;
            padding: 20px;
        }
        .container {
            max-width: 1100px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(196, 131, 176, 0.1);
            overflow-x: auto; /* allow horizontal scroll on small screens */
        }
        h2 {
            text-align: center;
            color:rgb(10, 13, 17);
            margin-bottom: 25px;
        }
        .btn {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            color: white;
            font-size: 14px;
        }
        .btn-back { background: #28a745; display: inline-block; margin-bottom: 15px; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            min-width: 700px; /* keep table from shrinking too much */
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
            white-space: nowrap; /* prevent text from wrapping */
        }
        td:nth-child(2), td:nth-child(3) {
            text-align: left;
        }
        th {
            background: rgb(192, 213, 243);
            color:black;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        @media (max-width: 768px) {
            table {
                font-size: 13px;
                min-width: unset; /* allow table to shrink */
            }
            th, td {
                padding: 8px;
            }
            .btn-back {
                font-size: 12px;
                padding: 5px 10px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <a href="admin_dashboard.php" class="btn btn-back">← Back to Dashboard</a>

    <h2>All Appointments</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Service Provider</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Booked At</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= htmlspecialchars($row['provider_name']) ?></td>
                    <td><?= $row['appointment_date'] ?></td>
                    <td><?= $row['appointment_time'] ?></td>
                    <td><?= ucfirst($row['status']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7" style="text-align:center;">No appointments found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
