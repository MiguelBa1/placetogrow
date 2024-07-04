# Placetogrow - Microsite Payment Platform

## Description
Placetogrow is a platform that allows administrators to create and manage microsites for different types of payments (invoices, subscriptions, donations), and enables users to make payments in a simple and secure manner.

## Technologies Used
- Laravel 11.0
- MySQL 8.0
- InertiaJs with Vue 3.4.0 and TypeScript 5.0.2
- Tailwind CSS 3.2.1

## Installation and Setup
1. Clone the repository:
```
git clone https://github.com/MiguelBa1/placetogrow
```

2. Create the `.env` file from the `.env.example` file:
```
cp .env.example .env
```

3. Configure the necessary information in the `.env` file.
4. Run `composer install` to install the dependencies.
5. Run `php artisan key:generate` to set the `APP_KEY` value in the `.env` file.
6. Run `php artisan storage:link` to make the images available to the application.
7. Run `php artisan migrate:fresh --seed` to create the database tables and seed the database.
8. Run `php artisan serve` to start the application.
9. Run `npm install` and `npm run dev` to compile the assets.

**Note:** To use the image manipulation features in the application, the PHP `gd` extension must be enabled. Please
ensure that this extension is enabled on your server.

## .Env File
The `.env` file contains the application configuration. It is important to configure the following variables:

### Database Connection
- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### Admin User

In order to access the admin panel, you need to create an admin user. You can do this by running the following command:
```
php artisan create:admin {name} {email} {password}
```

Or you can create the user filling the following variables in the `.env` file:

- `ADMIN_NAME`
- `ADMIN_EMAIL`
- `ADMIN_PASSWORD`

