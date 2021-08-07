<?php

use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\CountriesController;
use \App\Http\Controllers\RatingsController;
use App\Http\Controllers\Admin\ProfilesController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';

Route::get('admin/categories', [CategoriesController::class, 'index'])
    ->name('categories.index')
    ->middleware(['auth', 'can:categories.view-any']);
Route::get('admin/categories/create', [CategoriesController::class, 'create'])
    ->name('categories.create')
    ->middleware(['auth', 'can:categories.create']);
Route::post('admin/categories', [CategoriesController::class, 'store'])
    ->name('categories.store');
Route::get('admin/categories/{category}', [CategoriesController::class, 'show'])
    ->name('categories.show');
Route::get('admin/categories/{id}/edit', [CategoriesController::class, 'edit'])
    ->name('categories.edit');
Route::put('admin/categories/{id}', [CategoriesController::class, 'update'])
    ->name('categories.update');
Route::delete('admin/categories/{id}', [CategoriesController::class, 'destroy'])
    ->name('categories.destroy');

/**
 * This route is an abbreviation of all the routes above & standard
 */
// Route::resource('admin/categories', CategoriesController::class);

Route::get('admin/products/trash', [ProductsController::class, 'trash'])->name('products.trash');
Route::put('admin/products/trash/{product?}', [ProductsController::class, 'restore'])
    ->name('products.restore')
    ->middleware(['auth', 'can:restore,product']);
Route::delete('admin/products/trash/{id?}', [ProductsController::class, 'forceDelete'])->name('products.force-delete');
Route::resource('admin/products', ProductsController::class)->middleware(['auth']);

Route::resource('admin/roles', RolesController::class)->middleware(['auth']);

//***********
Route::get('admin/get-user', [HomeController::class, 'getUser']);

Route::resource('admin/countries', CountriesController::class)->middleware(['auth']);

Route::post('ratings/{type}', [RatingsController::class, 'store'])
    ->where('type', 'profile|product');

Route::resource('admin/profiles', ProfilesController::class)->middleware(['auth']);
