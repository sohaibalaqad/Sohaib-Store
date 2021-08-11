<?php

use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\CountriesController;
use \App\Http\Controllers\RatingsController;
use App\Http\Controllers\Admin\ProfilesController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckUserType;

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

Route::namespace('Admin')
    ->prefix('{lang}/admin')
    ->middleware(['auth', 'auth.type:admin,super-admin'])
    ->group(function (){
        Route::group([
            'prefix' => '/categories',
            'as' => 'categories.'
        ], function (){
            Route::get('/', [CategoriesController::class, 'index'])
                ->name('index')
                ->middleware(['can:categories.view-any']);
            Route::get('/create', [CategoriesController::class, 'create'])
                ->name('create')
                ->middleware(['can:categories.create']);
            Route::post('/', [CategoriesController::class, 'store'])
                ->name('store');
            Route::get('/{category}', [CategoriesController::class, 'show'])
                ->name('show');
            Route::get('/{id}/edit', [CategoriesController::class, 'edit'])
                ->name('edit');
            Route::put('/{id}', [CategoriesController::class, 'update'])
                ->name('update');
            Route::delete('/{id}', [CategoriesController::class, 'destroy'])
                ->name('destroy');
        });

        /**
         * This route is an abbreviation of all the routes above & standard
         */
//      Route::resource('/categories', CategoriesController::class);

        Route::get('/products/trash', [ProductsController::class, 'trash'])
            ->name('products.trash');
        Route::put('/products/trash/{product?}', [ProductsController::class, 'restore'])
            ->name('products.restore')
            ->middleware(['can:restore,product']);
        Route::delete('/products/trash/{id?}', [ProductsController::class, 'forceDelete'])
            ->name('products.force-delete');
        Route::resource('/products', ProductsController::class);

        Route::resource('/roles', RolesController::class);

        Route::get('/get-user', [HomeController::class, 'getUser']);

        Route::resource('/countries', CountriesController::class);

        Route::resource('/profiles', ProfilesController::class);
    });

Route::post('ratings/{type}', [RatingsController::class, 'store'])
    ->where('type', 'profile|product');


