# 📚 Attendance Management System

A full-featured, role-based **Attendance Management System** built with Laravel 11, featuring enterprise-grade access control, real-time WhatsApp notifications via the official Meta Cloud API, and a secure, production-ready session architecture.

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat&logo=php)
![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Status](https://img.shields.io/badge/status-active-success.svg)

---

## 📖 Overview

This system manages attendance, tasks, and leave requests across four distinct user roles — **Admin, HR, Teacher, and Student** — with automated WhatsApp notifications keeping students informed at every step of their academic lifecycle.

---

## ✨ Key Features

### 🔐 Role-Based Access Control (RBAC)
Built on the [Spatie Laravel-Permission](https://spatie.be/docs/laravel-permission) package for granular, database-driven authorization.

- Four hierarchical roles: `Admin`, `HR`, `Teacher`, `Student`
- Legacy hardcoded `role` column fully removed in favor of dedicated roles & permissions tables
- Centralized authorization checks using `hasRole()` and `can()` throughout the app
- Smart routing: `DashboardController` automatically redirects each user to their secured portal (`/admin/dashboard`, `/student/dashboard`, etc.) based on their assigned role

### 💬 Real-Time WhatsApp Notifications (Meta Cloud API)
Fully automated messaging powered directly by Meta's official WhatsApp Business Cloud API — no third-party providers (e.g., Twilio) required.

| Trigger Event | Notification Sent |
|---|---|
| ✅ Attendance Marked | Instant confirmation with timestamp |
| 📝 Leave Requested | Pending status confirmation |
| 📌 Task Assigned | Task title & due date |
| 📊 Task Graded | Approved/Rejected result |

**Implementation details:**
- New `whatsapp_number` column added to the `users` table via migration
- Registration form (`register.blade.php`) updated to capture WhatsApp numbers at sign-up
- Modular `WhatsAppService` class built on Laravel's `Http` client for clean, reusable message dispatching
- Meta API credentials (Access Token, Phone Number ID) managed securely via `.env`

### 🛠️ System Stability & Bug Fixes

**"419 Page Expired" (CSRF Token Mismatch)**
- **Cause:** Session tokens fell out of sync between the database and browser cookies across server restarts
- **Fix:** Switched `SESSION_DRIVER` to `cookie`, storing encrypted session state directly in the browser and eliminating filesystem/database sync issues

**"403 Forbidden" (Broken Admin Routing)**
- **Cause:** Legacy routing logic still referenced the deleted `role` column, misidentifying Admins as Students
- **Fix:** Refactored the `isAdmin()` helper in the `User` model to check Spatie roles/permissions correctly

---

## 🧱 Tech Stack

| Layer | Technology |
|---|---|
| **Framework** | Laravel 11 (PHP) |
| **Frontend** | Laravel Breeze — Blade Templates + Tailwind CSS |
| **Database** | MySQL |
| **Authorization** | Spatie Laravel-Permission |
| **Messaging** | Meta Graph API (WhatsApp Business Cloud API) |
| **Sessions & Security** | Cookie-based Sessions, CSRF Protection |
| **Rich Text** | CKEditor (task management) |
| **Local Environment** | XAMPP |

---

## 🔄 How It Works

**Login & Routing**
1. User logs in → `DashboardController` intercepts the request
2. Assigned Spatie role is evaluated
3. User is redirected to their role-specific dashboard

**Notification Lifecycle**
1. A triggering action occurs (attendance marked, leave requested, task assigned/graded)
2. `WhatsAppService` builds and dispatches the message via the Meta Cloud API
3. The student receives a real-time WhatsApp notification

---

## 🚀 Getting Started

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL
- Node.js & npm
- A Meta Developer account with WhatsApp Business API access

### Installation

```bash
# Clone the repository
git clone https://github.com/<your-username>/attendance-management-system.git
cd attendance-management-system

# Install PHP dependencies
composer install

# Install frontend dependencies
npm install && npm run build

# Copy environment file
cp .env.example .env
php artisan key:generate

# Configure your database and Meta API credentials in .env
# DB_DATABASE, DB_USERNAME, DB_PASSWORD
# WHATSAPP_ACCESS_TOKEN, WHATSAPP_PHONE_NUMBER_ID

# Run migrations and seeders
php artisan migrate --seed

# Serve the application
php artisan serve
```

### Environment Variables

```env
SESSION_DRIVER=cookie

WHATSAPP_ACCESS_TOKEN=your_meta_access_token
WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id
```

---

## 📂 Project Structure (Key Files)

```
app/
├── Http/Controllers/
│   └── DashboardController.php     # Role-based routing logic
├── Models/
│   └── User.php                    # hasRole()/can() checks, isAdmin() helper
├── Services/
│   └── WhatsAppService.php         # Meta Cloud API message dispatch
resources/views/auth/
│   └── register.blade.php          # WhatsApp number capture
database/migrations/
│   └── ..._add_whatsapp_number_to_users_table.php
```

---

## 🗺️ Roadmap
- [ ] Email notification fallback for undelivered WhatsApp messages
- [ ] Admin analytics dashboard for attendance trends
- [ ] Mobile app companion (Flutter)

---

## 📄 License
This project is licensed under the MIT License.

---

## 👤 Author
Built and maintained by **Rayan** — Software Engineering student & Applied AI Product Engineer in training.
