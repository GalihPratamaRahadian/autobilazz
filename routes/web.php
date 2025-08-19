<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Auth\LoginController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::redirect('/', '/login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Auth::routes();

Route::prefix('admin')->middleware('isAdmin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'indexAdmin'])->name('admin.dashboard');

    Route::prefix('user')->group(function () {
        Route::get('/', [AdminController::class, 'userIndex'])->name('admin.user');
        Route::get('{user}/get', [AdminController::class, 'userGet'])->name('admin.user.get');
        Route::post('store', [AdminController::class, 'userStore'])->name('admin.user.store');
        Route::put('{user}/update', [AdminController::class, 'userUpdate'])->name('admin.user.update');
        Route::delete('{user}/destroy', [AdminController::class, 'userDestroy'])->name('admin.user.destroy');
    });

    Route::prefix('service')->group(function () {
         Route::get('/', [ServiceController::class, 'serviceIndex'])->name('admin.service');
        Route::get('{service}/get', [ServiceController::class, 'serviceGet'])->name('admin.service.get');
        Route::post('store', [ServiceController::class, 'serviceStore'])->name('admin.service.store');
        Route::put('{service}/update', [ServiceController::class, 'serviceUpdate'])->name('admin.service.update');
        Route::delete('{service}/destroy', [ServiceController::class, 'serviceDestroy'])->name('admin.service.destroy');
    });

    Route::prefix('order')->group(function (){
        Route::get('/', [OrderController::class, 'orderIndex'])->name('admin.order');
        Route::get('{order}/get', [OrderController::class, 'orderGet'])->name('admin.order.get');
        Route::put('{order}/update', [OrderController::class, 'orderUpdate'])->name('admin.order.update');
        Route::post('store', [OrderController::class, 'orderStore'])->name('admin.order.store');
        Route::delete('{order}/destroy', [OrderController::class, 'orderDestroy'])->name('admin.order.destroy');

    });

    Route::prefix('payment')->group(function () {
        Route::get('/', [PaymentController::class, 'paymentIndex'])->name('admin.payment');
        Route::get('{payment}/get', [PaymentController::class, 'paymentGet'])->name('admin.payment.get');
        Route::put('{payment}/update', [PaymentController::class, 'paymentUpdate'])->name('admin.payment.update');
        Route::post('store', [PaymentController::class, 'paymentStore'])->name('admin.payment.store');
        Route::get('/payment/check-plate', [PaymentController::class, 'checkPlate'])->name('admin.payment.check_plate');

        Route::prefix('print')->group(function () {
            Route::get('payment/{id}', [PaymentController::class, 'paymentPrint'])->name('admin.payment.print');
        });
    });

    Route::prefix('resume')->group(function () {
        Route::get('/', [ResumeController::class, 'resumeIndex'])->name('admin.resume');
        Route::post('generate', [ResumeController::class, 'generate'])->name('admin.resume.generate');
    });
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
