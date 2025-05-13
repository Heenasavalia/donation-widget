<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonationController;

Route::get('/', function () {
    return view('welcome');
});




Route::get('/', [DonationController::class, 'showForm'])->name('donation.form');
Route::post('/donate', [DonationController::class, 'handleDonation'])->name('donation.handle');

Route::get('/donation/success/{id}', [DonationController::class, 'success'])->name('donation.success');
Route::get('/donation/cancel/{id}', [DonationController::class, 'cancel'])->name('donation.cancel');