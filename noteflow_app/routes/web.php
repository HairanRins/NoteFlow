<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    // Force fallback for now to avoid blank page
    return response()->view('welcome-fallback', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
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

// Fallback OpenAPI spec generator
Route::get('/docs.openapi.yaml', function () {
    $baseUrl = env('APP_URL', 'https://noteflow-orqw.onrender.com');
    
    $yaml = "openapi: 3.0.0
info:
  title: NoteFlow API
  version: 1.0.0
  description: API documentation for NoteFlow application
servers:
  - url: {$baseUrl}
paths:
  /api/health:
    get:
      summary: Health check endpoint
      responses:
        '200':
          description: OK
          content:
            application/json:
              schema:
                type: object
                properties:
                  status:
                    type: string
                  timestamp:
                    type: string
  /api/auth/login:
    post:
      summary: User login
      responses:
        '200':
          description: Login successful
        '401':
          description: Invalid credentials
  /api/auth/register:
    post:
      summary: User registration
      responses:
        '201':
          description: User created successfully
        '422':
          description: Validation error
  /api/user/profile:
    get:
      summary: Get user profile
      responses:
        '200':
          description: Profile data
        '401':
          description: Unauthorized";
    
    return response($yaml, 200, ['Content-Type' => 'text/yaml']);
});

Route::get('/docs/openapi.yaml', function () {
    return redirect('/docs.openapi.yaml');
});

// Debug route to check Scribe files
Route::get('/debug/docs', function () {
    $docsPath = public_path('docs');
    $files = [];
    
    if (is_dir($docsPath)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($docsPath));
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = str_replace($docsPath . '/', '', $file->getPathname());
                $files[] = [
                    'path' => $relativePath,
                    'size' => $file->getSize(),
                    'modified' => $file->getMTime(),
                    'exists' => true
                ];
            }
        }
    }
    
    return response()->json([
        'docs_path' => $docsPath,
        'exists' => is_dir($docsPath),
        'files' => $files,
        'public_path' => public_path(),
        'storage_docs' => storage_path('app/scribe')
    ]);
});

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
