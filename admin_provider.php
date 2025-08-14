<?php
session_start();
include 'config.php';

// Handle approve/reject/delete actions
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    mysqli_query($conn, "UPDATE service_provider SET status='approved' WHERE id = $id");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);
    mysqli_query($conn, "UPDATE service_provider SET status='rejected' WHERE id = $id");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Note: The original code had redundant delete queries.
    // Deleting from service_provider should be sufficient if 'users' table entries
    // are cascade-deleted or managed differently. If 'id' is truly common, then
    // deleting from service_provider and users is correct. Assuming original intent.
    mysqli_query($conn, "DELETE FROM service_provider WHERE id = $id");
    mysqli_query($conn, "DELETE FROM users WHERE id = $id");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Fetch service providers
$query = "SELECT sp.*, u.name, u.email, u.created_at
          FROM service_provider sp
          JOIN users u ON sp.id = u.id
          WHERE u.role = 'service_provider'
          ORDER BY u.id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - Service Providers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7f6;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }
        .header-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .back-btn {
            background-color: #007bff;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .back-btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .page-title {
            font-size: 1.7em;
            font-weight: 700;
            text-align: center;
            margin-top: 0;
            margin-bottom: 30px; /* Adjust as needed after removing the line */
            color: #2c3e50;
            position: relative; /* Keep position relative if you add other pseudo-elements later */
            /* Removed padding-bottom and ::after pseudo-element */
        }
        /* Removed .page-title::after block completely */
        .search-container {
            margin-bottom: 0;
            flex-grow: 1;
            text-align: right;
        }
        #searchInput {
            width: 100%;
            max-width: 300px;
            padding: 10px 15px;
            font-size: 15px;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            box-sizing: border-box;
        }
        #searchInput:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
            outline: none;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            text-align: left;
            font-size: 15px;
        }
        th {
            background-color: #e9ecef;
            color: #495057;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        th:first-child { border-top-left-radius: 10px; }
        th:last-child { border-top-right-radius: 10px; }
        tr:last-child td { border-bottom: none; }
        .profile-img {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #eee;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn-group {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
        .btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: white;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-sizing: border-box;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .btn-approve { background-color: #28a745; }
        .btn-approve:hover { background-color: #218838; }
        .btn-reject { background-color: #ffc107; color: #222; }
        .btn-reject:hover { background-color: #e0a800; }
        .btn-delete { background-color: #dc3545; }
        .btn-delete:hover { background-color: #c82333; }
        .btn-black {
            background-color: #6c757d !important;
            color: white !important;
            cursor: not-allowed;
            opacity: 0.7;
        }
        .btn-black:hover {
            transform: none;
            box-shadow: none;
        }
        .status {
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 25px;
            font-size: 0.9em;
            display: inline-block;
        }
        .status-approved { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;}
        .status-pending { background-color: #cce5ff; color: #004085; border: 1px solid #b8daff;}
        .status-rejected { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba;}
        .sip-link {
            color: #007bff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: color 0.3s ease;
        }
        .sip-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .container {
                padding: 20px;
            }
            .page-title {
                font-size: 1.8em;
            }
            th, td {
                padding: 12px;
                font-size: 14px;
            }
            .header-controls {
                flex-direction: column;
                align-items: flex-start;
            }
            .search-container {
                text-align: left;
                width: 100%;
            }
            #searchInput {
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .back-btn {
                margin-bottom: 0;
            }
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead {
                display: none;
            }
            tr {
                margin-bottom: 20px;
                background: white;
                border: 1px solid #e0e0e0;
                border-radius: 10px;
                padding: 15px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            }
            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
                border: none;
                border-bottom: 1px solid #f0f0f0;
            }
            td:last-child {
                border-bottom: none;
            }
            td::before {
                position: absolute;
                top: 15px;
                left: 15px;
                width: 45%;
                white-space: nowrap;
                font-weight: 600;
                color: #555;
            }
            td:nth-of-type(1)::before { content: "ID:"; }
            td:nth-of-type(2)::before { content: "Image:"; }
            td:nth-of-type(3)::before { content: "Name:"; }
            td:nth-of-type(4)::before { content: "Email:"; }
            td:nth-of-type(5)::before { content: "Category:"; }
            td:nth-of-type(6)::before { content: "Phone:"; }
            td:nth-of-type(7)::before { content: "SIP Receipt:"; }
            td:nth-of-type(8)::before { content: "Registered:"; }
            td:nth-of-type(9)::before { content: "Status:"; }
            td:nth-of-type(10)::before { content: "Actions:"; }
            .btn-group {
                justify-content: flex-end;
                padding-top: 10px;
            }
            .profile-img {
                width: 50px;
                height: 50px;
            }
        }

        /* Modal styles - Kept as per original request to maintain behavior */
        #actionModal {
            display:none;
            position:fixed;
            top:0; left:0; right:0; bottom:0;
            background:rgba(0,0,0,0.5);
            z-index:1000;
            justify-content:center;
            align-items:center;
            padding: 15px;
        }
        #actionModal .modal-content {
            background:#fff;
            padding:35px;
            border-radius:12px;
            width:100%;
            max-width:450px;
            text-align:center;
            box-shadow:0 15px 30px rgba(0,0,0,0.25);
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        #actionModal .modal-icon {
            font-size:48px;
            margin-bottom:20px;
            line-height: 1;
        }
        #actionModal #modalTitle {
            margin:0 0 12px;
            font-size:26px;
            font-weight: 700;
            color: #333;
        }
        #actionModal #modalMessage {
            color:#666;
            margin-bottom:30px;
            font-size: 16px;
        }
        #actionModal .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        #actionModal .btn-cancel {
            padding:12px 25px;
            border:none;
            background:#6c757d;
            color:#fff;
            border-radius:8px;
            cursor:pointer;
            font-size:16px;
            transition: background-color 0.3s ease;
        }
        #actionModal .btn-cancel:hover {
            background:#5a6268;
        }
        #actionModal #confirmActionBtn {
            padding:12px 25px;
            color:white;
            text-decoration:none;
            border-radius:8px;
            font-size:16px;
            transition: background-color 0.3s ease;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-controls">
        <a href="admin_dashboard.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        <div class="search-container">
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search by name, email, category, phone...">
        </div>
    </div>

    <h2 class="page-title">All Registered Service Providers</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Email</th>
                <th>Category</th>
                <th>Phone</th>
                <th>SIP Receipt</th>
                <th>Registered</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td>
                    <?php if(!empty($row['profile_image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($row['profile_image']) ?>" class="profile-img" alt="Profile Image">
                    <?php else: ?>
                        <img src="uploads/default.png" class="profile-img" alt="Default Image">
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td>
                    <?php
                    $sipImage = 'uploads/' . htmlspecialchars($row['sip_receipt_image']);
                    if (!empty($row['sip_receipt_image']) && file_exists($sipImage)) {
                        echo "<a href='$sipImage' target='_blank' class='sip-link'><i class='fas fa-file-image'></i> View</a>";
                    } else {
                        echo "<span style='color:#888;'>N/A</span>";
                    }
                    ?>
                </td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td>
                    <?php
                        $status = $row['status'] ?? 'pending';
                        $statusClass = "status-pending";
                        if ($status == 'approved') $statusClass = "status-approved";
                        elseif ($status == 'rejected') $statusClass = "status-rejected";
                    ?>
                    <span class="status <?= $statusClass ?>"><?= ucfirst($status) ?></span>
                </td>
                <td>
                    <div class="btn-group">
                        <?php if ($status != 'approved'): ?>
                            <a href="#" class="btn btn-approve" onclick="return openActionModal('approve', <?= $row['id'] ?>)" title="Approve">
                                <i class="fas fa-check"></i>
                            </a>
                        <?php else: ?>
                            <span class="btn btn-black" title="Approved"><i class="fas fa-check"></i></span>
                        <?php endif; ?>

                        <?php if ($status != 'rejected'): ?>
                            <a href="#" class="btn btn-reject" onclick="return openActionModal('reject', <?= $row['id'] ?>)" title="Reject">
                                <i class="fas fa-times"></i>
                            </a>
                        <?php else: ?>
                            <span class="btn btn-black" title="Rejected"><i class="fas fa-times"></i></span>
                        <?php endif; ?>

                        <a href="#" class="btn btn-delete" onclick="return openActionModal('delete', <?= $row['id'] ?>)" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="10" style="text-align: center; padding: 20px;">No service providers found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div id="actionModal">
    <div class="modal-content">
        <div id="modalIcon" class="modal-icon">
            </div>
        <h3 id="modalTitle"></h3>
        <p id="modalMessage"></p>
        <div class="modal-buttons">
            <button class="btn-cancel" onclick="closeModal()">Cancel</button>
            <a id="confirmActionBtn" href="#">Confirm</a>
        </div>
    </div>
</div>

<script>
function openActionModal(action, id) {
    let url = `?${action}=${id}`;
    let confirmBtn = document.getElementById("confirmActionBtn");
    let modalTitle = document.getElementById("modalTitle");
    let modalMessage = document.getElementById("modalMessage");
    let modalIcon = document.getElementById("modalIcon");

    confirmBtn.href = url;

    if (action === 'approve') {
        modalTitle.textContent = "Approve Service Provider";
        modalMessage.textContent = "Are you sure you want to approve this service provider? They will be able to offer their services.";
        modalIcon.innerHTML = '<i class="fas fa-check-circle" style="color:#28a745;"></i>';
        confirmBtn.style.backgroundColor = "#28a745";
        confirmBtn.style.color = "#fff";
    } else if (action === 'reject') {
        modalTitle.textContent = "Reject Service Provider";
        modalMessage.textContent = "Are you sure you want to reject this service provider? They will not be able to offer services.";
        modalIcon.innerHTML = '<i class="fas fa-times-circle" style="color:#ffc107;"></i>';
        confirmBtn.style.backgroundColor = "#ffc107";
        confirmBtn.style.color = "#000";
    } else if (action === 'delete') {
        modalTitle.textContent = "Delete Service Provider";
        modalMessage.textContent = "This action cannot be undone. Are you sure you want to permanently delete this service provider and their associated user account?";
        modalIcon.innerHTML = '<i class="fas fa-trash-alt" style="color:#dc3545;"></i>';
        confirmBtn.style.backgroundColor = "#dc3545";
        confirmBtn.style.color = "#fff";
    }

    document.getElementById("actionModal").style.display = "flex";
    return false; // Prevents default link behavior
}

function closeModal() {
    document.getElementById("actionModal").style.display = "none";
}

function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll("table tbody tr");

    rows.forEach(row => {
        // Get text content of relevant cells for filtering
        const name = row.children[2]?.textContent.toLowerCase() || "";
        const email = row.children[3]?.textContent.toLowerCase() || "";
        const category = row.children[4]?.textContent.toLowerCase() || "";
        const phone = row.children[5]?.textContent.toLowerCase() || "";
        const status = row.children[8]?.textContent.toLowerCase() || "";


        if (
            name.includes(input) ||
            email.includes(input) ||
            category.includes(input) ||
            phone.includes(input) ||
            status.includes(input) // Allow searching by status as well
        ) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

// Close modal if user clicks outside of it
window.onclick = function(event) {
    const modal = document.getElementById("actionModal");
    if (event.target == modal) {
        closeModal();
    }
}
</script>

</body>
</html>