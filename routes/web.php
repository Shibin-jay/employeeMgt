<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployeeController;
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

Auth::routes();

// Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

// Root route
Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

// Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

// Employee CRUD
Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/employees-data', [EmployeeController::class, 'getEmployees'])->name('employees.data');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::post('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
// Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');

// CATCH-ALL ROUTE 
Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
