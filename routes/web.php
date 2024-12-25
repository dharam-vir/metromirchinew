<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\LeadController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::middleware('auth')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        // Leads
        Route::get('/listing',[ListingController::class, "index"])->name('listing.index');
        Route::get('/leads/fresh',[LeadController::class, "fresh"])->name('leads.fresh');
        Route::get('/leads/complete',[LeadController::class, "complete"])->name('leads.complete');
        Route::get('/leads/action',[LeadController::class, "action"])->name('leads.action');
        // Users
        Route::get('/users/paid',[UserController::class, "paid"])->name('users.paid');
        Route::get('/users/expired',[UserController::class, "expired"])->name('users.expired');
        Route::get('/users/free',[UserController::class, "free"])->name('users.free');
        Route::get('/order',[OrderController::class, "index"])->name('order.index');
        Route::get('/order',[OrderController::class, "index"])->name('order.index');
        Route::get('/setting',[ProfileController::class, "setting"])->name('order.setting');
        Route::get('/change-password',[ProfileController::class, "changePassword"])->name('order.change-password');
    });
});


// require __DIR__.'/auth.php';
