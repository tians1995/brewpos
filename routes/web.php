<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditController;
Route::get('/',fn()=>redirect(auth()->check()?(auth()->user()->role==='cashier'?'/pos':'/dashboard'):'/login'));
Route::middleware('guest')->group(function(){Route::get('/login',[AuthController::class,'create'])->name('login');Route::post('/login',[AuthController::class,'store']);});
Route::post('/logout',[AuthController::class,'destroy'])->middleware('auth');
Route::middleware(['auth'])->group(function(){
 Route::get('/dashboard',DashboardController::class)->name('dashboard')->middleware('role:admin');
 Route::get('/pos',[PosController::class,'index'])->middleware('role:cashier');
 Route::post('/pos/checkout',[PosController::class,'checkout'])->middleware('role:cashier');
 Route::get('/transactions',[TransactionController::class,'index'])->middleware('role:admin,cashier');
 Route::get('/transactions/{transaction}',[TransactionController::class,'show'])->middleware('role:admin,cashier');
 Route::middleware('role:admin')->group(function(){Route::get('/audit', [AuditController::class,'index'])->name('audit.index');Route::resource('products',ProductController::class)->only(['index','store','update','destroy']);Route::post('/categories',[CategoryController::class,'store']);Route::delete('/categories/{category}',[CategoryController::class,'destroy']);Route::resource('users',UserController::class)->only(['index','store','update','destroy']);Route::get('/reports/monthly',[TransactionController::class,'monthlyReport'])->name('reports.monthly');});
});
