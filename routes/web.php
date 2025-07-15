<?php

use App\Http\Controllers\FrameController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/shoot', [GuestController::class, 'shoot'])->name('guest.shoot');

Route::middleware('auth')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('dashboard');

    // Package
    Route::get('/packages', [PackageController::class, 'index'])->name('package.index');
    Route::get('/packages/list', [PackageController::class, 'list'])->name('package.list');
    Route::get('/packages/{id}/show', [PackageController::class, 'show'])->name('package.show');
    Route::post('/packages/store', [PackageController::class, 'store'])->name('package.store');
    Route::post('/packages/{id}/update', [PackageController::class, 'update'])->name('package.update');
    Route::delete('/packages/{id}/destroy', [PackageController::class, 'destroy'])->name('package.destroy');

    // Frame
    Route::get('/frames', [FrameController::class, 'index'])->name('frame.index');
    Route::get('/frames/list', [FrameController::class, 'list'])->name('frame.list');
    Route::get('/frames/{id}/show', [FrameController::class, 'show'])->name('frame.show');
    Route::post('/frames/store', [FrameController::class, 'store'])->name('frame.store');
    Route::post('/frames/{id}/update', [FrameController::class, 'update'])->name('frame.update');
    Route::delete('/frames/{id}/destroy', [FrameController::class, 'destroy'])->name('frame.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
