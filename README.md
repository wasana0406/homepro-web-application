  HomePro — Home Services Web Application 
  
 HomePro is a simple web-based platform to connect customers with local home service providers (cleaning, plumbing, electrical, painting, shifting, etc.). It supports customer & provider roles, admin approval for providers, appointment booking, and basic admin management.
 
 Features

- Customer registration & login.
- Service provider registration with profile and SIP (Service Initiation Payment) receipt upload (requires admin approval).
- Browse services and service providers grouped by category.
- Book appointments (prevents double-booking for same provider/date).
- Provider dashboard to accept/reject appointments.
- Admin panel to manage users, providers, appointments and reports.
- Session-based authentication, password hashing and basic input validation.

Tech Stack

- Frontend: HTML, CSS, JavaScript, Bootstrap
- Backend: PHP
- Database: MySQL (phpMyAdmin)
- Local dev: XAMPP / Apache

  #  Installation (local)
1. Install XAMPP (or a similar Apache + PHP + MySQL stack).
2. Clone the repo:
   `bash
   git clone <https://github.com/wasana0406/homepro-web-application.git>
 3. Put project folder inside htdocs (for XAMPP) or your webserver root.
 4. Create a MySQL database, e.g. clean_homepro.
 5. Import the provided SQL (if available) or create tables:
      users
      service_provider
      appointments
      admin
 6. Update DB connection in config.php (host, username, password, database, port):
      $servername = "localhost";
      $username = "root";
      $password = "";
      $database = "clean_homepro";
      $conn = mysqli_connect($servername, $username, $password, $database);
    
    <img width="653" height="428" alt="Image" src="https://github.com/user-attachments/assets/2dd4a426-39b6-446a-840a-78f6d967742f" />

 8. Make sure uploads/ folder exists and is writable by the web server (set correct permissions).
 9. Open in browser: http://localhost/homepro/

















   
