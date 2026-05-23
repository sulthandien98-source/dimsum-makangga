<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| PUBLIC (MENU)
|--------------------------------------------------------------------------
*/
Route::get('/', [ProductController::class, 'index'])->name('menu');


/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

// Cart guest
Route::get('/cart', function () {
    return response()->json(session('cart', []));
});

// Cart login
Route::middleware('auth')->prefix('cart')->group(function () {
    Route::post('/add', [ProductController::class, 'addToCart'])->name('cart.add');
    Route::post('/update', [ProductController::class, 'updateCart'])->name('cart.update');
});


/*
|--------------------------------------------------------------------------
| USER AREA
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Checkout
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.process');

    // Payment
    Route::get('/payment', [OrderController::class, 'payment'])->name('payment');
    Route::post('/payment', [OrderController::class, 'store'])->name('payment.store');

    // User orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | ORDERS
        |--------------------------------------------------------------------------
        */
        Route::prefix('orders')->group(function () {

            Route::get('/', [OrderController::class, 'adminOrders'])->name('orders');
            Route::get('/{id}', [OrderController::class, 'show'])->name('orders.show');
            Route::put('/{id}', [OrderController::class, 'updateStatus'])->name('orders.update');
        });


        /*
        |--------------------------------------------------------------------------
        | PRODUCTS
        |--------------------------------------------------------------------------
        */
        Route::prefix('products')->group(function () {

            // List
            Route::get('/', [ProductController::class, 'adminIndex'])->name('products');

            // Create
            Route::post('/', [ProductController::class, 'store'])->name('products.store');

            // Update
            Route::put('/{id}', [ProductController::class, 'update'])->name('products.update');

            // 🔥 TAMBAH STOK
            Route::post('/{id}/stock', [ProductController::class, 'addStock'])
                ->name('products.addStock');

            // 🔥 TOGGLE ON/OFF (INI YANG KURANG TADI)
            Route::patch('/{id}/toggle', [ProductController::class, 'toggle'])
                ->name('products.toggle');

            // Delete
            Route::delete('/{id}', [ProductController::class, 'destroy'])
                ->name('products.delete');
        });

    });


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';