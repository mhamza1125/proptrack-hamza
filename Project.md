Use this as your master AI prompt. It is structured so the AI builds the project in professional phases instead of generating messy code all at once.

---

# MASTER AI PROMPT — PROPTRACK (Laravel Full Stack Assessment)

I am building a production-quality Laravel application called **PropTrack** for a technical assessment.

I want you to act as a **Senior Laravel Architect + Senior Full Stack Engineer**.

You must guide and generate the project in **phases**.

IMPORTANT RULES:

* Use Laravel 12+
* Use PHP 8.3+
* Use MySQL
* Use Laravel Breeze authentication
* Use Blade + Tailwind CSS
* Use Service Layer or Repository Pattern
* Follow PSR-12 standards
* Controllers must remain thin
* Use Eloquent only
* Use Form Requests for validation
* Use Policies + Middleware for authorization
* Use Queues for email sending
* Use proper relationships and eager loading
* Use clean folder structure
* Use soft deletes for listings
* Use ENUMs where appropriate
* Use migration indexes on frequently filtered columns
* Use resourceful controllers
* Avoid code duplication
* Generate scalable architecture

DO NOT SKIP STEPS.

DO NOT GENERATE EVERYTHING AT ONCE.

I want to build the application in structured professional phases.

Always explain:

1. Why we are doing something
2. Best practices
3. Folder structure
4. Commands to run
5. File names being created
6. Relationship architecture

---

# PROJECT DETAILS

Application Name:
PropTrack — Property Listings & Inquiry Management System

Roles:

1. Admin
2. Agent

Main Modules:

1. Authentication & Roles
2. Property Listings
3. Inquiry Management
4. Admin Dashboard
5. Agent Panel
6. Public Listings
7. Search & Filters
8. Queue-based Email Notifications
9. REST API (Bonus)

---

# DEVELOPMENT FLOW

We will build the project in these phases:

## PHASE 1 — Initial Project Setup

First help me setup the entire Laravel project professionally.

Guide me step-by-step with commands and explanations.

Include:

### Installation

* Create Laravel project
* Setup .env
* Generate app key
* Configure database
* Run migrations

### Install Required Packages

Use latest stable packages only.

Install and configure:

* Laravel Breeze
* Spatie Laravel Permission
* Laravel Debugbar (dev only)
* Laravel Pint
* Intervention Image (for thumbnails)
* Laravel IDE Helper (optional)
* Laravel Queue setup
* Laravel Sanctum (for bonus API)

### Configure:

* Tailwind
* Vite
* Queue system
* Mail setup
* Storage link
* Local filesystem

### Create Professional Folder Structure

Create folders such as:

* Services
* Repositories
* Interfaces
* Enums
* Traits
* Actions
* DTOs (optional)
* Helpers

Explain why each exists.

### Authentication Setup

* Install Breeze
* Configure authentication
* Setup roles system
* Seed roles automatically

### Git Setup

* Initialize git
* Create meaningful commit plan
* Suggest commit messages

### Deliverables for this phase

At the end provide:

* Exact terminal commands
* Final folder structure
* Packages installed
* Next phase preview

STOP after Phase 1.

Wait for my confirmation before Phase 2.

---

# PHASE 2 — Database Architecture & Models

After I say continue, help me design the database professionally.

You must generate:

## Database Design

Tables:

* users
* roles
* properties
* property_images
* inquiries

Use:

* Foreign keys
* Cascade rules
* Indexes
* Soft deletes
* ENUMs

## Relationships

Explain all Eloquent relationships:

* User hasMany Properties
* Property belongsTo User
* Property hasMany Inquiries
* etc.

## Generate:

* Migrations
* Models
* Enums
* Factories
* Seeders

## Add:

* Admin Seeder
* 2 Agent Seeders
* Fake property seeders

## Validation Rules

Define all validation requirements.

## Policies

Generate authorization policies.

STOP after Phase 2.

---

# PHASE 3 — Property Listings Module

Build complete listings system professionally.

Include:

## Features

* CRUD
* Soft delete
* Image upload
* Thumbnail generation
* Validation
* Authorization
* Pagination
* Filters
* Search
* Public listings page
* Property detail page

## Architecture

Use:

* Form Requests
* Services
* Repositories
* Policies
* Resource Controllers

## Public Filters

* City
* Property type
* Price range
* Status

## UI

Use Blade + Tailwind.

Generate:

* Routes
* Controllers
* Views
* Components
* Services
* Repository methods

Use reusable Blade components where possible.

STOP after Phase 3.

---

# PHASE 4 — Inquiry Management System

Build inquiry module professionally.

Features:

* Public inquiry form
* Guest submission
* Validation
* Inquiry storage
* Queue email notifications
* Mailable classes
* Status workflow

Statuses:

* New
* In Review
* Contacted
* Closed

Rules:

* Only Admin changes status
* Agents only see inquiries for their properties

Generate:

* Migrations
* Models
* Requests
* Controllers
* Mailables
* Queue Jobs
* Dashboard tables

Use queues properly.

Explain queue worker setup.

STOP after Phase 4.

---

# PHASE 5 — Admin Dashboard & Agent Panel

Build dashboards professionally.

## Admin Dashboard

Show:

* Total listings
* Listings by status
* Total inquiries
* Inquiries by status
* New inquiries in last 7 days
* Top 5 listings by inquiries

Use:

* Eloquent aggregates
* withCount()
* eager loading

## Agent Panel

Show:

* Agent listings
* Agent inquiries
* Quick status updates

Rule:
Agents cannot delete listings with active inquiries in:

* New
* In Review

Implement this rule professionally.

STOP after Phase 5.

---

# PHASE 6 — API (BONUS)

Build Sanctum-secured API.

Endpoints:

* GET /api/listings
* GET /api/listings/{id}
* POST /api/inquiries

Requirements:

* API Resources
* JSON response format
* Validation
* API authentication
* Rate limiting

Standard JSON structure:
{
"status": true,
"message": "Success",
"data": {}
}

STOP after Phase 6.

---

# PHASE 7 — Final Production Preparation

Help finalize project professionally.

Generate:

* README.md
* Queue commands
* Setup instructions
* Seeder instructions
* Deployment notes
* ERD guidance
* Loom recording checklist
* Final testing checklist

Also help me:

* Optimize queries
* Avoid N+1 problems
* Improve architecture
* Improve UI polish
* Improve interviewer impression

STOP after final phase.

---

# CODING STYLE REQUIREMENTS

Always:

* Use strict typing
* Use return types
* Use constructor property promotion
* Use clean naming conventions
* Use RESTful routes
* Use dependency injection
* Use reusable methods
* Use config/constants where appropriate

Never:

* Put business logic inside controllers
* Use raw SQL unless necessary
* Hardcode values
* Create giant controllers
* Duplicate validation logic

---

# RESPONSE FORMAT

For every phase:

1. Overview
2. Why this architecture
3. Commands to run
4. Files to create
5. Full code
6. Explanations
7. Best practices
8. Common mistakes to avoid
9. Commit suggestion
10. What we will do next

IMPORTANT:
Only generate ONE PHASE at a time.
Wait for my confirmation before continuing.

Start now with PHASE 1 ONLY.
