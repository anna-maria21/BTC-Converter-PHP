<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\UsersController;


Route::get('/', function () {
    return view('firstpage');
})->name('firstpage');

Route::get('/registration', function () {
    return view('create');
})->name('create');

Route::get('/btcRate/{email}', 'App\Http\Controllers\CourseController@rate')->name('home');

Route::post('/user/login', 'App\Http\Controllers\UsersController@login')->name('login');

Route::post('/user/create', 'App\Http\Controllers\UsersController@create')->name('registration');

Route::get('/user/login', 'App\Http\Controllers\UsersController@exit')->name('exit');
