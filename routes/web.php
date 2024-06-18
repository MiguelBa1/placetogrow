<?php

use App\Constants\Role;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MicrositeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/language', [LanguageController::class, 'update'])->name('language.update');

Route::middleware('auth')->group(function () {
    $profileRoute = '/profile';
    Route::get($profileRoute, [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch($profileRoute, [ProfileController::class, 'update'])->name('profile.update');
    Route::delete($profileRoute, [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:' . Role::ADMIN->value])->group(function () {
    Route::get('microsites/create', [MicrositeController::class, 'create'])->name('microsites.create');
    Route::post('microsites', [MicrositeController::class, 'store'])->name('microsites.store');
    Route::get('microsites/{microsite}/edit', [MicrositeController::class, 'edit'])->name('microsites.edit');
    Route::put('microsites/{microsite}', [MicrositeController::class, 'update'])->name('microsites.update');
    Route::delete('microsites/{microsite}', [MicrositeController::class, 'destroy'])->name('microsites.destroy');
});

Route::get('microsites', [MicrositeController::class, 'index'])->name('microsites.index');
Route::get('microsites/{microsite}', [MicrositeController::class, 'show'])->name('microsites.show');

require __DIR__.'/auth.php';
