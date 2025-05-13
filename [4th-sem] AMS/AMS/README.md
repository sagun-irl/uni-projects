# Attendance Management System
A project on Attendance Management System (AMS) submitted as a partial fulfillment of the requirements for academic degree.

## Description
The Attendance Management System project in PHP is a web-based application designed to streamline the process of attendance management. This project addresses the needs of teachers and professors within the education industry. With a user-friendly interface and robust functionality, the system facilitates attendance management as well as user management.

## Features
The project divides features and functionalities between three user roles:

### Admin
A role with the highest level of access, responsible for managing the system and overseeing all users. Features include:
- User management (CRUD operations for students, teachers and other admins)
- List and view users, with filters for Department and Batch
- View attendance records based on:
  - **Per-student:** Records of a student, for a specific day, month or year
  - **Per-class:** Records of a class, for a specific day
- Notify users about their account creation and updates via email

### Teacher
A role with a moderate level of access, responsible for managing attendance records. Features include:
- View list of enrolled students, based on Department and Batch
- Mark today's attendance for students in a class
- View attendance records, with filters same as Admin

### Student
A role with the minimum level of access, capable of viewing their own attendance records. Features include:
- View list of enrolled students, based on Department and Batch
- View their own attendance report, for a specific day, month or year

## Limitations
- Attendance is managed on per-day basis, rather than per-subject basis.
- Teachers cannot be promoted to admin (and vice versa) directly. A new account must be created.
- It doesn’t support modern techniques for login and authentication, such as 2FA, passkeys and fingerprints.
- Passwords are stored in plaintext, rather than hashed. (This is a security risk and needs to be addressed in production environments.)

## Tech Stack
### Frontend
- HTML
- Tailwind
- JavaScript

### Backend
- PHP
- MySQL / MariaDB

## Usage Guidelines

### Dependencies / Extensions
The server needs to have `MySQLi` extension enabled to interact with the database. Ensure that `php.ini` has the following line un-commented:
```ini
;extension=<...>
extension=mysqli
;extension=<...>
```

### Running the Server

On first run, the database and necessary tables need to be initialized using `create-db.php`:
```console
$ php -f ./create-db.php
```

---

The project can then be deployed using PHP dev server or Apache:
```console
$ php -S localhost:<port> -t ./server-root
```
> [!Note]
> Apache server is required for `.htaccess` directives (such as directory hiding, error pages) to take effect.

---

Or for GUI users who prefer using Laragon:

> [!Tip]
> The root directory for Laragon is `laragon/www/`

1. Download this project as a `.zip`
2. Extract the contents and move the `server-root/` directory to the app's root directory (preferably, rename it to `AMS`)
3. Launch the app
4. Start the server
5. Open http://AMS.test in a web browser

### Populating Database
For a swift assessment of the functionalities, the database tables can be quickly populated with random values using `populate-db.php`. `create-db.php` still needs to be run if database hasn't been created yet.
```console
$ php -f ./populate-db.php
```

> [!Tip]
> If students are sparse in attendance lists, run the populate script a few more times.

## Authors
- [@Suprim-Maharjan](https://github.com/Suprim-Maharjan)
- [@SuggonM *(Sagun)*](https://github.com/SuggonM)
