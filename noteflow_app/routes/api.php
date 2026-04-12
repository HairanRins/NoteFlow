<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API Routes for NoteFlow
Route::prefix('api')->group(function () {
    
    // Health check
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toISOString(),
            'version' => '1.0.0'
        ]);
    });
    
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::post('/register', function (Request $request) {
            // TODO: Implement user registration
            return response()->json(['message' => 'Registration endpoint']);
        });
        
        Route::post('/login', function (Request $request) {
            // TODO: Implement user login
            return response()->json(['message' => 'Login endpoint']);
        });
        
        Route::post('/logout', function (Request $request) {
            // TODO: Implement user logout
            return response()->json(['message' => 'Logout endpoint']);
        });
    });
    
    // Notes routes
    Route::prefix('notes')->group(function () {
        Route::get('/', function () {
            // TODO: Get user notes
            return response()->json(['message' => 'Get notes endpoint']);
        });
        
        Route::post('/', function (Request $request) {
            // TODO: Create new note
            return response()->json(['message' => 'Create note endpoint']);
        });
        
        Route::get('/{id}', function ($id) {
            // TODO: Get specific note
            return response()->json(['message' => "Get note $id endpoint"]);
        });
        
        Route::put('/{id}', function ($id, Request $request) {
            // TODO: Update note
            return response()->json(['message' => "Update note $id endpoint"]);
        });
        
        Route::delete('/{id}', function ($id) {
            // TODO: Delete note
            return response()->json(['message' => "Delete note $id endpoint"]);
        });
    });
    
    // User routes
    Route::prefix('user')->group(function () {
        Route::get('/profile', function () {
            // TODO: Get user profile
            return response()->json(['message' => 'Get profile endpoint']);
        });
        
        Route::put('/profile', function (Request $request) {
            // TODO: Update user profile
            return response()->json(['message' => 'Update profile endpoint']);
        });
        
        Route::post('/avatar', function (Request $request) {
            // TODO: Upload user avatar
            return response()->json(['message' => 'Upload avatar endpoint']);
        });
    });
});
