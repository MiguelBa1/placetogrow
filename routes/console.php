<?php

use App\Console\Commands\CheckPaymentsCommand;
use App\Console\Commands\CollectSubscriptionPaymentsCommand;
use App\Console\Commands\CreateAdminUserCommand;
use App\Console\Commands\UpdateInvoiceStatusCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command(CheckPaymentsCommand::class)->everyTenMinutes();

Artisan::command('check:payments', function () {
    $this->call(CheckPaymentsCommand::class);
})->describe('Check payments status');

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
