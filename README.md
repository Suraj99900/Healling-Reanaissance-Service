<p align="center"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></p>

<p align="center">
<b>Healling Renaissance Service</b><br>
A Laravel-based platform for video management, user enrollment, access control, and analytics dashboard.
</p>

---

## Project Overview

**Healling Renaissance Service** is a web application built with Laravel.  
It provides:

- User management and access control
- Video upload and category management
- Enrollment management
- API and screen analytics dashboard (hits, unique visitors, time spent, user agent, etc.)
- Responsive UI for both desktop and mobile

---

## Features

- **User Management:** Create, update, and manage application users.
- **Access Management:** Control user roles and permissions.
- **Video Management:** Upload, categorize, and manage videos.
- **Enrollment List:** Manage enrollments for users.
- **Dashboard Analytics:** 
  - Total/unique hits per endpoint
  - Time spent on each screen/API
  - User agent tracking
  - Recent activity logs
  - Filter by date and endpoint

---

## Getting Started

### Prerequisites

- PHP 8.1+
- Composer
- MySQL/MariaDB or compatible DB
- Node.js & npm (for frontend assets)

### Installation

```bash
git clone https://github.com/your-org/Healling-Reanaissance-Service.git
cd Healling-Reanaissance-Service
composer install
cp .env.example .env
php artisan key:generate
# Configure your .env for DB and mail
php artisan migrate
npm install && npm run build
php artisan serve
```

---

## Usage

- Visit `/login` to access the admin dashboard.
- Use the dashboard to manage users, videos, categories, and enrollments.
- View analytics and logs on the dashboard home.

---

## Project Structure

- `app/Http/Controllers/` — Main controllers (Dashboard, User, Video, etc.)
- `app/Models/` — Eloquent models (User, Video, ApiLog, etc.)
- `resources/views/` — Blade templates for UI
- `routes/web.php` — Web routes
- `routes/api.php` — API routes
- `app/Http/Middleware/LogApiRequests.php` — Middleware for logging API/screen activity

---

## Analytics & Logging

- All API and screen requests are logged in `api_logs` table.
- Tracks: endpoint, IP, user, time spent, user agent, status, and more.
- Dashboard displays stats and recent activity.

---

## Contributing

Pull requests are welcome!  
Please open issues for bugs or feature requests.

---

## License

This project is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).