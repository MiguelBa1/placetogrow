
# Placetogrow - Microsite Payment Platform

## Description
Placetogrow is a platform that allows administrators to create and manage microsites for different types of payments (invoices, subscriptions, donations), and enables users to make payments in a simple and secure manner.

## Technologies Used
- PHP 8.2
- Laravel 11.0
- MySQL 8.0
- InertiaJs with Vue 3.4.0 and TypeScript 5.0.2
- Tailwind CSS 3.2.1
- Headless UI 1.7

## Installation and Setup
1. Clone the repository:
    ```sh
    git clone https://github.com/MiguelBa1/placetogrow
    ```

2. Create the `.env` file from the `.env.example` file:
    ```sh
    cp .env.example .env
    ```

3. Configure the necessary information in the `.env` file. Make sure to set the following variables:

   ### Database Connection
    - `DB_CONNECTION`
    - `DB_HOST`
    - `DB_PORT`
    - `DB_DATABASE`
    - `DB_USERNAME`
    - `DB_PASSWORD`

   ### Admin User
    - `ADMIN_NAME`
    - `ADMIN_EMAIL`
    - `ADMIN_PASSWORD`

   ### PlacetoPay Credentials
    - `P2P_LOGIN`
    - `P2P_SECRET_KEY`
    - `P2P_URL`


4. Run `composer install` to install the dependencies.
5. Run `php artisan key:generate` to set the `APP_KEY` value in the `.env` file.
6. Run `php artisan storage:link` to make the images available to the application.
7. Run `php artisan migrate:fresh --seed` to create the database tables and seed the database.
8. Run `php artisan serve` to start the application.
9. Run `npm install` and `npm run dev` to compile the assets.

**Note:** To use the image manipulation features in the application, the PHP `gd` extension must be enabled. Please ensure that this extension is enabled on your server.

### Creating an Admin User
To access the admin panel, you need an admin user. You can create this user in one of the following ways:

1. By filling in the admin user variables in the `.env` file as mentioned above.
2. By running the following artisan command:
    ```sh
    php artisan create:admin {name} {email} {password}
    ```

This command allows you to directly create an admin user with the specified name, email, and password.

The system commands are defined in the `routes/console.php` file.

### Queue Configuration
The application uses queues for background jobs. You can set the queue driver in the `.env` file:

- `QUEUE_CONNECTION=sync` (processes jobs immediately)
- `QUEUE_CONNECTION=database` (processes jobs in the background using the database)

If using `database`, run the artisan command `php artisan queue:work` to start processing the jobs.

### Artisan Commands
The application has several Artisan commands that handle different processes, such as checking payments, updating subscription statuses, and calculating late fees for invoices. Here's a list of the available commands:

1. **Check Payments Status:**
   ```sh
   php artisan check:payments
   ```
   This command checks the status of payments every ten minutes.

2. **Check Subscriptions Status:**
   ```sh
   php artisan check:subscriptions
   ```
   This command checks the status of subscriptions every ten minutes.

3. **Update Invoice Status:**
   ```sh
   php artisan invoice:update-status
   ```
   This command updates the status of invoices daily based on their expiration date.

4. **Collect Subscription Payments:**
   ```sh
   php artisan subscriptions:collect-payments
   ```
   This command collects payments for active subscriptions and runs daily.

5. **Dispatch Late Fees Calculation:**
   ```sh
   php artisan dispatch:late-fees
   ```
   This command calculates late fees for pending invoices and runs daily.

6. **Create Admin User:**
   ```sh
   php artisan create:admin {name} {email} {password}
   ```
   This command creates a new admin user with the specified name, email, and password.

### Scheduled Jobs
In addition to the Artisan commands, the application also schedules jobs that handle notifications and other background tasks:

1. **Notify Upcoming Subscription Charge:**
    - **Job:** `NotifyUpcomingSubscriptionChargeJob`
    - **Schedule:** Runs daily on the 'low' priority queue.
    - Sends notifications to users about upcoming subscription charges.

2. **Notify Subscription Expiration:**
    - **Job:** `NotifySubscriptionExpirationJob`
    - **Schedule:** Runs daily on the 'low' priority queue.
    - Notifies users when their subscriptions are about to expire.

3. **Notify Invoice Due Soon:**
    - **Job:** `NotifyInvoiceDueSoonJob`
    - **Schedule:** Runs daily on the 'low' priority queue.
    - Alerts users when an invoice is due soon.

### Additional Information
- The scheduled commands and jobs are defined in the `routes/console.php` file, making use of Laravel's task scheduling feature.
- To manually start the scheduler in development, run:
  ```sh
  php artisan schedule:work
  ```
  This command will ensure that scheduled tasks are executed as expected.
