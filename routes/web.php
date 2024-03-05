<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ChainController;
use App\Http\Controllers\LeafletController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Models\Leaflet;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[StoreController::class, 'index']);

Route::post('/filter', [AjaxController::class, 'leafletAjax']);

Route::get('/gazetki-promocyjne-{slug},{leaflet_category_id}/', [LeafletController::class, 'index'])
    ->whereNumber('leaflet_category_id');

Route::get('/sieci-handlowe-{slug},{store_category_id}', [ChainController::class, 'index'])
    ->whereNumber('store_category_id');

Route::get('abc-zakupowicza',[BlogController::class, 'index']);

Route::get('abc-zakupowicza/{slug}/',[BlogController::class, 'showByCategory']);

Route::get('abc-zakupowicza/{category_slug}/{blog_slug}',[BlogController::class, 'show']);

Route::get('/{slug}/',[StoreController::class, 'indexLocalisation']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
