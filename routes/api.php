<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\UserController;
use App\Models\Image;
use App\Models\Post;
use App\Models\User;
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
Route::post('/login', [AuthController::class, 'newToken'])->name('new_token');


Route::group([
    'middleware' => 'auth:sanctum',
], function () {
    Route::post('/logout', [AuthController::class, 'revokeToken'])->name('revoke_token');
    Route::group([
        'prefix' => 'users',
        'as' => 'users.',
    ], function () {
        Route::get('/', [UserController::class, 'index'])
            ->middleware('can:viewAny,'.User::class)
            ->name('index');

        Route::post('/', [UserController::class, 'store'])
            ->middleware('can:create,'.User::class)
            ->name('create');

        Route::post('/{user}', [UserController::class, 'update'])
            ->middleware('can:update,user')
            ->name('update');

        Route::delete('/{user}', [UserController::class, 'destroy'])
            ->middleware('can:delete,user')
            ->name('delete');
    });


    Route::group([
        'prefix' => 'posts',
        'as' => 'posts.',
    ], function () {
        Route::get('/', [PostController::class, 'index'])
            ->middleware('can:viewAny,'.Post::class)
            ->name('index');

        Route::post('/', [PostController::class, 'store'])
            ->middleware('can:create,'.Post::class)
            ->name('create');

        Route::post('/{post}', [PostController::class, 'update'])
            ->middleware('can:update,post')
            ->name('update');

        Route::delete('/{post}', [PostController::class, 'destroy'])
            ->middleware('can:delete,post')
            ->name('delete');

        Route::delete('/{post}/force', [PostController::class, 'forceDelete'])
            ->middleware('can:forceDelete,post')
            ->name('force_delete');

        Route::post('/{post}/restore', [PostController::class, 'restore'])
            ->middleware('can:restore,post')
            ->name('restore');
    });


    Route::group([
        'prefix' => 'images',
        'as' => 'images.',
    ], function () {
        Route::get('/', [ImageController::class, 'index'])
            ->middleware('can:viewAny,'.Image::class)
            ->name('index');

        Route::post('/', [ImageController::class, 'store'])
            ->middleware('can:create,'.Image::class)
            ->name('create');

        Route::post('/{image}', [ImageController::class, 'update'])
            ->middleware('can:update,image')
            ->name('update');

        Route::delete('/{image}', [ImageController::class, 'destroy'])
            ->middleware('can:delete,image')
            ->name('delete');
    });
});
