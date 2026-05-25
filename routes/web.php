<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| PUBLIC MENU
|--------------------------------------------------------------------------
*/

Route::get('/', [ProductController::class, 'index'])
    ->name('menu');


/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

Route::get('/cart', function () {
    return response()->json(session('cart', []));
})->name('cart.get');

Route::middleware('auth')
    ->prefix('cart')
    ->name('cart.')
    ->group(function () {

        Route::post('/add', [ProductController::class, 'addToCart'])
            ->name('add');

        Route::post('/update', [ProductController::class, 'updateCart'])
            ->name('update');

        Route::post('/clear', [ProductController::class, 'clearCart'])
            ->name('clear');
    });


/*
|--------------------------------------------------------------------------
| USER AREA
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::prefix('profile')
        ->name('profile.')
        ->group(function () {

            Route::get('/', [ProfileController::class, 'edit'])
                ->name('edit');

            Route::patch('/', [ProfileController::class, 'update'])
                ->name('update');

            Route::delete('/', [ProfileController::class, 'destroy'])
                ->name('destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | CHECKOUT
    |--------------------------------------------------------------------------
    */

    Route::prefix('checkout')->group(function () {

        Route::get('/', [OrderController::class, 'checkout'])
            ->name('checkout');

        Route::post('/', [OrderController::class, 'processCheckout'])
            ->name('checkout.process');
    });


    /*
    |--------------------------------------------------------------------------
    | PAYMENT
    |--------------------------------------------------------------------------
    */

    Route::prefix('payment')->group(function () {

        Route::get('/', [OrderController::class, 'payment'])
            ->name('payment');

        Route::post('/auto', [OrderController::class, 'store'])
            ->name('payment.auto');

        Route::get('/proof/{order}', [OrderController::class, 'paymentProof'])
            ->name('payment.proof');

        Route::post('/proof/{order}/upload', [OrderController::class, 'uploadPaymentProof'])
            ->name('payment.proof.upload');
    });


    /*
    |--------------------------------------------------------------------------
    | WAITING PAGE
    |--------------------------------------------------------------------------
    */

    Route::get('/waiting/{order}', [OrderController::class, 'waiting'])
        ->name('order.waiting');


    /*
    |--------------------------------------------------------------------------
    | USER ORDERS
    |--------------------------------------------------------------------------
    */

    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders');
});


/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get('/', [AdminController::class, 'dashboard'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | REKAPITULASI
        |--------------------------------------------------------------------------
        */

        Route::prefix('rekapitulasi')
            ->name('rekapitulasi.')
            ->group(function () {

                Route::get('/', [AdminReportController::class, 'index'])
                    ->name('index');

                Route::get('/pdf', [AdminReportController::class, 'exportPdf'])
                    ->name('pdf');

                Route::get('/excel', [AdminReportController::class, 'exportExcel'])
                    ->name('excel');
            });


        /*
        |--------------------------------------------------------------------------
        | ORDERS
        |--------------------------------------------------------------------------
        */

        Route::prefix('orders')
            ->name('orders.')
            ->group(function () {

                Route::get('/', [OrderController::class, 'adminOrders'])
                    ->name('index');

                Route::get('/{id}', [OrderController::class, 'show'])
                    ->name('show');

                Route::put('/{id}/status', [OrderController::class, 'updateStatus'])
                    ->name('updateStatus');

                Route::post('/{id}/verify', [AdminOrderController::class, 'verify'])
                    ->name('verify');

                Route::post('/{id}/reject', [AdminOrderController::class, 'reject'])
                    ->name('reject');

                Route::post('/{id}/reject-order', [AdminOrderController::class, 'rejectOrder'])
                    ->name('rejectOrder');
            });


        /*
        |--------------------------------------------------------------------------
        | PRODUCTS
        |--------------------------------------------------------------------------
        */

        Route::prefix('products')
            ->name('products.')
            ->group(function () {

                Route::get('/', [ProductController::class, 'adminIndex'])
                    ->name('index');

                Route::post('/', [ProductController::class, 'store'])
                    ->name('store');

                Route::put('/{id}', [ProductController::class, 'update'])
                    ->name('update');

                Route::post('/{id}/stock', [ProductController::class, 'addStock'])
                    ->name('addStock');

                Route::post('/{id}/toggle', [ProductController::class, 'toggle'])
                    ->name('toggle');

                Route::delete('/{id}', [ProductController::class, 'destroy'])
                    ->name('delete');
            });
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';