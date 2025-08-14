<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Services</title>
    <link rel="stylesheet" href="css/style.css">
    <style>

/* Universal Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background-color: #faf8f8; /* Light grey background */
    text-align: center;
    overflow-x: hidden;
}

/* Hero Section (Carousel Container) */
.hero {
    position: relative; /* Allows absolute positioning of children */
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: rgb(255, 242, 242);
    overflow: hidden;
}

.carousel-slide {
    position: absolute; /* Positions slides on top of each other */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-position: center center; /* Centers the background image */
    background-size: cover;
    opacity: 0;
    transition: opacity 1s ease-in-out; /* Smooth fade transition over 1 second */
    display: flex; /* Use flexbox to center content within each slide */
    align-items: center;
    justify-content: center;
}

.carousel-slide.active {
    opacity: 1; /* Makes the active slide visible */
}

/* Overlay for Readability */
.carousel-slide::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4); /* Dark semi-transparent overlay */
    z-index: 1; /* Ensures overlay is behind content but above image */
}

/* Hero Content */
.hero-content {
    position: relative;
    z-index: 2;
    max-width: 600px;
    padding: 0 20px;
    text-align: center;
    color: white;
}

.hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7); /* Subtle text shadow for readability */
}

.hero h1 span {
    color: rgb(195, 248, 48);
}

.hero p {
    color: rgba(255, 255, 255, 0.95);
    font-size: 1.3rem;
    margin-top: 10px;
    margin-bottom: 20px;
    line-height: 1.6;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6); /* Subtle text shadow */
}

/* Hero Buttons */
.hero-buttons .btn {
    background: linear-gradient(135deg, #4CAF50 0%, #2E8B57 100%); /* Green gradient background */
    padding: 14px 30px; /* Generous padding for good click area */
    color: white;
    text-decoration: none;
    border-radius: 8px; /* Moderately rounded corners */
    margin: 8px; /* Space between buttons */
    display: inline-block; /* Allows side-by-side display */
    font-size: 1.1rem; /* Slightly larger font */
    font-weight: 600; /* Bolder text */
    transition: all 0.3s ease-in-out; /* Smooth transitions for hover effects */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2); /* Soft shadow */
    border: none; /* No default border */
    letter-spacing: 0.5px; /* Slight letter spacing */
}



.hero-buttons .btn.secondary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); /* Blue gradient for secondary button */
    color: #cfe0c8; /* Light text color */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2); /* Same shadow as primary */
}

.hero-buttons .btn.secondary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #007bff 100%); /* Reverse blue gradient on hover */
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
}

/* Carousel Navigation Dots */
.carousel-dots {
    position: absolute;
    bottom: 20px; /* Positioned at the bottom */
    left: 50%;
    transform: translateX(-50%); /* Centers the dots horizontally */
    z-index: 3; /* Ensures dots are on top of everything */
    display: flex; /* Arranges dots in a row */
    gap: 10px; /* Space between dots */
}

.dot {
    width: 12px;
    height: 12px;
    background-color: rgba(255, 255, 255, 0.6); /* Semi-transparent white */
    border-radius: 50%; /* Makes them circular */
    cursor: pointer;
    transition: background-color 0.3s ease; /* Smooth color change on hover/active */
}

.dot.active {
    background-color: #65b741; /* Green color for the active dot */
}

/* How It Works Section Styles */
.how-it-works-section {
    padding: 80px 20px;
    background-color: #ffffff; /* White background */
    color: #333;
    text-align: center;
}

.how-it-works-section h2 {
    font-size: 2.8rem;
    color: #1A522A; /* Much deeper green for heading */
    margin-bottom: 20px;
    position: relative;
    display: inline-block;
}

.how-it-works-section h2::after {
    content: '';
    position: absolute;
    left: 50%;
    bottom: -10px;
    transform: translateX(-50%);
    width: 100px;
    height: 4px;
    background-color: #388E3C; /* Darker green for underline */
    border-radius: 2px;
}

.how-it-works-section p.intro {
    font-size: 1.1rem;
    max-width: 800px;
    margin: 0 auto 50px auto;
    line-height: 1.8;
    color: #444; /* Slightly darker intro text */
}

.steps-container {
    display: flex;
    justify-content: center;
    gap: 40px;
    flex-wrap: wrap;
    margin-top: 40px;
}

.step-item {
    background: linear-gradient(145deg, #E6F3E6 0%, #D4EFD7 100%); /* Bolder light green gradient */
    padding: 30px;
    border-radius: 12px; /* Slightly more rounded */
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); /* More prominent shadow */
    flex: 1;
    max-width: 300px;
    text-align: center;
    position: relative;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    border: 1px solid #C8E6C9; /* Stronger subtle border */
}

.step-item:hover {
    transform: translateY(-10px); /* More pronounced lift */
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25); /* Even stronger shadow on hover */
}

.step-item .icon {
    font-size: 4rem; /* Even larger icon size */
    color: #2E7D32; /* Deeper, richer green for icon */
    margin-bottom: 20px;
}

.step-item h3 {
    font-size: 1.9rem; /* Larger heading */
    color: #0056b3; /* Deeper blue for heading */
    margin-bottom: 10px;
    font-weight: 700; /* Bolder heading text */
}

.step-item p {
    font-size: 1.05rem; /* Slightly larger paragraph */
    color: #555; /* Slightly darker paragraph text */
    line-height: 1.6;
}

/* Step number indicator */
.step-item::before {
    content: attr(data-step); /* Use data-step attribute for content */
    position: absolute;
    top: -25px; /* Move higher */
    left: 50%;
    transform: translateX(-50%);
    background-color: #1B5E20; /* Very dark green for number background */
    color: white;
    font-size: 1.4rem; /* Larger font size for number */
    font-weight: bold;
    width: 45px; /* Larger circle */
    height: 45px; /* Larger circle */
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3); /* Stronger shadow for number */
    z-index: 1;
    border: 3px solid #66BB6A; /* Light green border for number */
}

/* Arrow between steps (for desktop layout) */
.steps-container .step-item:not(:last-child)::after {
    content: '\2192'; /* Right arrow character */
    position: absolute;
    top: 50%;
    right: -45px; /* Position to the right of the box */
    transform: translateY(-50%);
    font-size: 3.5rem; /* Larger arrow */
    color: #A5D6A7; /* Brighter green for arrow */
    z-index: 0; /* Ensure arrow is behind content but visible */
}

/* Hide arrow on last item or when wrapping */
@media (max-width: 992px) {
    .steps-container .step-item::after {
        content: none; /* Hide arrows when items wrap */
    }
}


/* Direct Payments & Local Support Section Styles - THIS SECTION IS REMOVED */


/* Responsive Design */
@media (max-width: 768px) {
    .hero h1 {
        font-size: 2.5rem;
    }
    .hero p {
        font-size: 1rem;
    }
    .hero-buttons .btn {
        padding: 10px 20px;
        font-size: 1rem;
    }
    .how-it-works-section h2 { /* Removed .direct-payment-section h2 */
        font-size: 2.2rem;
    }
    .how-it-works-section p.intro { /* Removed .direct-payment-section p.intro */
        font-size: 1rem;
    }
    .step-item { /* Removed .info-card */
        max-width: 90%;
    }
    /* Removed .info-card specific responsive styles */
}

@media (max-width: 576px) {
    .hero h1 {
        font-size: 2rem;
    }
    .hero p {
        font-size: 0.9rem;
    }
    .hero-buttons .btn {
        padding: 8px 16px;
        font-size: 0.9rem;
    }
    .how-it-works-section h2 { /* Removed .direct-payment-section h2 */
        font-size: 1.8rem;
    }
    .step-item::before {
        top: -20px;
        width: 35px;
        height: 35px;
        font-size: 1.2rem;
    }
    .step-item .icon { /* Removed .info-card .icon */
        font-size: 3rem;
    }
    .step-item h3 { /* Removed .info-card h3 */
        font-size: 1.6rem;
    }
    /* Removed .info-card specific responsive styles */
}


</style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <section class="hero">
        <div class="carousel-slide active" style="background-image: url('css/image/a.png');">
            <div class="hero-content">
                <h1>Find Home <span>Service/Repair</span> Near You</h1>
                <p>Explore Best Home Service & Repair near you</p>
                <div class="hero-buttons">
                    <a href="service.php" class="btn secondary">Our Services</a>
                </div>
            </div>
        </div>
        <div class="carousel-slide" style="background-image: url('css/image/d.jpg');">
            <div class="hero-content">
                <h1>Quality Home <span>Solutions</span> Await</h1>
                <p>Your trusted partner for all home service needs</p>
                <div class="hero-buttons">
                    <a href="about.php" class="btn secondary">About</a>
                </div>
            </div>
        </div>
        <div class="carousel-slide" style="background-image: url('css/image/e.jpg');">
            <div class="hero-content">
                <h1>Expert <span>Repair & Maintenance</span> Services</h1>
                <p>Connecting you with the best professionals</p>
                <div class="hero-buttons">
                    <a href="gallery.php" class="btn secondary">Gallery</a>
                </div>
            </div>
        </div>

        <div class="carousel-dots">
            <span class="dot active" data-slide="0"></span>
            <span class="dot" data-slide="1"></span>
            <span class="dot" data-slide="2"></span>
        </div>
    </section>

    <section class="how-it-works-section">
        <div class="container">
            <h2>How It Works</h2>
            <p class="intro">Getting your home services done is **quick and easy** with us. Follow these simple steps:</p>
            <div class="steps-container">
                <div class="step-item" data-step="1">
                    <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                    <h3>Book Your Service</h3>
                    <p>Tell us what you need and when you need it. Browse categories or search directly for a service.</p>
                </div>
                <div class="step-item" data-step="2">
                    <div class="icon"><i class="fas fa-user-check"></i></div>
                    <h3>Connect with a Pro</h3>
                    <p>We'll match you with a vetted and skilled professional in your area, ready to help.</p>
                </div>
                <div class="step-item" data-step="3">
                    <div class="icon"><i class="fas fa-tools"></i></div>
                    <h3>Get the Job Done</h3>
                    <p>Your chosen professional will arrive and complete the task efficiently and to your satisfaction.</p>
                </div>
                <div class="step-item" data-step="4">
                    <div class="icon"><i class="fas fa-thumbs-up"></i></div>
                    <h3>Pay & Review</h3>
                    <p>**Pay your service provider directly upon completion of the work and share your valuable feedback!**</p>
                </div>
            </div>
        </div>
    </section>

    <script src="js/script.js"></script>
</body>
</html>