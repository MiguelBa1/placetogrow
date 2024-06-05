<?php

use App\Console\Commands\CheckPaymentsCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(CheckPaymentsCommand::class)->everyTenMinutes();
