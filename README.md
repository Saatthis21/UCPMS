# University Centralised Procurement Management System (UCPMS)
> **Status:** 🚧 Active Development (Final Year Project)

A role-based Laravel web application engineered to digitise university procurement workflows, automated vendor evaluation, and departmental budget tracking.

## Key Features
* **4-Tier Role-Based Access Control (RBAC):** Custom permissions for Applicants, Department Heads, Bursary Officers, and Vendors.
* **12-Table Relational Database:** Designed for request tracking, quotation submissions, and budget allocations.
* **AI-Powered Evaluation:** Integrated Google Gemini AI via background queues to evaluate vendor quotations asynchronously.
* **Audit & Logbook Management:** Automated record-keeping and structured document workflows.

## Tech Stack
* **Framework:** Laravel (PHP)
* **Database:** MySQL / MariaDB
* **AI Integration:** Google Gemini API
* **Frontend:** Blade, HTML5, CSS3, JavaScript
* **Version Control:** Git & GitHub

## Installation & Setup
1. Clone the repository.
2. Run `composer install` and `npm install`.
3. Copy `.env.example` to `.env` and update your database and `GEMINI_API_KEY` credentials.
4. Run `php artisan migrate --seed`.
5. Run `php artisan serve`.