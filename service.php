<?php
include 'config.php'; // Your database connection file

$category_icons = [
    "Cleaning" => "<i class='fas fa-broom'></i>",
    "Repair" => "<i class='fas fa-tools'></i>",
    "Painting" => "<i class='fas fa-paint-roller'></i>",
    "Shifting" => "<i class='fas fa-truck'></i>",
    "Plumbing" => "<i class='fas fa-faucet'></i>",
    "Electric" => "<i class='fas fa-bolt'></i>",
];

// --- CRITICAL CHANGE HERE ---
// Only select service providers where profile_completed is 1 AND status is 'approved'
$query = "SELECT * FROM service_provider WHERE profile_completed = 1 AND status = 'approved'";
$result = mysqli_query($conn, $query);

$categories = [];

// Check if there are any results before proceeding
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cat = $row['category'];
        if (!isset($categories[$cat])) {
            $categories[$cat] = [];
        }
        $categories[$cat][] = [
            "id" => $row['id'],
            "title" => $row['category'],
            "name" => $row['name'],
            "address" => $row['address'],
            "image" => "uploads/" . $row['profile_image']
        ];
    }
} else {
    // Optionally, handle the case where no approved providers are found
    // For example, you could set a flag or display a message in the HTML
    $no_approved_providers = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Services</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/service.css">
    <?php include('header.php'); // Ensure this includes your site header ?>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
        }

        .container {
            display: flex;
            padding: 1.5rem 2rem;
        }

        .categories {
            width: 250px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            padding: 1.5rem;
            margin-right: 2rem;
            position: sticky;
            top: 20px;
        }

        .categories h2 {
            font-size: 20px;
            margin-bottom: 1.5rem;
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 0.5rem;
        }

        .categories ul {
            list-style: none;
            padding: 0;
        }

        .categories li {
            padding: 10px;
            cursor: pointer;
            background-color: #f2f2f2;
            margin-bottom: 10px;
            border-radius: 8px;
            transition: 0.3s;
            display: flex;
            align-items: center;
        }

        .categories li.active,
        .categories li:hover {
            background-color: #e0e0e0;
        }

        .categories li i {
            margin-right: 8px;
            font-size: 1.2em;
        }

        .services {
            flex: 1;
        }

        .service-category {
            margin-bottom: 3rem;
        }

        .service-category h2 {
            font-size: 24px;
            margin-bottom: 1rem;
            color: #444;
        }

        .service-category h2 i {
            margin-right: 8px;
            font-size: 1.2em;
        }

        .service-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .service-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }

        .service-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }

        .service-card h3 {
            margin: 0.5rem 0 0.3rem;
        }

        .book-now {
            display: inline-block;
            margin-top: 0.5rem;
            background-color: #0d6efd;
            color: #fff;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .book-now:hover {
            background-color: #094ab2;
        }

        /* Icon Colors */
        i.fa-broom { color: #4285f4; }
        i.fa-tools { color: #34a853; }
        i.fa-paint-roller { color:rgb(243, 161, 9); }
        i.fa-truck { color:rgb(234, 53, 128); }
        i.fa-faucet { color: #03a9f4; }
        i.fa-bolt { color: #fbbc05; }
        
        /* Message for no providers */
        .no-providers-message {
            text-align: center;
            padding: 50px;
            font-size: 1.2em;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="categories">
        <h2>Categories</h2>
        <ul>
            <?php 
            // Only show categories if there are approved providers
            if (!empty($categories)) {
                foreach ($categories as $category => $services) { ?>
                    <li id="cat-<?php echo $category; ?>" onclick="filterCategory('<?php echo $category; ?>')">
                        <?php echo $category_icons[$category] . htmlspecialchars($category); ?>
                    </li>
                <?php }
            } else { ?>
                <li>No categories available</li>
            <?php } ?>
        </ul>
    </div>

    <main class="services">
        <?php 
        if (!empty($categories)) {
            foreach ($categories as $category => $services) { ?>
                <section id="<?php echo $category; ?>" class="service-category">
                    <h2><?php echo $category_icons[$category] . htmlspecialchars($category); ?></h2>
                    <div class="service-list">
                        <?php foreach ($services as $service) { ?>
                            <div class="service-card">
                                <img src="<?php echo $service['image']; ?>" alt="<?php echo $service['title']; ?>" class="service-img">
                                <h3><?php echo $service['title']; ?></h3>
                                <p><?php echo htmlspecialchars($service['name']); ?></p>
                                <p><?php echo htmlspecialchars($service['address']); ?></p>
                                <a href="booking.php?id=<?php echo $service['id']; ?>" class="book-now">Book Now</a>
                            </div>
                        <?php } ?>
                    </div>
                </section>
            <?php }
        } else { ?>
            <div class="no-providers-message">
                <p>No service providers are currently available or approved.</p>
                <p>Please check back later!</p>
            </div>
        <?php } ?>
    </main>
</div>

<script>
    function filterCategory(category) {
        document.querySelectorAll('.service-category').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.categories li').forEach(el => el.classList.remove('active'));

        const targetCategory = document.getElementById(category);
        const targetCatList = document.getElementById('cat-' + category);

        if (targetCategory && targetCatList) {
            targetCategory.style.display = 'block';
            targetCatList.classList.add('active');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Only attempt to filter if there are categories available
        <?php if (!empty($categories)) { ?>
            filterCategory('<?php echo array_keys($categories)[0]; ?>');
        <?php } ?>
    });
</script>

</body>
</html>