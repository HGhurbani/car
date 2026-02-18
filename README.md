# Car Workshop Management System

A lightweight PHP + MySQL web application for managing workshop vehicles, maintenance operations, and service reports.

> **Interface language:** Arabic (RTL)  
> **Documentation language:** English

## Overview

This project provides an internal dashboard for workshop teams to:

- Authenticate staff users.
- Manage vehicle records.
- Track maintenance jobs by status.
- Filter and review maintenance reports.
- Export and import data (Excel/PDF utilities included).

## Core Features

- **Secure login flow** using hashed password verification (`password_verify`).
- **Dashboard analytics** for total vehicles, total maintenance records, and status summaries.
- **Vehicle management** (add, edit, list).
- **Maintenance management** (add, edit, list, status tracking).
- **Report filtering** by technician, status, region, and dates.
- **Data tools** for import/export workflows.
- **RTL-ready UI** built with Bootstrap and Cairo font for Arabic-first usage.

## Tech Stack

- **Backend:** PHP (PDO)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML, CSS, Bootstrap (RTL), JavaScript
- **UI Enhancements:** Font Awesome, DataTables, SweetAlert2, Toastify

## Project Structure

```text
.
├── assets/                # CSS/JS/fonts and static resources
├── includes/              # shared header/footer/config/functions
├── vehicles/              # vehicle CRUD pages
├── maintenance/           # maintenance CRUD pages
├── reports/               # reporting pages
├── import_export/         # Excel/PDF import-export utilities
├── login.php              # authentication page
├── logout.php             # logout endpoint
├── dashboard.php          # main dashboard
└── index.php              # dashboard entry point
```

## Getting Started

### 1) Requirements

- PHP 8.0+ (recommended)
- MySQL 5.7+ / MariaDB
- Web server (Apache/Nginx) or local stack (XAMPP, Laragon, MAMP)

### 2) Clone the repository

```bash
git clone <your-repo-url>
cd car
```

### 3) Configure database connection

Update DB credentials in:

- `includes/config.php`

Set:

- `$host`
- `$db`
- `$user`
- `$password`

### 4) Create database schema

Create the required tables (at minimum):

- `users`
- `vehicles`
- `maintenance`

> If you have a SQL dump for your environment, import it before first run.

### 5) Serve the project

Place the project in your web root (for example, `/car_workshop`), then open:

- `http://localhost/car_workshop/login.php`

## Authentication

- Users are authenticated from the `users` table.
- Passwords should be stored as **bcrypt hashes** (or compatible hashes supported by `password_hash`).

## Security Notes

- Do **not** commit real production credentials.
- Restrict DB user permissions to only what the app needs.
- Validate and sanitize all incoming data in production deployments.
- Add CSRF protection for form submissions if you plan to expose the app publicly.

## Roadmap Ideas

- Environment-variable based configuration (`.env`).
- Role-based access control (admin/technician/viewer).
- API endpoints for mobile integration.
- Unit and integration tests.
- Dockerized local setup.

## Contributing

1. Fork the repository.
2. Create a feature branch.
3. Commit your changes.
4. Open a pull request.

## License

Add your preferred license (e.g., MIT) in a `LICENSE` file.
