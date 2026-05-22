# PropTrack — Property Listings & Inquiry Management System

A production-quality Laravel application built as a full-stack technical assessment.  
Demonstrates Service/Repository architecture, role-based access control, queue-driven notifications, and a bonus REST API with Sanctum token authentication.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 (PHP 8.2+) |
| Database | MySQL 8+ |
| Auth | Laravel Breeze (Blade + Tailwind) |
| Roles | Spatie Laravel Permission v6 |
| API Auth | Laravel Sanctum v4 |
| Image Processing | Intervention Image v3 |
| Queue | Database driver (jobs table) |
| Mail | Log driver in dev / SMTP in production |
| CSS | Tailwind CSS v4 via Vite |

---

## Architecture

```
app/
├── Enums/                  # PropertyType, PropertyStatus, InquiryStatus (PHP 8.1 backed enums)
├── Http/
│   ├── Controllers/        # Thin resource controllers; no business logic
│   │   └── Api/            # Sanctum-authenticated API controllers
│   ├── Requests/           # Form Requests for all validation
│   └── Resources/          # API JSON transformers
├── Jobs/                   # SendInquiryNotificationJob (queued)
├── Mail/                   # NewInquiryMail mailable
├── Models/                 # Eloquent models with relationships, scopes, soft-deletes
├── Policies/               # Authorization (PropertyPolicy, InquiryPolicy)
├── Repositories/           # Data access layer — interface + implementation
├── Services/               # Business logic (PropertyService, InquiryService, DashboardService, ImageService)
└── Traits/                 # ApiResponse trait
```

**Roles:** `admin` (full access) · `agent` (own properties + inquiries on those properties only)

---

## Installation

### 1. Clone & install dependencies

```bash
git clone <repo-url> proptrack
cd proptrack
composer install
npm install
```

### 2. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```env
APP_NAME=PropTrack
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=proptrack
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=log          # or smtp for production
QUEUE_CONNECTION=database
```

### 3. Database

```bash
# Create the database first, then:
php artisan migrate
php artisan db:seed
```

This seeds:
- 1 admin user
- 2 agent users
- 20 fake property listings with images

**Seeded credentials:**

| Role | Email | Password |
|---|---|---|
| Admin | admin@proptrack.com | password |
| Agent 1 | agent1@proptrack.com | password |
| Agent 2 | agent2@proptrack.com | password |

### 4. Storage link

```bash
php artisan storage:link
```

### 5. Build frontend assets

```bash
npm run build        # production
npm run dev          # development with HMR
```

### 6. Start the server

```bash
php artisan serve
```

---

## Queue Worker

All inquiry notification emails are dispatched to the queue. Start the worker:

```bash
# Development — process one job at a time
php artisan queue:work --once

# Development — continuous
php artisan queue:work

# Production — supervisor recommended
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

Check email output (log driver):
```bash
tail -f storage/logs/laravel.log
```

---

## REST API

Base URL: `http://localhost:8000/api`

All responses follow the envelope:
```json
{
  "status": true,
  "message": "Success",
  "data": {}
}
```

### Authentication

```
POST /api/auth/login
Body: { "email": "...", "password": "..." }
Response: { "token": "...", "token_type": "Bearer", "user": { ... } }

POST /api/auth/logout  (requires Bearer token)
```

### Endpoints

| Method | Endpoint | Auth | Rate Limit |
|---|---|---|---|
| GET | `/api/listings` | Public | 60/min |
| GET | `/api/listings/{id}` | Public | 60/min |
| POST | `/api/inquiries` | Sanctum token | 10/min |
| POST | `/api/auth/login` | — | 5/min |
| POST | `/api/auth/logout` | Sanctum token | — |

### Example — List active properties

```bash
curl http://localhost:8000/api/listings?city=Sydney&per_page=6
```

### Example — Submit inquiry (token required)

```bash
TOKEN=$(curl -s -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"agent1@proptrack.com","password":"password"}' \
  | jq -r '.data.token')

curl -X POST http://localhost:8000/api/inquiries \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"property_id":1,"name":"Jane Doe","email":"jane@example.com","message":"Is this still available?"}'
```

---

## ERD — Entity Relationship Diagram

```
users
  id, name, email, password, email_verified_at, remember_token, timestamps

model_has_roles  (Spatie pivot)
  role_id, model_type, model_id

roles  (Spatie)
  id, name, guard_name, timestamps

properties
  id, user_id (FK→users), title, description, type (enum), status (enum),
  price (decimal 15,2), bedrooms, bathrooms, area (decimal 10,2),
  city (indexed), address, featured_image, deleted_at (soft-delete), timestamps
  INDEX: city, status, user_id, price

property_images
  id, property_id (FK→properties CASCADE), image_path, is_primary,
  sort_order, timestamps

inquiries
  id, property_id (FK→properties CASCADE), name, email, phone,
  message (text), status (enum: new/in_review/contacted/closed),
  timestamps
  INDEX: property_id, status

personal_access_tokens  (Sanctum)
  id, tokenable_type, tokenable_id, name, token (hashed), abilities,
  last_used_at, expires_at, timestamps

jobs / failed_jobs  (Queue)
```

**Relationships:**
- `User` hasMany `Property` (one agent owns many listings)
- `Property` belongsTo `User` (the listing agent)
- `Property` hasMany `PropertyImage`
- `Property` hasMany `Inquiry`
- `Inquiry` belongsTo `Property`
- `User` hasMany roles via `model_has_roles` (Spatie)

---

## Deployment Checklist

### Environment
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
QUEUE_CONNECTION=database
MAIL_MAILER=smtp
SESSION_DRIVER=database
```

### Optimization commands
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan storage:link
npm run build
```

Single command:
```bash
php artisan optimize
```

### Queue (Supervisor example config)
```ini
[program:proptrack-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/proptrack/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/proptrack-queue.log
```

---

## Final Testing Checklist

### Authentication
- [ ] Register new account
- [ ] Login as admin (`admin@proptrack.com / password`)
- [ ] Login as agent (`agent1@proptrack.com / password`)
- [ ] Password reset flow works

### Public Site
- [ ] Home page displays property grid (3 columns desktop)
- [ ] Search by title/city/address works
- [ ] Filter by city, type, price range works
- [ ] Clearing filters resets results
- [ ] Property detail page shows image, specs, and inquiry form
- [ ] Inquiry form submits, success message appears
- [ ] Pagination works

### Admin Dashboard
- [ ] Total stats are accurate
- [ ] Status breakdown progress bars render
- [ ] Top 5 listings by inquiries table loads
- [ ] Recent inquiries table loads
- [ ] Can view/edit/delete any property
- [ ] Can change inquiry status (New → In Review → Contacted → Closed)

### Agent Panel
- [ ] Only sees own properties and inquiries
- [ ] Stat cards reflect agent-scoped data
- [ ] Cannot delete a property with active (New/In Review) inquiries — button disabled with tooltip
- [ ] Can delete a property when no active inquiries — confirmation prompt works

### Property CRUD
- [ ] Create property with image upload — thumbnail generated in `storage/app/public/properties/thumbs/`
- [ ] Edit property — existing image shown, new upload replaces it
- [ ] Soft delete — property disappears from public listings but remains in DB
- [ ] Policy enforcement — agent cannot edit another agent's property (403)

### Inquiry Workflow
- [ ] New inquiry triggers queued email job (`php artisan queue:work --once`)
- [ ] Email appears in `storage/logs/laravel.log`
- [ ] Inquiry shows correct status badge

### REST API
- [ ] `GET /api/listings` returns paginated JSON with `{status, message, data: {items, pagination}}`
- [ ] `GET /api/listings/{id}` returns single property
- [ ] `POST /api/auth/login` returns Bearer token
- [ ] `POST /api/inquiries` (with token) creates inquiry
- [ ] `POST /api/inquiries` (without token) returns 401
- [ ] Rate limiting headers present (`X-RateLimit-Limit`, `X-RateLimit-Remaining`)

### Security
- [ ] CSRF protection on all forms
- [ ] Unauthenticated users redirected from dashboard
- [ ] Agent cannot access admin-only routes
- [ ] No N+1 queries (verify with Laravel Debugbar at `/_debugbar`)

---

## Queue Commands Reference

```bash
# Run all pending jobs once
php artisan queue:work --once

# Monitor queue
php artisan queue:monitor database

# Retry all failed jobs
php artisan queue:retry all

# Clear failed jobs table
php artisan queue:flush

# View failed jobs
php artisan queue:failed
```

---

## Commit History

```
feat: setup laravel project with authentication, roles, and clean architecture structure
feat: implement database schema with users, properties, images, inquiries, relationships, and authorization rules
feat: implement property listings module with service-repository architecture, image handling, filters, and role-based access control
feat: implement inquiry management system with guest submissions, queued email notifications, and status workflow
feat: implement admin and agent dashboards with analytics, role-scoped metrics, and secure deletion safeguards
feat: implement REST API with Sanctum auth, API Resources, rate limiting, and JSON envelope
feat: phase 7 — light theme overhaul, N+1 fixes, 3-column grid, aspect-ratio images, production docs
```
