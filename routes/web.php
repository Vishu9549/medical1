<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

// Customer Front-End Routes (Public)
Route::get('/', [HomeController::class, 'index']);
Route::get('/search', [HomeController::class, 'search']);
Route::get('/medicine/{id}', [HomeController::class, 'medicineDetails']);

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

// Cart Actions (Public)
Route::get('/smartcart', [CartController::class, 'index']);
Route::post('/cart/add', [CartController::class, 'add']);
Route::post('/cart/update', [CartController::class, 'update']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [HomeController::class, 'profile']);
    Route::get('/profile/orders', [HomeController::class, 'orders']);
    Route::get('/profile/addresses', [HomeController::class, 'addresses']);
    Route::get('/profile/favourites', [HomeController::class, 'favourites']);
    Route::get('/profile/notifications', [HomeController::class, 'notifications']);
    Route::get('/profile/settings', [HomeController::class, 'settings']);
    Route::get('/profile/help', [HomeController::class, 'help']);
    Route::get('/smartcart/results', [CartController::class, 'results']);
    Route::post('/order', [OrderController::class, 'store']);
    Route::get('/order/{id}/success', [OrderController::class, 'success']);
});

// Protected Shop Dashboard Routes (Requires Auth & Shop Owner Role)
Route::middleware(['auth', 'role.shop_owner'])->group(function () {
    Route::get('/shop/dashboard', [ShopController::class, 'dashboard']);
    Route::post('/shop/toggle-online', [ShopController::class, 'toggleOnline']);
    Route::post('/shop/toggle-delivery', [ShopController::class, 'toggleDelivery']);
    Route::get('/shop/quicksetup', [ShopController::class, 'quickSetupIndex']);
    Route::post('/shop/quicksetup', [ShopController::class, 'quickSetupSave']);
    Route::get('/shop/inventory', [ShopController::class, 'inventoryIndex']);
    Route::post('/shop/inventory/add', [ShopController::class, 'inventoryAdd']);
    Route::delete('/shop/inventory/delete/{id}', [ShopController::class, 'inventoryDelete']);
    Route::get('/shop/orders', [ShopController::class, 'ordersIndex']);
    Route::post('/shop/order/status', [ShopController::class, 'ordersUpdate']);
});

// Shop Registration is open to Auth users
Route::middleware('auth')->group(function () {
    Route::post('/shop/register', [ShopController::class, 'register']);
});

// Protected Admin Operations Dashboard Routes (Requires Auth & Admin Role)
Route::middleware(['auth', 'role.admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/admin/stores', [AdminController::class, 'stores']);
    Route::post('/admin/stores/status', [AdminController::class, 'storesStatus']);
    Route::get('/admin/approvals', [AdminController::class, 'approvals']);
    Route::get('/admin/medicines', [AdminController::class, 'medicines']);
    Route::post('/admin/medicines/add', [AdminController::class, 'medicinesAdd']);
    Route::delete('/admin/medicines/delete/{id}', [AdminController::class, 'medicinesDelete']);
    Route::get('/admin/commission', [AdminController::class, 'commission']);
    Route::post('/admin/commission', [AdminController::class, 'commissionUpdate']);
});
