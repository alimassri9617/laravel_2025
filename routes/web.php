<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Reg;
use App\Http\Controllers\ClientController;
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
    // Authentication routes would go here
    
    // Dashboard
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    
    // Deliveries
    Route::get('/deliveries', [ClientController::class, 'deliveries'])->name('deliveries');
    Route::get('/deliveries/{delivery}', [ClientController::class, 'showDelivery'])->name('deliveries.show');
    
    // New delivery
    Route::get('/deliveries/create', [ClientController::class, 'createDelivery'])->name('deliveries.create');
    Route::post('/deliveries', [ClientController::class, 'storeDelivery'])->name('deliveries.store');
    
    // Messages
    Route::get('/messages', [ClientController::class, 'messages'])->name('messages');
    Route::post('/deliveries/{delivery}/messages', [ClientController::class, 'sendMessage'])->name('messages.send');
    
    // Payments
    Route::get('/payments', [ClientController::class, 'payments'])->name('payments');
    
    // Settings
    Route::get('/settings', [ClientController::class, 'settings'])->name('settings');
    Route::put('/settings', [ClientController::class, 'updateSettings'])->name('settings.update');
});

