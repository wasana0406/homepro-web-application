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

Home page
<img width="1345" height="630" alt="Image" src="https://github.com/user-attachments/assets/3325be92-1131-415f-8e7d-e7234c939e2a" />

Services page
<img width="1349" height="633" alt="Image" src="https://github.com/user-attachments/assets/d91c52e0-f279-4167-8286-89223722c41f" />

Booking page
<img width="1350" height="630" alt="Image" src="https://github.com/user-attachments/assets/63288bf6-5d8e-4f10-9745-c6db3f18272d" />

Login page
 <img width="1266" height="629" alt="Image" src="https://github.com/user-attachments/assets/b1805567-d39d-4c70-8087-025dc2f6b31d" />

Provider Dashboard page
<img width="1324" height="629" alt="Image" src="https://github.com/user-attachments/assets/1a3ef718-66f0-4552-a06d-0b08ebfe83b9" />











   
