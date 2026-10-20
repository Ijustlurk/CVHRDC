# CVHRDC — Role Credentials (Local Dev)

> **⚠️ For local development and testing use only.**
> All passwords were last reset via `reset_passwords.php`.

---

## 🔐 Password (All Accounts)

```
password123
```

---

## 👤 User Accounts by Role

| # | Role | Name | Email |
|---|------|------|-------|
| 1 | Super Admin | SuperAdmin User | superadmin@mail.com |
| 2 | Admin | Administrator | admin@gmail.com |
| 3 | RMC | RMC User | rmc@gmail.com |
| 4 | RUC | RUC User | ruc@gmail.com |
| 5 | Editor-in-Chief | asd | asd@gmail.com |
| 6 | Publishing Coordinator | John | asd123@gmail.com |
| 7 | Peer Review Coordinator | prc1 | prc1@gmail.com |
| 8 | Reviewer | Reviewer 1 | reviewer1@gmail.com |
| 9 | Reviewer | Reviewer 2 | reviewer2@gmail.com |
| 10 | Reviewer | Reviewer 3 | reviewer3@gmail.com |
| 11 | User | User | user@mail.com |
| 12 | User | Another Test | anothertest@example.com |

---

## 🗂️ Role Descriptions

| Role | Access Level | Primary Responsibilities |
|------|-------------|--------------------------|
| **Super Admin** | Full system access | Manage users, roles, system settings, backups |
| **Admin** | High-level access | Manage content, users, and configurations |
| **RMC** | Research Management | Create and manage Calls for Proposals, review submissions |
| **RUC** | Research Unit Coordinator | Approve Calls for Proposals, manage visibility/status |
| **Editor-in-Chief** | Publishing | Oversee publications, books, conference papers |
| **Publishing Coordinator** | Publishing | Coordinate research output publishing workflow |
| **Peer Review Coordinator** | Review Management | Assign reviewers to proposal submissions |
| **Reviewer** | Review only | Accept/decline invitations, submit peer review verdicts |
| **User** | Researcher / Public | Submit proposals, view research outputs |

---

## 🚀 Quick Login Reference

### Admin Panel Access
| Role | Email | Password |
|------|-------|----------|
| Super Admin | `superadmin@mail.com` | `password123` |
| Admin | `admin@gmail.com` | `password123` |
| RMC | `rmc@gmail.com` | `password123` |
| RUC | `ruc@gmail.com` | `password123` |

### Content & Publishing
| Role | Email | Password |
|------|-------|----------|
| Editor-in-Chief | `asd@gmail.com` | `password123` |
| Publishing Coordinator | `asd123@gmail.com` | `password123` |

### Proposal Review Pipeline
| Role | Email | Password |
|------|-------|----------|
| Peer Review Coordinator | `prc1@gmail.com` | `password123` |
| Reviewer 1 | `reviewer1@gmail.com` | `password123` |
| Reviewer 2 | `reviewer2@gmail.com` | `password123` |
| Reviewer 3 | `reviewer3@gmail.com` | `password123` |

### Regular Users
| Role | Email | Password |
|------|-------|----------|
| User | `user@mail.com` | `password123` |
| User (alt) | `anothertest@example.com` | `password123` |

---

*To reset all passwords again, run from the project root:*
```bash
php reset_passwords.php
```
