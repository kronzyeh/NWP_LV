<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Routes for authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes - Requires Authentication
Route::middleware('auth:api')->group(function () {
    // Profile and Logout
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Projects Routes
    Route::get('/projects', [ProjectController::class, 'index']);  // Get all projects
    Route::post('/projects', [ProjectController::class, 'store']);  // Create a new project
    Route::get('/projects/{id}', [ProjectController::class, 'show']);  // Get a specific project by ID
    Route::post('/projects/{project}/add-member', [ProjectController::class, 'addMember']);  // Add member to a project
});

// Fetch all users - No Authentication Required
Route::middleware('auth:api')->get('/users', [UserController::class, 'index']);
