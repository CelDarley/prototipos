<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/menus', [MenuController::class, 'index']);
Route::post('/menus', [MenuController::class, 'store']);
Route::get('/menus/{menu}', [MenuController::class, 'show']);
Route::put('/menus/{menu}', [MenuController::class, 'update']);
Route::delete('/menus/{menu}', [MenuController::class, 'destroy']); 