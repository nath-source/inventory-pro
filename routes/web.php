<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController; 
use App\Http\Controllers\CategoryController; 
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserProfileController; 
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// This is the default dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Group for Logged-in Users
Route::middleware('auth')->group(function () {
    
    // --- DELETE THESE 3 LINES (They conflict with your new profile) ---
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('categories', CategoryController::class); 
    Route::resource('products', ProductController::class);
    Route::resource('purchases', PurchaseController::class);
    
    // POS Routes
    Route::get('/pos', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    
    // Receipt Printing Route (I removed the duplicate wrong line here too)
    Route::get('/sales/{sale}/print', [SaleController::class, 'showReceipt'])->name('sales.print');
    
    // Reports Route
    Route::get('/reports', [SaleController::class, 'report'])->name('reports.index');
    Route::get('/reports/export/pdf', [SaleController::class, 'exportPdf'])->name('reports.pdf');

    // Profile Routes (These are the ones we want to use!)
    Route::get('/my-profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/my-profile', [UserProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');