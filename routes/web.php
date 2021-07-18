<?php

use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ProductsController;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin/categories', [CategoriesController::class, 'index'])->name('categories.index');
Route::get('admin/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
Route::post('admin/categories', [CategoriesController::class, 'store'])->name('categories.store');
Route::get('admin/categories/{id}', [CategoriesController::class, 'show'])->name('categories.show');
Route::get('admin/categories/{id}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
Route::put('admin/categories/{id}', [CategoriesController::class, 'update'])->name('categories.update');
Route::delete('admin/categories/{id}', [CategoriesController::class, 'destroy'])->name('categories.destroy');

/**
 * This route is an abbreviation of all the routes above & standard
 */
// Route::resource('admin/categories', CategoriesController::class);

Route::resource('admin/products', ProductsController::class);