<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\MobileController;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
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

Route::get('/public/buyer/index.php', function () {
    return view('index');
});

Route::prefix('products')->group(function () {
    Route::get('/all', [ProductsController::class, 'index_all']);
    Route::get('/category/{id}', [App\Http\Controllers\ProductsController::class, 'category_all']);
    Route::get('/product/{id}', [App\Http\Controllers\ProductsController::class, 'product']);
});

Route::prefix('categories')->group(function () {
    Route::get('/all', [CategoryController::class, 'all']);
});
Route::prefix('login')->group(function () {
    Route::get('/submit', [MobileController::class, 'authenticate']);
});
Route::prefix('register')->group(function () {
    Route::get('/submit', [MobileController::class, 'Registration']);
});
Auth::routes();
Route::get('/student', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
// Route::get('/{any}', [App\Http\Controllers\HomeController::class, 'index'])->where('any', '.*');
// Route::view('/{any}','dashboard')->where('any','^(?!js/).*');
Route::get('{path}', [App\Http\Controllers\HomeController::class, 'index'])->where('any', '^(?!js/).*')->where('path', '([A-z\d\-/_.]+)?');
