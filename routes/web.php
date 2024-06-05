<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn() => Inertia::render('Index'))->name('index');

Route::get('/site1', [PaymentController::class, 'index'])->name('site1');

Route::post('/site1', [PaymentController::class, 'store'])->name('site1.store');

Route::get('/site1/return/{reference}', [PaymentController::class, 'return'])->name('site1.return');
