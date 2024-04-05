<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\Permission;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Check if token is right or not
Route::middleware('auth:sanctum')->group(function() {
    // get user
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::put('users/info', [AuthController::class, 'updateInfo']);
    Route::put('users/password', [AuthController::class, 'updatePassword']);

    // REST API Routes
    // Route::get('users', [UserController::class, 'index']);
    // Route::post('users', [UserController::class, 'store']);
    // Route::get('users/{id}', [UserController::class, 'show']);
    // Route::put('users/{id}', [UserController::class, 'update']);
    // Route::delete('users/{id}', [UserController::class, 'destroy']);
      // Route::get('orders', [OrderController::class, 'index']);
    // Route::get('orders/{id}', [OrderController::class, 'show']);

    // apiResources is equivalent to get, post, put, delete
    Route::apiResource('users', UserController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('products', ProductController::class);
    Route::post('upload', [ImageController::class, 'upload']);
    Route::apiResource('orders', OrderController::class)->only('index', 'show');
    Route::post('export', [OrderController::class, 'export']);
    Route::get('chart', [OrderController::class, 'chart']);
    Route::get('permissions', [Permission::class, 'index']);
});