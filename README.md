# Hotel Booking System User Manual

## Table of Contents
1. Introduction
2. Prerequisites
3. Installation
4. Configuration
5. Usage
6. Troubleshooting
7. Credits
8. License

---

## 1. Introduction

Welcome to the Hotel Booking System project! This system is designed to facilitate hotel room booking and management. To get started, follow the instructions in this user manual to install and set up the system on your local machine.

## 2. Prerequisites

Before you begin, ensure that you have the following software and tools installed on your machine:

- XAMPP: This is a web server package that includes Apache, MySQL, and PHP. You can download it from https://www.apachefriends.org/index.html
- MySQL: The project requires MySQL for database management. You can download and install MySQL from https://dev.mysql.com/downloads/installer/. Make sure you have MySQL installed and your MySQL root password ready before proceeding with the installation.

## 3. Installation

Follow these steps to install the Hotel Booking System on your machine:

1. Download the project ZIP file from the GitHub repository.

2. Install XAMPP and make sure it's up and running.

3. Extract the contents of the project ZIP file into the `htdocs` folder of your XAMPP installation directory. This ZIP file should contain two important items: `hotel_db.sql` and a folder named "project" that contains the PHP, CSS, and JavaScript files.

## 4. Configuration

Now, let's set up the database and configure the system:

1. Open a command prompt and access the MySQL shell using the following command:
   
   mysql -u root -p
   

2. Create a new database named "hotel_db" by running the following command within the MySQL shell. You will be prompted to enter your MySQL root password:
   
   CREATE DATABASE hotel_db;
   

3. Exit the MySQL shell:
   
   exit;
   

4. Copy the full file directory path where the `hotel_db.sql` file is located, which should be inside the `htdocs` folder.

5. Open a command prompt again and execute the following command to import the SQL file into your "hotel_db" database. Make sure to replace `D:\xampp\htdocs\` with the path you copied in the previous step:
   
   mysql -u root -p hotel_db < D:\xampp\htdocs\hotel_db.sql
   

6. Open the file `htdocs/project/components/connect.php` in a text editor.

7. Locate line 5 in `connect.php`, which looks like this:
   
   $db_user_pass = 'rajdeep';
   

8. Replace `rajdeep` with your MySQL root password.

## 5. Usage

Now that the installation and configuration are complete, you can use the Hotel Booking System:

- Start the Apache server in your XAMPP application.

- Open a web browser (e.g., Chrome) and enter the following URLs:
   - For the user-end: http://localhost/project
   - For the admin-end: http://localhost/project/admin

Explore the system, create user accounts, and manage hotel bookings.

## 6. Troubleshooting

If you encounter any issues during the installation or configuration, refer to the Troubleshooting section in the GitHub repository or seek help from the project's contributors.

## 7. Credits

Rajdeep Das drajdeep.2003@gmail.com
Ayushman Nandi rishiayushman96@gmail.com

## 8. License

This project is open-source and distributed under the terms of the MIT License. You can find the license details in the repository.
---

Now you're ready to use the Hotel Booking System. Enjoy your experience, and feel free to contribute to the project or report any issues you encounter on the GitHub repository.










