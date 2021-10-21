<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'handle'])->name('home');
Route::get('/tags', [TagController::class, 'index'])->name('tag.index');
Route::get('/tags/{tag}', [TagController::class, 'show'])->name('tag.show');
Route::get('/article/{post}', [PostController::class, 'show'])->name('post.show');
