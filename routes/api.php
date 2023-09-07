<?php

use Illuminate\Http\Request;
use App\Http\Controllers\{
    LoginController,
    UserController,
    ProductController,
};
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
Route::get('/login', function(){
   return response()->json([
    'message'=> 'Silahkan Login',
    'code'=>200,
   ],200);
})->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'user']);   
    Route::post('/user/create', [UserController::class, 'createuser']);
    Route::post('/user/edit/{id}', [UserController::class, 'edituser']);
    Route::get('/user/delete/{id}', [UserController::class, 'deleteuser']);
    Route::get('/user/restore/{id}', [UserController::class, 'restoreuser']);

    Route::get('/product', [ProductController::class, 'product']);
    Route::post('/product/create', [ProductController::class, 'createproduct']);
    Route::post('/product/edit/{id}', [ProductController::class, 'editproduct']);
    Route::get('/product/delete/{id}', [ProductController::class, 'deleteproduct']);
    Route::get('/product/restore/{id}', [ProductController::class, 'restoreproduct']);

});



