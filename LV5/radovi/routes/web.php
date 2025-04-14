<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\RoleController;

Route::get('/', fn () => view('welcome'));

Route::middleware(['auth'])->group(function () {
    Route::resource('tasks', TaskController::class)->middleware('role:admin,nastavnik,student');
    Route::get('apply/{id}', [ApplicationController::class, 'apply'])->middleware('role:student');
    Route::get('my-applicants', [ApplicationController::class, 'myApplicants'])->middleware('role:nastavnik');
    Route::post('accept/{application}', [ApplicationController::class, 'accept'])->middleware('role:nastavnik');
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
});


require __DIR__.'/auth.php';
