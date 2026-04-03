# Running the Project

MyStockMaster is designed to be easily deployed on any standard PHP 8.3 / Laravel v12 environment. It also supports being built as a standalone desktop application via NativePHP.

## Prerequisites

Ensure your environment meets the following requirements:
- **PHP:** `^8.3`
- **Composer:** `^2.7`
- **Node.js:** `^22.x` & **npm:** `^10.x`
- **Database:** MySQL 8.0+, PostgreSQL 14+, or SQLite 3.

## Standard Installation

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/zakarialabib/mystockmaster.git
   cd mystockmaster
   ```

2. **Install PHP Dependencies:**
   ```bash
   composer install
   ```

3. **Environment Setup:**
   Copy the `.env.example` file to `.env` and configure your database and mail settings.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run Migrations & Seeders:**
   Ensure your database is running and execute:
   ```bash
   php artisan migrate --seed
   ```

5. **Install Node Dependencies & Build Assets:**
   ```bash
   npm install
   npm run build
   ```

6. **Start the Application:**
   Run the integrated development server using the concurrent `dev` script:
   ```bash
   composer dev
   ```
   This will simultaneously start the Laravel Server, Queue Worker, Vite server, and Log listener.

## NativePHP Desktop Build

To run or build the application as a standalone desktop executable (macOS, Windows, Linux):

1. **Development Mode:**
   ```bash
   composer native:dev
   ```

2. **Production Build:**
   ```bash
   php artisan native:build
   ```

## Documentation Server

This Code Wiki is powered by Vitepress. To view it locally:

1. **Start the Dev Server:**
   ```bash
   npm run docs:dev
   ```
2. **Build for Production:**
   ```bash
   npm run docs:build
   ```
