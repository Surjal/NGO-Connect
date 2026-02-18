<?php

use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Registration
Route::get('/ngo/register', [Auth\RegisterNgoController::class, 'showRegistrationForm'])->name('register.ngo');
Route::post('/ngo/register', [Auth\RegisterNgoController::class, 'register'])->name('register.ngo');

Route::get('/people/register', [Auth\RegisterPeopleController::class, 'showRegistrationForm'])->name('register.people');
Route::post('/people/register', [Auth\RegisterPeopleController::class, 'register']);

// Login/Logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
