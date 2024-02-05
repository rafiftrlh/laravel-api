<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// get
Route::get('/products/all', [PostController::class, 'index']);

//post
Route::post('/products/post', [PostController::class, 'store']);

// delete
Route::delete('/products/delete/{id}', [PostController::class, 'destroy']);

// update
Route::put('/products/update/{id}', [PostController::class, 'update']);