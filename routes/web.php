<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::livewire('/', 'pages::home')->name('dashboard');

Route::prefix('/')->name('auth.')->middleware(['guest'])->group(function() {
    Route::livewire('login', 'pages::auth.login')->name('login');
});
