<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ImageAdminController;
use App\Http\Controllers\Admin\PostsAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Middleware\HasAdminAccessMiddleware;
use App\Models\Image;
use App\Models\Post;
use App\Models\User;

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['web', HasAdminAccessMiddleware::class],
], function () {
    Route::get('/', [AdminController::class, 'handle'])->name('index');

    Route::group([
        'prefix' => 'posts',
        'as' => 'posts.',
    ], function () {
        Route::get('/', [PostsAdminController::class, 'index'])
            ->name('index')
            ->middleware('can:viewAny,'.Post::class);

        Route::get('/create', [PostsAdminController::class, 'create'])
            ->name('create')
            ->middleware('can:create,'.Post::class);

        Route::post('/', [PostsAdminController::class, 'store'])
            ->name('store')
            ->middleware('can:create,'.Post::class);


        Route::get('/{post}/edit', [PostsAdminController::class, 'edit'])
            ->name('edit')
            ->middleware('can:update,post');

        Route::post('/{post}', [PostsAdminController::class, 'update'])
            ->name('update')
            ->middleware('can:update,post');


        Route::delete('/{post_id}/force', [PostsAdminController::class, 'forceDelete'])
            ->where('post_id', '[0-9]+')
            ->name('force_delete');

        Route::post('/{post_id}/restore', [PostsAdminController::class, 'restore'])
            ->name('restore')
            ->where('post_id', '[0-9]+');

        Route::delete('/{post}', [PostsAdminController::class, 'destroy'])
            ->name('delete')
            ->middleware('can:delete,post');
    });


    Route::group([
        'prefix' => 'users',
        'as' => 'users.',
    ], function () {
        Route::get('/', [UserAdminController::class, 'index'])
            ->name('index')
            ->middleware('can:viewAny,'.User::class);

        Route::get('/create', [UserAdminController::class, 'create'])
            ->name('create')
            ->middleware('can:create,'.User::class);

        Route::post('/', [UserAdminController::class, 'store'])
            ->name('store')
            ->middleware('can:create,'.User::class);


        Route::get('/{user}/edit', [UserAdminController::class, 'edit'])
            ->name('edit')
            ->middleware('can:update,user');

        Route::post('/{user}', [UserAdminController::class, 'update'])
            ->name('update')
            ->middleware('can:update,user');

        Route::delete('/{user}', [UserAdminController::class, 'destroy'])
            ->name('delete')
            ->middleware('can:delete,user');
    });

    Route::group([
        'prefix' => 'images',
        'as' => 'images.',
    ], function () {
        Route::get('/', [ImageAdminController::class, 'index'])
            ->name('index')
            ->middleware('can:viewAny,'.Image::class);

        Route::get('/create', [ImageAdminController::class, 'create'])
            ->name('create')
            ->middleware('can:create,'.Image::class);

        Route::post('/', [ImageAdminController::class, 'store'])
            ->name('store')
            ->middleware('can:create,'.Image::class);


        Route::get('/{image}/edit', [ImageAdminController::class, 'edit'])
            ->name('edit')
            ->middleware('can:update,image');

        Route::post('/{image}', [ImageAdminController::class, 'update'])
            ->name('update')
            ->middleware('can:update,image');

        Route::delete('/{image}', [ImageAdminController::class, 'destroy'])
            ->name('delete')
            ->middleware('can:delete,image');
    });
});
