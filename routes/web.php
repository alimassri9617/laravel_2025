<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Reg;
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

