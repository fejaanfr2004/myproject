
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ActivityLogController;

// ========================================
// HOME PAGE
// ========================================

Route::get('/', function () {

    $products = \App\Models\Product::where('is_active', true)
        ->latest()
        ->take(3)
        ->get();

    $cart = session()->get('cart', []);
    $cartCount = array_sum(array_column($cart, 'quantity'));

    return view('home', compact('products', 'cartCount'));

})->name('home');


// ========================================
// CUSTOMER PRODUCTS
// ========================================

Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/product/{id}', [ProductController::class, 'show'])
    ->name('products.show');


// ========================================
// SHOPPING CART
// ========================================

Route::post('/cart/add', [ProductController::class, 'addToCart'])
    ->name('cart.add');

Route::get('/cart', [ProductController::class, 'cart'])
    ->name('cart.index');

Route::post('/cart/update', [ProductController::class, 'updateCart'])
    ->name('cart.update');

Route::post('/cart/remove', [ProductController::class, 'removeFromCart'])
    ->name('cart.remove');


// ========================================
// CUSTOMER CHECKOUT
// ========================================

Route::get('/checkout', [CheckoutController::class, 'show'])
    ->name('checkout.index');

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->name('checkout.store');


// ========================================
// LOGIN AND REGISTRATION
// ========================================

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register']);

});
// ========================================
// CUSTOMER NOTIFICATIONS
// ========================================

Route::middleware('auth')->group(function () {

    Route::get('/notifications', [\App\Http\Controllers\CustomerNotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/{id}/read', [\App\Http\Controllers\CustomerNotificationController::class, 'markAsRead'])
        ->name('notifications.read');

    Route::post('/notifications/read-all', [\App\Http\Controllers\CustomerNotificationController::class, 'markAllAsRead'])
        ->name('notifications.read-all');

});

// ========================================
// LOGOUT
// ========================================

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// ========================================
// ADMIN PANEL
// ========================================

Route::middleware(['auth', 'can:view-admin-dashboard'])
    ->prefix('admin')
    ->group(function () {
        // ADMIN NOTIFICATIONS
Route::get('/notifications', [NotificationController::class, 'index'])
    ->name('admin.notifications.index');

Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
    ->name('admin.notifications.read');

Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
    ->name('admin.notifications.read-all');

        // ========================================
        // ROLE MANAGEMENT
        // ========================================

        Route::middleware('can:manage-roles')->group(function () {

            Route::resource('roles', RoleController::class)
                ->except(['show'])
                ->names('admin.roles');

        });


        // ========================================
        // ADMIN DASHBOARD
        // ========================================

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');


        // ========================================
        // ADMIN REPORTS
        // ========================================

        Route::middleware('can:view-orders')->group(function () {

            Route::get('/reports', [ReportController::class, 'index'])
                ->name('admin.reports.index');

            Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])
                ->name('admin.reports.export.excel');

            Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])
                ->name('admin.reports.export.pdf');

        });


        // ========================================
        // CATEGORY MANAGEMENT
        // ========================================

        Route::middleware('can:manage-categories')->group(function () {

            Route::get('/categories', [CategoryController::class, 'index'])
                ->name('admin.categories.index');

            Route::get('/categories/create', [CategoryController::class, 'create'])
                ->name('admin.categories.create');

            Route::post('/categories', [CategoryController::class, 'store'])
                ->name('admin.categories.store');

            Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
                ->name('admin.categories.edit');

            Route::put('/categories/{category}', [CategoryController::class, 'update'])
                ->name('admin.categories.update');

            Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
                ->name('admin.categories.destroy');

        });


        // ========================================
        // ORDER MANAGEMENT
        // ========================================

        Route::middleware('can:view-orders')->group(function () {

            Route::get('/orders', [OrderController::class, 'index'])
                ->name('admin.orders.index');

            Route::get('/orders/{order}', [OrderController::class, 'show'])
                ->name('admin.orders.show');

        });

        Route::middleware('can:manage-orders')->group(function () {

            Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
                ->name('admin.orders.updateStatus');

        });


        // ========================================
        // PRODUCT MANAGEMENT
        // ========================================

        // VIEW PRODUCTS

        Route::middleware('can:view-products')->group(function () {

            Route::get('/products', [AdminProductController::class, 'index'])
                ->name('admin.products.index');

        });


        // CREATE PRODUCTS

        Route::middleware('can:create-products')->group(function () {

            Route::get('/products/create', [AdminProductController::class, 'create'])
                ->name('admin.products.create');

            Route::post('/products', [AdminProductController::class, 'store'])
                ->name('admin.products.store');

        });


        // EDIT PRODUCTS

        Route::middleware('can:edit-products')->group(function () {

            Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])
                ->name('admin.products.edit');

            Route::put('/products/{product}', [AdminProductController::class, 'update'])
                ->name('admin.products.update');

        });


        // DELETE PRODUCTS

        Route::middleware('can:delete-products')->group(function () {

            Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])
                ->name('admin.products.destroy');

        });


        // ========================================
        // USER MANAGEMENT
        // ========================================

        Route::middleware('can:manage-users')->group(function () {

            Route::get('/users', [UserController::class, 'index'])
                ->name('admin.users.index');

            Route::get('/users/create', [UserController::class, 'create'])
                ->name('admin.users.create');

            Route::post('/users', [UserController::class, 'store'])
                ->name('admin.users.store');

            Route::get('/users/{user}/edit', [UserController::class, 'edit'])
                ->name('admin.users.edit');

            Route::put('/users/{user}', [UserController::class, 'update'])
                ->name('admin.users.update');

            Route::delete('/users/{user}', [UserController::class, 'destroy'])
                ->name('admin.users.destroy');

        });


        // ========================================
        // ACTIVITY LOGS - SUPER ADMIN ONLY
        // ========================================

        Route::get('/activity-logs', [ActivityLogController::class, 'index'])
            ->middleware('role:Super Admin')
            ->name('admin.activity-logs.index');
Route::get('/activity-logs/export', [ActivityLogController::class, 'export'])
    ->middleware('role:Super Admin')
    ->name('admin.activity-logs.export');
   
   Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show'])
    ->middleware('role:Super Admin')
    ->name('admin.activity-logs.show'); });
      