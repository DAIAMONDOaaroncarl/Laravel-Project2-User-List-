<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagement;

Route::get('/', [UserManagement::class, 'index']);
Route::get('/user-management', [UserManagement::class, 'add'])
    ->name('user-management.users.add');
Route::post('/user-management/users', [UserManagement::class, 'store'])
    ->name('user-management.users.store');
Route::get('/user-management/users', [UserManagement::class, 'index'])
    ->name('user-management.users.index');


// for CRUD operation

Route::get('/user-management/users/{user}/edit', [UserManagement::class, 'edit'])
    ->name('user-management.users.edit');

Route::put('/user-management/users/{user}', [UserManagement::class, 'update'])
    ->name('user-management.users.update');

Route::delete('/user-management/users/{user}', [UserManagement::class, 'destroy'])
    ->name('user-management.users.destroy');

Route::delete('/user-management/users', [UserManagement::class, 'destroySelected'])
    ->name('user-management.users.destroy-selected');
