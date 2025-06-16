<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.x-8892BF?logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Laravel-Framework-F55247?logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/MySQL-Database-00758F?logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Tailwind-CSS-38B2AC?logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Livewire-Components-4E56A6?logo=livewire&logoColor=white" alt="Livewire">
  <img src="https://img.shields.io/badge/AlpineJS-Frontend-8BC0D0?logo=alpine.js&logoColor=white" alt="AlpineJS">
</p>

<h1 align="center">📘 Hubspur Platform Documentation</h1>

<p align="center">
  <strong>Version:</strong> 1.0  
  <br />
  <strong>Maintained by:</strong> Patrick Nthiwa  
  <br />
  <strong>System Stack:</strong> PHP 8.x, Laravel, Tailwind CSS, MySQL, AlpineJS, Livewire
</p>

<hr />


## 🏢 Overview

**Hubspur** is an enterprise-grade ecommerce and membership management platform developed by **Patrick Nthiwa**. It enables efficient handling of product sales and investor/member shareholding activities, making it a hybrid solution for ecommerce and diaspora investment.

---

## 📂 Table of Contents

1. [System Architecture](#system-architecture)  
2. [Core Modules](#core-modules)  
3. [Member Management](#member-management)  
4. [Shareholding System](#shareholding-system)  
5. [Product & Order Management](#product--order-management)  
6. [Admin Panel Customizations](#admin-panel-customizations)  
7. [Routing & Permissions](#routing--permissions)  
8. [Database Schema Overview](#database-schema-overview)  
9. [Blade Templates & UI](#blade-templates--ui)  
10. [API Endpoints (Optional/Planned)](#api-endpoints-optionalplanned)  
11. [Deployment Notes](#deployment-notes)  
12. [Future Roadmap](#future-roadmap)  

---

## 🏗️ System Architecture

- **Laravel 10+**: MVC backend with Eloquent ORM  
- **Tailwind CSS**: Utility-first CSS framework  
- **Livewire + AlpineJS**: Interactive frontend experience  
- **MySQL**: Relational database  
- Modular service provider structure (admin + shop modules)

---

## 📦 Core Modules

### 1. Customer Module

- User registration/login  
- Profile management  
- Associated with shareholder/member entity  

### 2. Product Module

- Product creation & categorization  
- Inventory & pricing control  
- Digital and physical product support  

### 3. Order Module

- Cart, checkout, and payment logic  
- Order tracking  
- Customer notifications  

### 4. Member Management (Custom)

- Member list with status  
- Member details + associated customer  
- Capital and share unit tracking  

---

## 👥 Member Management

The Member Management module allows admin to manage (diaspora) investors.

### Key Features

- Unique member numbers  
- Active/Inactive status  
- Capital paid tracking  
- Share allocation interface  

### `shareholders` Table

| Column            | Type     | Description                      |
|-------------------|----------|----------------------------------|
| id                | int      | Primary key                      |
| shareholder_number| string   | Unique identifier                |
| customer_id       | int      | FK to `customers`                |
| is_active         | boolean  | Active status                    |
| capital_paid      | decimal  | Total amount paid                |
| timestamps        | auto     | Created/Updated at               |

---

## 📊 Shareholding System

Hubspur supports flexible share allocations across multiple share classes.

### Tables Involved

- `shares`: share class definitions  
- `shareholder_share`: pivot table linking members and shares  

### `shares` Table

| Column         | Type     | Description                  |
|----------------|----------|------------------------------|
| id             | int      | Primary key                  |
| class          | string   | Class name (e.g., A, B)      |
| price_per_unit | decimal  | Unit value                   |
| timestamps     | auto     | Created/Updated              |

### `shareholder_share` Pivot Table

| Column         | Type     | Description                    |
|----------------|----------|--------------------------------|
| shareholder_id | int      | FK to `shareholders`           |
| share_id       | int      | FK to `shares`                 |
| units          | int      | Units allocated                |
| timestamps     | auto     | Created/Updated                |

### Features

- Share allocation modal in admin  
- Unit updates per class  
- Automatic capital recalculation  

---

## 🛒 Product & Order Management

Standard ecommerce features:

- Product listing  
- Cart and checkout process  
- Orders with status tracking  
- Invoice generation  
- Customer order history

---

## 🔧 Admin Panel Customizations

### Custom Menu Structure

```php
return [
    [
        'key'   => 'mumbos',
        'name'  => 'MUMBOS',
        'route' => 'admin.mumbos.index',
        'icon'  => 'group',
        'sort'  => 2,
    ],
    [
        'key'   => 'mumbos.shareholders',
        'name'  => 'Members',
        'route' => 'admin.shareholders.index',
    ],
    [
        'key'   => 'mumbos.shares',
        'name'  => 'Share Classes',
        'route' => 'admin.shares.index',
    ]
];
