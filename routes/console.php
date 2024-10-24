<?php

use App\Console\Commands\CheckPaymentsCommand;
use App\Console\Commands\CheckSubscriptionsCommand;
use App\Console\Commands\CollectSubscriptionPaymentsCommand;
use App\Console\Commands\CreateAdminUserCommand;
use App\Console\Commands\DispatchInvoiceLateFeesCommand;
use App\Console\Commands\UpdateInvoiceStatusCommand;
use App\Jobs\NotifyInvoiceDueSoonJob;
use App\Jobs\NotifySubscriptionExpirationJob;
use App\Jobs\NotifyUpcomingSubscriptionChargeJob;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command(CheckPaymentsCommand::class)->everyTenMinutes();

Artisan::command('check:payments', function () {
    $this->call(CheckPaymentsCommand::class);
})->describe('Check payments status');

Schedule::command(CheckSubscriptionsCommand::class)->everyTenMinutes();

Artisan::command('check:subscriptions', function () {
    $this->call(CheckSubscriptionsCommand::class);
})->describe('Check subscriptions status');

Schedule::command(UpdateInvoiceStatusCommand::class)->daily();

Artisan::command('invoice:update-status', function () {
    $this->call(UpdateInvoiceStatusCommand::class);
})->describe('Update invoice status based on expiration date');

Artisan::command('create:admin {name} {email} {password}', function ($name, $email, $password) {
    $this->call(CreateAdminUserCommand::class, [
        'name' => $name,
        'email' => $email,
        'password' => $password,
    ]);
})->describe('Create a new admin user');

Schedule::command(CollectSubscriptionPaymentsCommand::class)->daily();

Artisan::command('subscriptions:collect-payments', function () {
    $this->call(CollectSubscriptionPaymentsCommand::class);
})->describe('Collect payments for active subscriptions');

Schedule::job((new NotifyUpcomingSubscriptionChargeJob())->onQueue('low'))->daily();
Schedule::job((new NotifySubscriptionExpirationJob())->onQueue('low'))->daily();
Schedule::job((new NotifyInvoiceDueSoonJob())->onQueue('low'))->daily();

Artisan::command('dispatch:late-fees', function () {
    $this->call(DispatchInvoiceLateFeesCommand::class);
})->describe('Dispatch late fees calculation for pending invoices');

Schedule::command(DispatchInvoiceLateFeesCommand::class)->daily();
