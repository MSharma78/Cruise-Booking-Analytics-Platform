🛳️ NICE Cruise Booking & Analytics Platform

NICE Cruise Lines is a full-stack database-driven web application built with PHP, MySQL, and XAMPP that allows users to explore cruise destinations, register and log in, book cruise trips, manage passengers, and view invoices — all powered by a secure backend architecture following RESTful principles.

🚀 Features
👤 User Module

User registration and login with hashed passwords (password_hash, password_verify)

Session-based authentication ($_SESSION['user_id'])

CRUD operations for user profiles (Create, Read, Update, Delete)

⚓ Booking Module

View available cruise trips, ports, and destinations

Select staterooms and book cruises transactionally

Automatically handle group bookings for adults and children

Generate invoice summaries dynamically

👨‍✈️ Admin Module

Admin login for managing trips, users, and bookings

CRUD operations on ports, staterooms, and destinations

📦 Database Layer

MySQL database with normalized schema (3NF)

Use of stored procedures, foreign keys, and transactions

Example tables: users, adminlogin, eam_trip, eam_port, eam_group, eam_booking, eam_passenger

🌐 RESTful Architecture

Each PHP file acts as a REST-style endpoint

register.php → POST /users

login.php → POST /auth

destinations.php → GET /destinations

booking.php → POST /bookings

passenger_details.php → POST /passengers

Follows REST principles:

Stateless operations

Resource-based separation

CRUD mapped to HTTP methods (GET, POST, PUT, DELETE)

🧰 Tech Stack
Layer	Technology
Frontend	HTML5, CSS3, minimal JS
Backend	PHP (Procedural)
Database	MySQL (via XAMPP / phpMyAdmin)
Architecture	REST-style modular design
Security	Prepared Statements, Password Hashing, Session Management
🗂️ Project Structure
dbmsproject/
│
├── index.php                # Home page
├── register.php             # User registration (POST)
├── login.php                # User login (POST)
├── booking.php              # Cruise booking (POST)
├── passenger_details.php    # Add passengers (POST)
├── destinations.php         # Display destinations (GET)
├── invoice.php              # Booking invoice (GET)
├── admin/                   # Admin panel files
│   ├── admin_login.php
│   ├── manage_users.php
│   └── manage_trips.php
└── db_connect.php           # Database connection config

🧩 Database Schema Overview
🔹 Key Tables
Table	Description
users	Stores registered users
adminlogin	Stores admin credentials
eam_port	Cruise port details
eam_port_address	Address info for ports
eam_trip	Trip details (start/end dates, ports)
eam_stateroom	Cabin categories
eam_stateroom_location	Stateroom location mapping
eam_trip_strm_price	Price per night per room
eam_group	Stores group booking data
eam_booking	Links groups with trips
eam_passenger	Passenger details for each group
⚙️ How to Run Locally

Download and Install XAMPP
https://www.apachefriends.org

Start Apache and MySQL from the XAMPP Control Panel.

Clone this project or copy it into:

C:\xampp\htdocs\dbmsproject


Import the database schema

Open http://localhost/phpmyadmin

Create a new database (e.g., cruise_db)

Import your SQL file containing all eam_ tables and stored procedures.

Update your database credentials
Inside db_connect.php:

$conn = new mysqli("localhost", "root", "", "cruise_db");


Run the project
Visit http://localhost/dbmsproject

🔒 Security & Reliability

Passwords hashed with PASSWORD_DEFAULT

SQL injection prevented using prepared statements

Transactions ensure booking consistency

Session management protects user identity

Stored procedures reduce direct SQL exposure

💬 REST API Example (Booking Flow)
Step	HTTP Method	Endpoint	Description
1	POST	/register.php	Register new user
2	POST	/login.php	Authenticate and start session
3	GET	/destinations.php	Fetch cruise destinations
4	POST	/booking.php	Create a booking transaction
5	POST	/passenger_details.php	Add passenger info
6	GET	/invoice.php	View generated invoice
🧠 Learning Outcomes

Designed a normalized MySQL schema with stored procedures and triggers

Implemented secure user authentication

Applied REST architecture principles in PHP

Managed relational joins across multiple entities

Gained experience in transactional consistency, CRUD, and error handling

👩‍💻 Team
Role	Name
Developer	Meghna Sharma
Collaborators	[Your teammates’ names if any]
Course	Principles of Database Systems – NYU Tandon
🏁 Future Enhancements

Convert PHP forms to true REST API endpoints returning JSON

Add AJAX / React frontend for live updates

Include payment gateway integration

Add data analytics dashboard for admins (Power BI / Tableau)
