<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    try {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    } catch (\Exception $e) {
        // Fallback if Inertia fails
        return response()->view('welcome-fallback', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Swagger UI
Route::get('/swagger', function () {
    return view('swagger');
})->name('swagger');

// Serve static Scribe docs (if using static type)
Route::get('/docs/{file?}', function ($file = 'index.html') {
    $path = public_path("docs/{$file}");
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $mimeTypes = [
        'html' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'yaml' => 'text/yaml',
        'yml' => 'text/yaml',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
    ];
    
    $mimeType = $mimeTypes[$extension] ?? 'text/plain';
    
    return response()->file($path, ['Content-Type' => $mimeType]);
})->where('file', '.*');

require __DIR__.'/auth.php';
