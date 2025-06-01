# Student Management System

A comprehensive student management system with user authentication, CRUD operations, and role-based access control.

## Features

- **Authentication System**
  - Login and registration with secure password hashing
  - Role-based access control (admin/student)
  - Form validation with real-time feedback

- **Student Management**
  - Complete CRUD operations for student data
  - Search functionality with filtering
  - Google Maps integration for directions

- **Admin Features**
  - Dashboard with statistics
  - Student management
  - Activity history tracking

- **Security Features**
  - Input sanitization
  - Password hashing
  - Session management
  - CSRF protection

## Technical Implementation

- **Frontend**: HTML, CSS, JavaScript, Bootstrap 5
- **Backend**: PHP
- **Database**: MySQL
- **External APIs**: Google Maps (for directions)

## Installation

1. **Set up the database**:
   - Import the `config/setup.sql` file into your MySQL server
   - This will create the necessary database, tables, and default admin user

2. **Configure database connection**:
   - Edit `config/database.php` to match your MySQL settings

3. **Server requirements**:
   - PHP 7.4 or higher
   - MySQL 5.7 or higher
   - Web server (Apache/Nginx)

## Default Admin Access

- Username: admin
- Password: admin123

## Project Structure

- `/admin` - Admin-specific pages
- `/auth` - Authentication-related pages
- `/assets` - CSS, JavaScript, and other static files
- `/config` - Configuration files
- `/includes` - Shared components (header, footer)
- `/student` - Student-specific pages

