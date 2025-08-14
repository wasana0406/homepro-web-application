<?php
include 'config.php';

// Appointment report query
$sql = "SELECT 
            a.id AS appointment_id,
            a.customer_name,
            a.customer_email,
            a.appointment_date,
            a.appointment_time,
            a.status,
            sp.name AS provider_name,
            sp.email AS provider_email,
            sp.category AS service_category,
            sp.status AS provider_status
        FROM 
            appointments a
        JOIN 
            service_provider sp ON a.provider_id = sp.id
        ORDER BY 
            a.created_at DESC";

$result = mysqli_query($conn, $sql);

// Appointment status counts for pie chart
$statusQuery = "
  SELECT status, COUNT(*) AS count 
  FROM appointments 
  GROUP BY status
";
$statusResult = mysqli_query($conn, $statusQuery);

$statusLabels = [];
$statusCounts = [];

while ($row = mysqli_fetch_assoc($statusResult)) {
    $statusLabels[] = ucfirst($row['status']);
    $statusCounts[] = (int)$row['count'];
}

// Provider appointment counts for bar chart
$providerChartQuery = "
  SELECT sp.name AS provider_name, COUNT(a.id) AS total_appointments
  FROM appointments a
  JOIN service_provider sp ON a.provider_id = sp.id
  GROUP BY sp.name
  ORDER BY total_appointments DESC
";
$providerChartResult = mysqli_query($conn, $providerChartQuery);

$providerNames = [];
$providerCounts = [];

while ($row = mysqli_fetch_assoc($providerChartResult)) {
    $providerNames[] = $row['provider_name'];
    $providerCounts[] = (int)$row['total_appointments'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Appointment Report</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap & DataTables CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet" />

  <style>
    body {
      background-color: #f8f9fa;
    }
    .table thead th {
      vertical-align: middle;
      text-align: center;
    }
    .badge {
      font-size: 0.9em;
    }
    .page-header {
      background: linear-gradient(90deg, #198754 0%, #157347 100%);
      padding: 20px;
      border-radius: 10px;
      color: white;
      margin-bottom: 30px;
    }
    .btn-dashboard {
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    /* Chart container styling */
    #charts {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
      justify-content: center;
      margin: bottom 40px;
    }
    #charts > div {
      flex: 1 1 400px;
      max-width: 300px;
      background: white;
      padding: 1.5rem;
      border-radius: 12px;
      box-shadow: 0 0 15px rgb(0 0 0 / 0.1);
    }
  </style>
</head>
<body>

<div class="container my-5">
  <div class="page-header d-flex justify-content-between align-items-center">
    <h2 class="mb-0">📊 Admin Appointment Report</h2>
    <a href="admin_dashboard.php" class="btn btn-light btn-dashboard">← Back to Dashboard</a>
  </div>

  

  <!-- Appointment Report Table -->
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table id="reportTable" class="table table-bordered table-hover align-middle">
          <thead class="table-success text-center">
            <tr>
              <th>#</th>
              <th>Customer</th>
              <th>Email</th>
              <th>Date</th>
              <th>Time</th>
              <th>Status</th>
              <th>Provider</th>
              <th>Provider Email</th>
              <th>Category</th>
              <th>Provider Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr class="text-center">
                  <td><?= $row['appointment_id']; ?></td>
                  <td><?= htmlspecialchars($row['customer_name']); ?></td>
                  <td><?= htmlspecialchars($row['customer_email']); ?></td>
                  <td><?= $row['appointment_date']; ?></td>
                  <td><?= $row['appointment_time']; ?></td>
                  <td><span class="badge bg-secondary"><?= ucfirst($row['status']); ?></span></td>
                  <td><?= htmlspecialchars($row['provider_name']); ?></td>
                  <td><?= htmlspecialchars($row['provider_email']); ?></td>
                  <td><?= htmlspecialchars($row['service_category']); ?></td>
                  <td><span class="badge bg-info"><?= ucfirst($row['provider_status']); ?></span></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="10" class="text-center">No appointments found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Charts Section -->
  <div id="charts">
    <!-- Appointment Status Pie Chart -->
    <div>
      <h5 class="text-center mb-4">🧁 Appointment Status Summary</h5>
      <canvas id="statusChart"></canvas>
    </div>

    <!-- Provider Appointment Bar Chart -->
    <div>
      <h5 class="text-center mb-4">👷‍♂️ Appointments per Provider</h5>
      <canvas id="providerChart"></canvas>
    </div>
  </div>

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  $(document).ready(function () {
    $('#reportTable').DataTable({
      dom: 'Bfrtip',
      buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
      order: [[0, 'desc']]
    });

    // Appointment Status Pie Chart
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(ctxStatus, {
      type: 'pie',
      data: {
        labels: <?= json_encode($statusLabels); ?>,
        datasets: [{
          label: 'Appointment Status',
          data: <?= json_encode($statusCounts); ?>,
          backgroundColor: [
            '#f5032b', // green
            '#ffc107', // yellow
            '#dc3545', // red
            '#0d6efd', // blue
            '#6c757d', // grey
            '#20c997'  // teal
          ]
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom'
          },
          title: {
            display: false,
            text: 'Appointment Status Distribution'
          }
        }
      }
    });

    // Provider Appointment Bar Chart
    const ctxProvider = document.getElementById('providerChart').getContext('2d');
    const providerChart = new Chart(ctxProvider, {
      type: 'bar',
      data: {
        labels: <?= json_encode($providerNames); ?>,
        datasets: [{
          label: 'Appointments',
          data: <?= json_encode($providerCounts); ?>,
          backgroundColor: 'rgba(25, 135, 84, 0.7)',
          borderColor: 'rgba(25, 135, 84, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            stepSize: 1,
            ticks: {
              precision: 0
            },
            title: {
              display: true,
              text: 'Number of Appointments'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Service Providers'
            }
          }
        },
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });
  });
</script>

</body>
</html>
