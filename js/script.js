document.addEventListener('DOMContentLoaded', function() {
    // Select all carousel slides and navigation dots
    const slides = document.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('.dot');

    let currentSlide = 0; // Keep track of the currently active slide
    const slideInterval = 5000; // Time in milliseconds (5000ms = 5 seconds)

    // Function to display a specific slide
    function showSlide(index) {
        // First, remove the 'active' class from all slides and dots
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));

        // Then, add the 'active' class to the desired slide and its corresponding dot
        // This makes the slide visible (due to opacity: 1 in CSS) and highlights the dot
        slides[index].classList.add('active');
        dots[index].classList.add('active');

        currentSlide = index; // Update the current slide index
    }

    // Function to move to the next slide
    function nextSlide() {
        // Calculate the next slide index.
        // The modulo operator (%) ensures it wraps around to 0 after the last slide.
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide); // Display the next slide
    }

    // Initialize: Show the first slide when the page loads
    showSlide(currentSlide);

    // Set up automatic slide changing
    // setInterval calls nextSlide() repeatedly every 'slideInterval' milliseconds
    let autoSlideTimer = setInterval(nextSlide, slideInterval);

    // Add click event listeners to the navigation dots
    dots.forEach(dot => {
        dot.addEventListener('click', function() {
            // Get the slide index from the 'data-slide' attribute of the clicked dot
            const slideIndex = parseInt(this.dataset.slide);
            showSlide(slideIndex); // Show the slide corresponding to the clicked dot

            // When a dot is clicked, reset the automatic timer
            // This prevents the automatic change from happening immediately after a manual click
            clearInterval(autoSlideTimer); // Clear the existing timer
            autoSlideTimer = setInterval(nextSlide, slideInterval); // Start a new timer
        });
    });
});