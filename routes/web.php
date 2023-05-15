<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

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

Route::get('/', [UserController::class, 'landing'])->name('landing');
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/store', [ReportController::class, 'store'])->name('store');
Route::post('/auth', [ReportController::class, 'auth'])->name('auth');
Route::get('/data', [ReportController::class, 'data'])->name('data');

Route::middleware(['isLogin', 'CekRole:admin'])->group(function() {
    Route::get('/data', [ReportController::class, 'data'])->name('data');
    Route::delete('/destroy/{id}', [ReportController::class, 'destroy'])->name('destroy');
    Route::get('/export/pdf', [ReportController::class, 'exportPDF'])->name('export-pdf');
    Route::get('/export/pdf/{id}', [ReportController::class, 'printPDF'])->name('print-pdf');
    Route::get('/export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');
    });