<?php

use App\Constants\Permission;
use App\Http\Controllers\CustomerInvoice\CustomerInvoiceController;
use App\Http\Controllers\CustomerSubscription\CustomerSubscriptionController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\Microsite\MicrositeController;
use App\Http\Controllers\MicrositeField\MicrositeFieldController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\RolePermission\RolePermissionController;
use App\Http\Controllers\Subscription\SubscriptionController;
use App\Http\Controllers\SubscriptionPayment\SubscriptionPaymentController;
use App\Http\Controllers\Support\LanguageController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/language', [LanguageController::class, 'update'])->name('language.update');

Route::prefix('profile')->middleware('auth')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
});

Route::prefix('roles-permissions')->name('roles-permissions.')
    ->middleware(['auth', 'permission:' . Permission::MANAGE_ROLES->value])
    ->group(function () {
        Route::get('/', [RolePermissionController::class, 'index'])->name('index');
        Route::post('/', [RolePermissionController::class, 'store'])->name('store');
        Route::prefix('{role}')->group(function () {
            Route::get('/', [RolePermissionController::class, 'edit'])->name('edit');
            Route::put('/', [RolePermissionController::class, 'update'])->name('update');
        });
    });

Route::prefix('microsites')->name('microsites.')->group(function () {
    Route::middleware(['auth'])->group(function () {

        Route::get('field-types', [MicrositeFieldController::class, 'getFieldTypes'])->name('fields.types');

        Route::get('/create', [MicrositeController::class, 'create'])->name('create');
        Route::post('/', [MicrositeController::class, 'store'])->name('store');
        Route::prefix('{microsite}')->group(function () {
            Route::get('/', [MicrositeController::class, 'show'])->name('show');
            Route::get('/edit', [MicrositeController::class, 'edit'])->name('edit');
            Route::post('/', [MicrositeController::class, 'update'])->name('update');
            Route::delete('/', [MicrositeController::class, 'destroy'])->name('destroy');
            Route::put('/restore', [MicrositeController::class, 'restore'])->name('restore');

            Route::prefix('fields')->name('fields.')->group(function () {
                Route::post('/', [MicrositeFieldController::class, 'store'])->name('store');
                Route::put('{field}', [MicrositeFieldController::class, 'update'])->name('update');
                Route::delete('{field}', [MicrositeFieldController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('invoices')->name('invoices.')->group(function () {
                Route::get('/', [InvoiceController::class, 'index'])->name('index');
                Route::post('/', [InvoiceController::class, 'store'])->name('store');
                Route::post('/import', [InvoiceController::class, 'import'])->name('import');

                Route::get('/download-template', [InvoiceController::class, 'downloadTemplate'])->name('download-template');
            });

            Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
                Route::get('/', [SubscriptionController::class, 'index'])->name('index');
                Route::get('/create', [SubscriptionController::class, 'create'])->name('create');
                Route::post('/', [SubscriptionController::class, 'store'])->name('store');
                Route::prefix('{subscription}')->group(function () {
                    Route::get('/edit', [SubscriptionController::class, 'edit'])->name('edit');
                    Route::put('/', [SubscriptionController::class, 'update'])->name('update');
                    Route::delete('/', [SubscriptionController::class, 'destroy'])->name('destroy');
                    Route::put('/restore', [SubscriptionController::class, 'restore'])->name('restore')->withTrashed();
                });
            });
        });

        Route::get('/', [MicrositeController::class, 'index'])->name('index');
    });
});

Route::prefix('users')->name('users.')->middleware(['auth'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::prefix('{user}')->group(function () {
        Route::put('/roles', [UserController::class, 'updateRoles'])->name('updateRoles');
    });
});

Route::prefix('payments')->name('payments.')->group(function () {
    Route::prefix('{microsite}')->group(function () {
        Route::get('/', [PaymentController::class, 'show'])->name('show');
        Route::post('/payment', [PaymentController::class, 'store'])->name('store');
    });
    Route::get('/return/{payment}', [PaymentController::class, 'return'])->name('return');
});

Route::prefix('subscription-payments')->name('subscription-payments.')->group(function () {
    Route::prefix('{microsite}')->group(function () {
        Route::get('/', [SubscriptionPaymentController::class, 'show'])->name('show');
        Route::post('{subscription}/payment', [SubscriptionPaymentController::class, 'store'])->name('store');
    });
    Route::get('/return/{customerSubscription:reference}', [SubscriptionPaymentController::class, 'return'])->name('return');
});

Route::prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/', [CustomerInvoiceController::class, 'index'])->name('index');
    Route::post('/send-link', [CustomerInvoiceController::class, 'sendLink'])->name('send-link');
    Route::get('/show', [CustomerInvoiceController::class, 'show'])->name('show');
});

Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
    Route::get('/', [CustomerSubscriptionController::class, 'index'])->name('index');
    Route::post('/send-link', [CustomerSubscriptionController::class, 'sendLink'])->name('send-link');
    Route::get('/{email}/{document_number}', [CustomerSubscriptionController::class, 'show'])->name('show');
});

Route::prefix('transactions')->name('transactions.')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('index');
    Route::get('/{payment}', [TransactionController::class, 'show'])->name('show');
});

require __DIR__ . '/auth.php';
