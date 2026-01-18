<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::livewire('/', 'pages::home')->name('dashboard');

Route::prefix('/')->name('auth.')->middleware(['guest'])->group(function() {
    Route::livewire('login', 'pages::auth.⚡login')->name('login');
    Route::livewire('register', 'pages::auth.⚡register')->name('register');
});

Route::prefix('board/')->name('board.')->middleware(['auth'])->group(function() { 
    Route::livewire('','pages::board.⚡index')->name('index');
});
