<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

Route::get('/', [GameController::class, 'index']);
Route::post('/start', [GameController::class, 'start'])->name('start');
Route::post('/check/{game}', [GameController::class, 'check'])->name('check');
Route::get('/history', [GameController::class, 'history'])->name('history');