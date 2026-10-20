# CVHRDC — Cagayan Valley Health Research and Development Consortium

A web-based research management and dissemination platform built with **Laravel 10** for the Cagayan Valley Health Research and Development Consortium (CVHRDC). The system provides a public-facing portal and a role-gated backend for managing research outputs, events, news, calls for proposals, and institutional content.

---

## Table of Contents

- [System Overview](#system-overview)
- [Technology Stack](#technology-stack)
- [Roles & Permissions](#roles--permissions)
  - [SuperAdmin](#-superadmin)
  - [Admin](#-admin)
  - [RMC — Research Management Committee](#-rmc--research-management-committee)
  - [RUC — Research Utilization Committee](#-ruc--research-utilization-committee)
  - [User (Researcher)](#-user-researcher)
- [Default Credentials](#default-credentials)
- [Local Setup](#local-setup)
- [Project Structure](#project-structure)

---

## System Overview

CVHRDC serves as a centralized digital hub for health research management in the Cagayan Valley region of the Philippines. It supports the full research lifecycle — from submission of proposals, management of research outputs, to public dissemination of publications, news, events, and partner information.

**Key capabilities at a glance:**

| Feature | Description |
|---|---|
| 🌐 Public Portal | Landing page with news, events, projects, publications, announcements, and active calls for proposals |
| 📄 Research Repository | Upload and manage research papers, preprints, conference papers, and books |
| 📢 Calls for Proposals | RMC creates calls; RUC reviews and publishes them to the public |
| 👥 User Management | Role-based access control with soft-delete and restore |
| ⚙️ CMS | Editable site header, banner, and footer |
| 🛡️ Maintenance Mode | Admin can put the site in maintenance mode with a custom message |
| 💾 Database Backups | Scheduled and on-demand database backup with downloadable files |
| 📋 Activity Logs | System-level and user-level activity logging |

---

## Technology Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 10 (PHP ^8.1) |
| Frontend | Bootstrap 5, Vite, Sass/SCSS, Tailwind CSS |
| Database | MySQL (via XAMPP) |
| Auth | Laravel Breeze + Laravel UI |
| File Storage | Laravel Storage (local disk) |
| Backup | `spatie/laravel-backup` |
| CAPTCHA | `anhskohbo/no-captcha` |
| API Client | Guzzle HTTP |

---

## Roles & Permissions

The system uses a **many-to-many role-user** model. Each user can be assigned one or more roles. Access is enforced via middleware and gate policies.

---

### 👑 SuperAdmin

The highest level of access in the system. The SuperAdmin role is reserved for platform-level administration.

> Currently, this role routes to the same Admin dashboard. A dedicated SuperAdmin panel is scaffolded but not yet fully built (`/superadmin` prefix, `can:superadmin-access` gate).

**Can do everything the Admin can do**, plus platform-level configuration as the feature is extended.

---

### 🛠️ Admin

Accessed via `/admin`. Protected by the `admin` middleware (covers both `admin` and `superadmin` roles).

#### Content Management
| Area | Actions |
|---|---|
| **News** | Create, view, edit, delete, toggle visibility |
| **Projects** | Create, view, edit, delete, upload images, toggle visibility |
| **Events** | Create, view, edit, delete, toggle visibility |
| **Publications** | Create, view, edit, delete, toggle visibility |
| **Announcements** | Create, view, edit, delete, toggle visibility |
| **Partners** | Create, view, edit, delete, toggle visibility |
| **Researchers** | Create, view, edit, delete |

#### Research Output Management *(Admin review of user-submitted content)*
| Type | Actions |
|---|---|
| **Research Papers** | View, edit, update visibility, soft-delete, restore, force-delete |
| **Preprints** | View, edit, update visibility, soft-delete, restore, force-delete |
| **Conference Papers** | View, edit, update visibility, soft-delete, restore, force-delete |
| **Books** | View, edit, update visibility, soft-delete, restore, force-delete |

#### User & Role Management
- View, create, edit, soft-delete, and **restore** user accounts
- Assign and modify roles
- Manage role definitions (name + description)

#### Site Settings & CMS
- Edit site **Header** (title, subtitle, logo)
- Edit site **Banner** (title, subtitle, image, link)
- Edit site **Footer** (org name, description, address, contact details)
- Edit **CMS pages**
- Toggle **Maintenance Mode** on/off with a custom message

#### Database Backup
- Configure automated backup settings
- Trigger **on-demand backups**
- **Download** backup files

#### Activity Logs
- View system-level logs and user activity logs

---

### 📋 RMC — Research Management Committee

Accessed via `/rmc`. Protected by the `rmc` middleware.

The RMC is responsible for creating and managing **Calls for Proposals** and reviewing submissions received from researchers.

#### Calls for Proposals
| Action | Details |
|---|---|
| **Create** a Call | Define title, description, deadline, format file, and other metadata |
| **Edit / Update** | Modify existing calls |
| **Delete** | Remove a call |
| **Toggle Visibility** | Show/hide a call (subject to RUC review before public posting) |
| **Download Format File** | Download the attached proposal format document |

#### Proposal Submissions
| Action | Details |
|---|---|
| **View all submissions** | See all proposals submitted under any of their calls |
| **View per-call submissions** | Filter submissions by a specific call |
| **View submission details** | Read the full submission including uploaded document |
| **Update submission status** | Approve, reject, or change the status of a submitted proposal |
| **Download submission document** | Download the researcher's submitted proposal file |

---

### 🔍 RUC — Research Utilization Committee

Accessed via `/ruc`. Protected by the `ruc` middleware.

The RUC acts as a **quality gate** between the RMC and the public. They review calls created by the RMC and decide which ones are published publicly.

#### Calls Review & Publishing
| Action | Details |
|---|---|
| **View all calls** | See every call for proposals in the system |
| **View a specific call** | Review the full details of a call |
| **Update call status** | Approve a call for public posting or mark it for revision |
| **View calls pending review** | Filtered list of calls awaiting RUC action |
| **View published calls** | Filtered list of calls already made public |
| **Download format file** | Download the proposal format document attached to a call |

> **Note:** The RUC does **not** create calls — that is exclusively the RMC's responsibility. The RUC's role is review and publication control.

---

### 👤 User (Researcher)

Accessed via `/users`. Protected by the `can:user-access` gate.

Regular authenticated users are typically **researchers** who submit their outputs to the system and respond to calls for proposals.

#### Research Output Management *(own submissions only)*
Users can only view, edit, and delete their **own** submissions.

| Output Type | Actions |
|---|---|
| **Research Papers** | Submit, view, edit, delete, restore, force-delete, toggle visibility, download |
| **Preprints** | Submit, view, edit, delete, restore, force-delete, toggle visibility, download |
| **Conference Papers** | Submit, view, edit, delete, restore, force-delete, toggle visibility, download |
| **Books** | Submit, view, edit, delete, restore, force-delete, toggle visibility, download |

#### Proposal Submissions
| Action | Details |
|---|---|
| **Browse open calls** | View public calls for proposals |
| **Submit a proposal** | Upload a proposal document in response to an active call |
| **View own submissions** | Track the status of their submitted proposals |
| **Download own submission** | Download the file they submitted |

#### Feedback
- Submit feedback through the system
- View their own previously submitted feedback

#### Activity Feed
- View a personal activity timeline tracking all creates, updates, and deletes across their research outputs

#### Profile
- Edit personal profile information
- Change password
- Delete account

---

## Default Credentials

> ⚠️ **Change these immediately** before deploying to a production environment.

| Role | Email | Password |
|---|---|---|
| SuperAdmin | `superadmin@mail.com` | `super*admin` |
| Admin | `admin@gmail.com` | `admin` |
| RMC | `rmc@gmail.com` | `rmc123` |
| RUC | `ruc@gmail.com` | `ruc123` |
| User | `user@mail.com` | `user` |

---

## Local Setup

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL)
- PHP ^8.1 (bundled with XAMPP)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) + npm

### Steps

```bash
# 1. Clone the repository into your XAMPP htdocs folder
git clone <repo-url> C:\xampp\htdocs\CVHRDC
cd C:\xampp\htdocs\CVHRDC

# 2. Install PHP dependencies
composer install

# 3. Copy and configure environment
copy .env.example .env
php artisan key:generate

# 4. (Optional) Update .env with your DB credentials
#    Default XAMPP: DB_HOST=127.0.0.1, DB_USERNAME=root, DB_PASSWORD=

# 5. Create the database (start XAMPP MySQL first)
mysql -u root -e "CREATE DATABASE IF NOT EXISTS cvhrdc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 6. Run migrations and seeders
php artisan migrate
php artisan db:seed

# 7. Link public storage
php artisan storage:link

# 8. Install and build frontend assets
npm install
npm run build
```

## Project Structure

```
CVHRDC/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin panel controllers
│   │   │   ├── RMC/            # RMC-specific controllers
│   │   │   ├── RUC/            # RUC-specific controllers
│   │   │   ├── Users/          # Researcher-facing controllers
│   │   │   ├── Public/         # Unauthenticated public routes
│   │   │   └── SuperAdmin/     # SuperAdmin (in progress)
│   │   └── Middleware/         # Auth & role middleware
│   └── Models/                 # Eloquent models
├── database/
│   ├── migrations/             # Database schema
│   └── seeders/                # Default data (roles, users, settings)
├── resources/
│   ├── views/                  # Blade templates
│   └── scss/                   # Stylesheets
├── routes/
│   └── web.php                 # All application routes
├── public/                     # Web root (point Apache here)
├── storage/                    # File uploads
└── .env                        # Environment configuration
```

---

*CVHRDC Web System — Cagayan Valley, Philippines*
