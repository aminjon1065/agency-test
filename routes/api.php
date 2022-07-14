<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::post('/register', [\App\Http\Controllers\Api\Auth\AuthController::class, 'register'])->name('user.register');
Route::post('/login', [\App\Http\Controllers\Api\Auth\AuthController::class, 'login'])->name('user.login')->name('user.login');
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/me', [\App\Http\Controllers\Api\Auth\AuthController::class, 'me'])->name('user.me');
    Route::post('/logout', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logout'])->name('user.logout');
    Route::post('/upload', [\App\Http\Controllers\Api\Files\FilesController::class, 'upload'])->name('files.upload');
    Route::get('/files', [\App\Http\Controllers\Api\Files\FilesController::class, 'index'])->name('files.get');
    Route::post('/message', [\App\Http\Controllers\Api\Messages\MessagesController::class, 'newMessage'])->name('message.new');
    Route::get('/inbox', [\App\Http\Controllers\Api\Messages\MessagesController::class, 'inbox'])->name('message.inbox');
    Route::get('/email/verify/{id}', [\App\Http\Controllers\Api\Auth\EmailVerification::class, 'verify']);
//    Route::get('/email/resend', [\App\Http\Controllers\Api\Auth\EmailVerification::class, 'resend']);
});
