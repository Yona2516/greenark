# Greenark Consultants Website - Installation Guide

## Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js 16+ and npm
- MySQL 8.0+ or PostgreSQL 13+
- Web server (Apache/Nginx)

## Backend Setup (Laravel)

### 1. Clone and Setup Laravel Backend

```bash
# Create new Laravel project
composer create-project laravel/laravel greenark-backend
cd greenark-backend

# Install required packages
composer require filament/filament spatie/laravel-permission intervention/image