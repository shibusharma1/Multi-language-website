<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');
