<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardController;
use App\Http\Controllers\BingoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/card_generator', [CardController::class, 'cardStore']);
Route::post('/number_caller', [BingoController::class, 'getCallingBingoNumber']);
Route::get('/validate_winner ', [CardController::class, 'validateWinnerCard']);
