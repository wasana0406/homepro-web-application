
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Service</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
  <div class="container">
    <h3>Add New Service</h3>
    <form method="POST" action="save_service.php">
      <div class="mb-3">
        <label for="service_name" class="form-label">Service Name</label>
        <input type="text" name="service_name" id="service_name" class="form-control">
      </div>
      <div class="mb-3">
        <label for="price" class="form-label">Price (Rs.)</label>
        <input type="number" name="price" id="price" class="form-control">
      </div>
      <button type="submit" class="btn btn-success">Add Service</button>
    </form>
  </div>
</body>
</html>
