<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ItemsController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\ContainerController;
use App\Http\Controllers\Admin\RepresentativeController;
use App\Http\Controllers\Admin\RepresentativeDeliveryController;
use App\Http\Controllers\Admin\DirectSaleController;

Route::prefix('/admin')->middleware(['web', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/edit-profile', [AdminController::class, 'editProfile'])->name('admin.edit-profile');
    Route::patch('/update-profile', [AdminController::class, 'updateProfile'])->name('admin.update-profile');

    /** UserController */
    Route::resource('/users', UserController::class, ['names' => 'admin.users']);
    Route::get('/users-export', [UserController::class, 'export'])->name('admin.users.export');
    Route::post('/users-import', [UserController::class, 'import'])->name('admin.users.import');
    Route::get('/manage-users', [UserController::class, 'manage_users'])->name('admin.users.manage');
    Route::post('/manage-users-store', [UserController::class, 'manage_users_store'])->name('admin.users.manage.store');
    Route::get('/users/delete/{id}', [UserController::class, 'destroy'])->name('admin.users.delete');
    Route::get('/users-data-table', [UserController::class, 'index'])->name('admin.users.data-table');
    /** UserController */

    /** DepartmentController */
    Route::resource('/departments', DepartmentController::class, ['names' => 'admin.departments']);
    Route::get('/departments/delete/{id}', [DepartmentController::class, 'destroy'])->name('admin.departments.delete');
    Route::get('/departments-data-table', [DepartmentController::class, 'index'])->name('admin.departments.data-table');
    /** DepartmentController */

    /** SupplierController */
    Route::resource('/suppliers', SupplierController::class, ['names' => 'admin.suppliers']);
    Route::get('/suppliers/delete/{id}', [SupplierController::class, 'destroy'])->name('admin.suppliers.delete');
    Route::get('/suppliers-data-table', [SupplierController::class, 'index'])->name('admin.suppliers.data-table');
    /** SupplierController */

    /** CategoryController */
    Route::resource('/categories', CategoryController::class, ['names' => 'admin.categories']);
    Route::get('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.delete');
    Route::get('/categories/specific/{id}', [CategoryController::class, 'getSpecificCategory'])->name('admin.categories.specific');
    Route::get('/categories-data-table', [CategoryController::class, 'index'])->name('admin.categories.data-table');
    /** CategoryController */

    /** ItemController */
    Route::resource('/items', ItemsController::class, ['names' => 'admin.items']);
    Route::post('/items-import', [ItemsController::class, 'import'])->name('admin.items.import');
    Route::get('/items/delete/{id}', [ItemsController::class, 'destroy'])->name('admin.items.delete');
    Route::get('/items-data-table', [ItemsController::class, 'index'])->name('admin.items.data-table');
    /** ItemController */

    /** UnitController */
    Route::resource('/units', UnitController::class, ['names' => 'admin.units']);
    Route::get('/units/delete/{id}', [UnitController::class, 'destroy'])->name('admin.units.delete');
    Route::get('/units-data-table', [UnitController::class, 'index'])->name('admin.units.data-table');
    /** UnitController */

    /** ContainerController */
    Route::resource('/containers', ContainerController::class, ['names' => 'admin.containers']);
    Route::get('/containers/delete/{id}', [ContainerController::class, 'destroy'])->name('admin.containers.delete');
    Route::get('/containers-data-table', [ContainerController::class, 'index'])->name('admin.containers.data-table');
    Route::get('/containers/confirm/{id}', [ContainerController::class, 'confirmContainer'])->name('admin.containers.confirm');
    /** ContainerController */

    /** RepresentativeController */
    Route::resource('/representatives', RepresentativeController::class, ['names' => 'admin.representatives']);
    Route::get('/representatives/delete/{id}', [RepresentativeController::class, 'destroy'])->name('admin.representatives.delete');
    Route::get('/representatives-data-table', [RepresentativeController::class, 'index'])->name('admin.representatives.data-table');
    /** RepresentativeController */

    /** RepresentativeDeliveryController */
    Route::get('/delivery', [RepresentativeDeliveryController::class, 'index'])->name('admin.delivery.index');
    Route::get('/save_delivery/{id?}', [RepresentativeDeliveryController::class, 'saveRepresentativeDelivery'])->name('admin.delivery.save_view');
    Route::post('/save_delivery/{id?}', [RepresentativeDeliveryController::class, 'saveRepresentativeDelivery'])->name('admin.delivery.save');
    Route::get('/delivery/delete/{id}', [RepresentativeDeliveryController::class, 'destroy'])->name('admin.delivery.delete');
    Route::get('/status_delivery/{id}', [RepresentativeDeliveryController::class, 'changeStatusAndQty'])->name('admin.delivery.status');
    Route::get('representative_deliveries/print/{id}', [RepresentativeDeliveryController::class, 'printDelivery'])->name('admin.delivery.print');
    /** RepresentativeDeliveryController */

    /** DirectSaleController */
    Route::prefix('admin')->group(function () {
        Route::get('/direct-sales', [DirectSaleController::class, 'index'])->name('admin.direct_sales.index');
        Route::get('/direct-sales/create', [DirectSaleController::class, 'create'])->name('admin.direct_sales.create');
        Route::post('/direct-sales/create', [DirectSaleController::class, 'create'])->name('admin.direct_sales.store');
        Route::get('/direct-sales/edit/{id}', [DirectSaleController::class, 'edit'])->name('admin.direct_sales.edit');
        Route::post('/direct-sales/edit/{id}', [DirectSaleController::class, 'edit'])->name('admin.direct_sales.update');
        Route::delete('/direct-sales/delete/{id}', [DirectSaleController::class, 'destroy'])->name('admin.direct_sales.delete');
        Route::get('/direct-sales/confirm/{id}', [DirectSaleController::class, 'confirmSale'])->name('admin.direct_sales.confirm');
        Route::get('/direct-sales/print/{id}', [DirectSaleController::class, 'printInvoice'])->name('admin.direct_sales.print');
    });
    /** DirectSaleController */
});

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/', function () {
    return view('auth.login');
})->name('login');
