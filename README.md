# Record Management System (Laravel)

## Overview

This project is a **Record Management System** built using **Laravel 12**, designed to demonstrate **clean architecture, secure coding practices, and containerized deployment**.

The application follows real-world backend development standards including:

- Secure RESTful design
- Clean separation of concerns (Service + Repository pattern)
- Docker-based deployment for consistency

---

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.2)
- **Database:** MySQL 8
- **Web Server:** Apache
- **Containerization:** Docker & Docker Compose
- **ORM:** Eloquent
- **Authentication:** Laravel Auth

---

## Features

### Functional Requirements

- User Authentication (Login / Logout)
- Record Management (CRUD)
- Server-side Validation
- Search & Filtering
- Pagination
- Database Seeders

---

## Non-Functional Requirements

### Security

The application demonstrates protection against:

- **SQL Injection**
    - Eloquent ORM & parameter binding
- **Cross-Site Scripting (XSS)**
    - Blade auto-escaping
- **Cross-Site Request Forgery (CSRF)**
    - Laravel CSRF middleware

### Architecture & Design

- **Repository Pattern** – Abstracts database access
- **Service Layer** – Handles business logic
- **Thin Controllers** – Only request/response handling
- **Form Request Validation** – Centralized validation

---

## Deployment Strategy

### Prerequisites

- Docker
- Docker Compose
- Git



### Step 1: Clone Repository

```bash
git clone <repository-url>
cd record-management-laravel
```


### Step 2: Environment Setup

```bash
cp .env.example .env
```

Update environment values if needed.


### Step 3: Generate App Key

```bash
 php artisan key:generate
```


### Step 4: Build & Run Containers

```bash
docker compose up --build
```

### One-Command Rebuild (Optional)
If you want a clean rebuild:

```bash
docker compose down -v
docker compose up --build
```


### Step 5: Access Application

```
http://localhost:5000
```

---

## Challenges & Solutions

### Docker + Environment Configuration

- Challenge:
  
  Environment variables and app key not being picked up correctly due to bind mounts.

- Solutions:

    1. Explicit use of .env with Docker env_file
    2. Clear separation between host and container responsibilities

### Database Initialization Timing

- Challenge:
  
  Application attempted migrations before MySQL was ready.

- Solutions:

    1. Added database readiness check in container entrypoint
    2. Ensured migrations run only after DB connection is available

---

## Security & Performance

### Security

- CSRF tokens
- Input validation
- Environment-based secrets

### Performance

- Pagination
- Optimized queries (Database indexes were added on searchable fields (e.g., title, price, avaiable_at) to improve query performance.)
- Config caching ready

---

## Future Improvements

- Role-based access control
- API versioning
- Redis caching
- Queue workers
- CI/CD pipeline
- Automated tests
- Horizontal scaling

---



## Conclusion
### This project demonstrates:
- Secure Laravel development
- Clean architecture principles
- Docker-based deployment
  
The structure allows easy scaling, testing, and maintenance while remaining interview-friendly and easy to evaluate.
