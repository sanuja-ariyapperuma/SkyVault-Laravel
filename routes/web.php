<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MetaDataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::prefix('customer')->group(function () {
        Route::post('/search', [CustomerController::class, 'search'])->name('customer.search');
        Route::get('/{customerid}', [CustomerController::class, 'show'])->name('customer.show');
        Route::get('/{customerid}/phone-modal', [CustomerController::class, 'phoneModal'])->name('customer.phone-modal');
        Route::post('/{customerid}/phones', [CustomerController::class, 'storePhone'])->name('customer.phones.store');
        Route::put('/{customerid}/phones/{phoneId}', [CustomerController::class, 'updatePhone'])->name('customer.phones.update');
        Route::delete('/{customerid}/phones/{phoneId}', [CustomerController::class, 'deletePhone'])->name('customer.phones.delete');
        Route::put('/{customerid}/phones/{phoneId}/default', [CustomerController::class, 'setDefaultPhone'])->name('customer.phones.set-default');
        Route::get('/{customerid}/email-modal', [CustomerController::class, 'emailModal'])->name('customer.email-modal');
        Route::post('/{customerid}/emails', [CustomerController::class, 'storeEmail'])->name('customer.emails.store');
        Route::put('/{customerid}/emails/{emailId}', [CustomerController::class, 'updateEmail'])->name('customer.emails.update');
        Route::delete('/{customerid}/emails/{emailId}', [CustomerController::class, 'deleteEmail'])->name('customer.emails.delete');
        Route::put('/{customerid}/emails/{emailId}/default', [CustomerController::class, 'setDefaultEmail'])->name('customer.emails.set-default');
        Route::get('/{customerid}/address-modal', [CustomerController::class, 'addressModal'])->name('customer.address-modal');
        Route::post('/{customerid}/addresses', [CustomerController::class, 'storeAddress'])->name('customer.addresses.store');
        Route::put('/{customerid}/addresses/{addressId}', [CustomerController::class, 'updateAddress'])->name('customer.addresses.update');
        Route::delete('/{customerid}/addresses/{addressId}', [CustomerController::class, 'deleteAddress'])->name('customer.addresses.delete');
        Route::get('/{customerid}/passport-modal', [CustomerController::class, 'passportModal'])->name('customer.passport-modal');
        Route::get('/{customerid}/visa-modal', [CustomerController::class, 'visaModal'])->name('customer.visa-modal');
        Route::get('/{customerid}/frequent-flyer-modal', [CustomerController::class, 'frequentFlyerModal'])->name('customer.frequent-flyer-modal');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
