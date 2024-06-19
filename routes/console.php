<?php

use App\Console\Commands\CheckPaymentsCommand;
use App\Console\Commands\CreateAdminUserCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command(CheckPaymentsCommand::class)->everyTenMinutes();

Artisan::command('create:admin {name} {email} {password}', function ($name, $email, $password) {
    $this->call(CreateAdminUserCommand::class, [
        'name' => $name,
        'email' => $email,
        'password' => $password,
    ]);
})->describe('Create a new admin user');
