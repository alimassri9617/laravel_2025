<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Reg;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DriverController;
use App\Models\Client;
use App\Models\Delivery;
use Illuminate\Support\Facades\Session;
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

Route::get("/createClient",function (){
    $client = Client::create([
        'fname' => 'John',
        'lname' => 'Doe',
        'email' => 'client@example.com',
        'phone' => '1234567890',
        'password' => 'password123' // In production, use Hash::make()
    ]);

    // Create some deliveries for the client
    Delivery::create([
        'client_id' => $client->id,
        'pickup_location' => '123 Main St, New York',
        'destination' => '456 Elm St, Boston',
        'package_type' => 'medium',
        'delivery_type' => 'standard',
        'delivery_date' => now()->addDays(3),
        'amount' => 15.00,
        'status' => 'pending'
    ]);

    Delivery::create([
        'client_id' => $client->id,
        'pickup_location' => '789 Oak St, Chicago',
        'destination' => '101 Pine St, Seattle',
        'package_type' => 'large',
        'delivery_type' => 'express',
        'delivery_date' => now()->addDays(1),
        'amount' => 30.00,
        'status' => 'in_progress'
    ]);
});
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

// Route::prefix('driver')->name('driver.')->group(function() {
//     // Authentication
//     Route::get('/login', [DriverController::class, 'showLoginForm'])->name('login');
//     Route::post('/login', [DriverController::class, 'login']);
//     Route::get('/register', [DriverController::class, 'showRegistrationForm'])->name('register');
//     Route::post('/register', [DriverController::class, 'register']);
//     Route::get('/logout', [DriverController::class, 'logout'])->name('logout');
    
//     // Protected routes
//     Route::middleware(function ($request, $next) {
//         if (!Session::has('driver_id')) {
//             return redirect()->route('driver.login');
//         }
//         return $next($request);
//     })->group(function() {
//         Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');
//         Route::get('/available-deliveries', [DriverController::class, 'availableDeliveries'])->name('available-deliveries');
//         Route::post('/accept-delivery/{id}', [DriverController::class, 'acceptDelivery'])->name('accept-delivery');
//         Route::post('/update-status/{id}', [DriverController::class, 'updateDeliveryStatus'])->name('update-status');
//         Route::get('/earnings', [DriverController::class, 'earnings'])->name('earnings');
//         Route::get('/profile', [DriverController::class, 'showProfile'])->name('profile');
//         Route::post('/profile', [DriverController::class, 'updateProfile'])->name('profile.update');
//     });
// });


Route::prefix('driver')->name('driver.')->group(function() {
    // Authentication
    Route::get('/login', [DriverController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [DriverController::class, 'login']);
    Route::get('/register', [DriverController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [DriverController::class, 'register'])->name("driver.register");
    Route::get('/logout', [DriverController::class, 'logout'])->name('logout');
    
    // Protected routes
    Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');
    Route::get('/available-deliveries', [DriverController::class, 'availableDeliveries'])->name('available-deliveries');
    Route::post('/accept-delivery/{id}', [DriverController::class, 'acceptDelivery'])->name('accept-delivery');
    Route::post('/update-status/{id}', [DriverController::class, 'updateDeliveryStatus'])->name('update-status');
    Route::get('/earnings', [DriverController::class, 'earnings'])->name('earnings');
    Route::get('/profile', [DriverController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [DriverController::class, 'updateProfile'])->name('profile.update');
});