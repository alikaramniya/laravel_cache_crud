<?php

use App\Http\Controllers\{
    TestController,
    UserController
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('test', [TestController::class, 'index'])->name('test');

Route::get('/', [UserController::class, 'index'])->name('home');

Route::group(
    [
        'as' => 'users.',
        'prefix' => 'users',
        'controller' => UserController::class
    ],
    function () {
        Route::delete('delete/{user}', 'delete')->name('delete');
        Route::patch('update/{user}', 'update')->name('update');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::get('restore/{id}', 'restore')->name('restore');
        Route::get('list-deleted-users', 'listSoftDeleted')->name('list.soft-deleted');
        Route::get('force-delete/{id}', 'forceDelete')->name('force.delete');
    }
);
