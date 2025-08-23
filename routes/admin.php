<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ItemsController;

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
});

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/', function () {
    return view('auth.login');
})->name('login');
