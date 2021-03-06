<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [GameController::class, 'home'])->name('home');

Route::get('/newgame', [GameController::class, 'createForm'])->name('createGameForm');
Route::post('/newgame', [GameController::class, 'create'])->name('createGame');

Route::get('/game/{id}', [GameController::class, 'game'])->name('game');
Route::get('/game/{id}/reset', [GameController::class, 'reset'])->name('resetGame');