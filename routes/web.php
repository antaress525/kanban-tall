<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ConfirmEmailChangeController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::livewire('/', 'pages::home')->name('dashboard');

Route::prefix('/')->name('auth.')->middleware(['guest'])->group(function() {
    Route::livewire('login', 'pages::auth.⚡login')->name('login');
    Route::livewire('register', 'pages::auth.⚡register')->name('register');
});

# Board route
Route::prefix('board/')->name('board.')->middleware(['auth'])->group(function() { 
    Route::livewire('','pages::board.⚡index')->name('index');
    Route::livewire('{board}','pages::board.⚡show')->name('show')
        ->can('view', 'board');
});

# Setting route
Route::prefix('setting/')->name('setting.')->middleware(['auth'])->group(function() {
    Route::livewire('account','pages::setting.⚡account')->name('account');
    Route::livewire('password','pages::setting.⚡change-password')->name('password');
});

Route::get('/email-change/confirm/{token}', ConfirmEmailChangeController::class)
    ->name('email.change.confirm');

