<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Reg;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DriverController;
use App\Models\Client;

Route::get('/', function () {
    return view('index');
})->name('home');



Route::get("/login",function(){
    return view("login");
})->name("login");

Route::get("/register",function(){
    return view("register");
})->name("register");

Route::post('/register', [Reg::class, 'storeUser'])->name('register.store');
Route::post('/register/driver', [Reg::class, 'storeDriver'])->name('register.storedriver');


Route::post('/login', [App\Http\Controllers\Login::class, 'login'])->name('login.store');


// Client routes
Route::prefix('client')->name('client.')->group(function() {
    // Authentication
    Route::get('/login', [ClientController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ClientController::class, 'login']);
    Route::get('/logout', [ClientController::class, 'logout'])->name('logout');
    
    // Protected routes
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/deliveries', [ClientController::class, 'deliveries'])->name('deliveries');
    Route::get('/deliveries/create', [ClientController::class, 'createDelivery'])->name('deliveries.create');
    Route::post('/deliveries', [ClientController::class, 'storeDelivery'])->name('deliveries.store');
    Route::get('/deliveries/{id}', [ClientController::class, 'showDelivery'])->name('deliveries.show');
});