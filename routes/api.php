<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\UserGroupsController;
use App\Http\Controllers\Api\GroupUsersController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\UserMessagesController;
use App\Http\Controllers\Api\GroupMessagesController;
use App\Http\Controllers\Api\MessageMessagesController;

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

Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [AuthController::class, 'register'])->name('api.register');

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    })
    ->name('api.user');

Route::name('api.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('permissions', PermissionController::class);

        Route::apiResource('users', UserController::class);

        // User Messages
        Route::get('/users/{user}/messages', [
            UserMessagesController::class,
            'index',
        ])->name('users.messages.index');
        Route::post('/users/{user}/messages', [
            UserMessagesController::class,
            'store',
        ])->name('users.messages.store');

        // User Messages2
        Route::get('/users/{user}/messages', [
            UserMessagesController::class,
            'index',
        ])->name('users.messages.index');
        Route::post('/users/{user}/messages', [
            UserMessagesController::class,
            'store',
        ])->name('users.messages.store');

        // User Groups
        Route::get('/users/{user}/groups', [
            UserGroupsController::class,
            'index',
        ])->name('users.groups.index');
        Route::post('/users/{user}/groups', [
            UserGroupsController::class,
            'store',
        ])->name('users.groups.store');

        Route::apiResource('groups', GroupController::class);

        // Group Users
        Route::get('/groups/{group}/users', [
            GroupUsersController::class,
            'index',
        ])->name('groups.users.index');
        Route::post('/groups/{group}/users', [
            GroupUsersController::class,
            'store',
        ])->name('groups.users.store');

        // Group Messages
        Route::get('/groups/{group}/messages', [
            GroupMessagesController::class,
            'index',
        ])->name('groups.messages.index');
        Route::post('/groups/{group}/messages', [
            GroupMessagesController::class,
            'store',
        ])->name('groups.messages.store');

        Route::apiResource('messages', MessageController::class);

        // Message Messages
        Route::get('/messages/{message}/messages', [
            MessageMessagesController::class,
            'index',
        ])->name('messages.messages.index');
        Route::post('/messages/{message}/messages', [
            MessageMessagesController::class,
            'store',
        ])->name('messages.messages.store');
    });
