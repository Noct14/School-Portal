<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 📚 School Portal

**School Portal** is a web-based school payment management system designed to streamline the tuition payment process. The platform allows parents to make **online payments via Virtual Account (VA)**, while school administrators can efficiently manage student data, tuition fees, and payment reports.

## ✨ Features

### 🎓 For Students / Parents
- **Dashboard** – View tuition payment status.
- **Payment History** – Track previous payments.
- **Online Payment** – Secure payments using **Virtual Account (VA)**.
- **Payment Notifications** – Automatic confirmation upon successful payment.

### 🏫 For Admin (School Staff)
- **Student Management** – Add, edit, and remove student data.
- **Tuition Fee Management** – Set fees based on academic years and apply scholarship discounts.
- **Payment Verification** – Auto-verification via VA.
- **Generate Invoice** – Create and send invoices to students.
- **Reports & Visualization** – Generate and view payment reports.

## 🌐 Accessing the System

| Role                | URL                                  |
|---------------------|--------------------------------------|
| Student / Parent Panel | [http://127.0.0.1:8000/](http://127.0.0.1:8000/) |
| Admin Panel        | [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin) |

## ⚙️ Technologies Used
- **Backend:** Laravel 10, PHP 8
- **Frontend:** Filament 3
- **Database:** MySQL
- **Server:** WAMP (Localhost)
- **Payment Gateway:** Midtrans (Virtual Account Integration)

## 🚀 Installation Guide

To set up this project on your local machine, follow these steps:

1. Clone the repository and navigate to the project directory:
   ```sh
   git clone https://github.com/your-username/school-portal.git
2. Navigate to the project directory:
   ```sh
   cd school-portal
3. Install dependencies:
   ```sh
   composer install
   npm install
4. Copy the .env file:
   ```sh
   cp .env.example .env
5. Generate an application key:
   ```sh
   php artisan key:generate
6. Configure the database in .env, then run:
   ```sh
   php artisan migrate --seed
7. Start the local server:
   ```sh
   php artisan serve

## 🏦 Payment Integration
This system supports Virtual Account (VA) payments. To enable this feature, integrate with a payment provider such as Midtrans or other available gateways.
