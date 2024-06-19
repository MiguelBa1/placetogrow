<?php

use App\Constants\Role;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MicrositeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/language', [LanguageController::class, 'update'])->name('language.update');

Route::prefix('profile')->middleware('auth')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
});

Route::prefix('microsites')->name('microsites.')->group(function () {
    Route::middleware(['auth', 'role:' . Role::ADMIN->value])->group(function () {
        Route::get('/create', [MicrositeController::class, 'create'])->name('create');
        Route::post('/', [MicrositeController::class, 'store'])->name('store');
        Route::prefix('{microsite}')->group(function () {
            Route::get('/edit', [MicrositeController::class, 'edit'])->name('edit');
            Route::put('/', [MicrositeController::class, 'update'])->name('update');
            Route::delete('/', [MicrositeController::class, 'destroy'])->name('destroy');
        });
    });

    Route::get('/', [MicrositeController::class, 'index'])->name('index');
    Route::get('/{microsite}', [MicrositeController::class, 'show'])->name('show');

});

Route::prefix('categories')->name('categories.')->middleware(['auth', 'role:' . Role::ADMIN->value])->group(function () {
    Route::post('/', [CategoryController::class, 'store'])->name('store');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    Route::get('/', [CategoryController::class, 'index'])->name('index');
});

Route::post('payments/', [PaymentController::class, 'store'])->name('payment.store');

Route::get('/site1', function () {
    return Inertia::render('Site1/Index');
})->name('site1');

Route::get('/site1/return/{reference}', [PaymentController::class, 'return'])->name('site1.return');

require __DIR__.'/auth.php';
