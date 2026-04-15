<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NoteFlow - API Documentation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Figtree', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
    <div class="relative min-h-screen flex items-center justify-center">
        <div class="w-full max-w-4xl px-6 py-12">
            <!-- Header -->
            <header class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    NoteFlow API
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300">
                    Comprehensive API documentation for our note-taking platform
                </p>
            </header>

            <!-- Main Content -->
            <main class="grid gap-6 md:grid-cols-2">
                <!-- API Documentation Card -->
                <a href="/docs" 
                   class="block p-8 bg-white rounded-lg shadow-lg border border-gray-200 hover:border-blue-500 transition-colors">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">API Documentation</h2>
                    </div>
                    <p class="text-gray-600 mb-4">
                        Explore our RESTful API endpoints with detailed examples, authentication methods, and interactive testing.
                    </p>
                    <div class="flex items-center text-blue-600 font-medium">
                        View Documentation
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>

                <!-- Swagger UI Card -->
                <a href="/swagger" 
                   class="block p-8 bg-white rounded-lg shadow-lg border border-gray-200 hover:border-green-500 transition-colors">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Swagger UI</h2>
                    </div>
                    <p class="text-gray-600 mb-4">
                        Interactive API explorer. Test endpoints directly from your browser with live examples and schemas.
                    </p>
                    <div class="flex items-center text-green-600 font-medium">
                        Open Swagger UI
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </main>

            <!-- Footer -->
            <footer class="text-center mt-12 text-sm text-gray-500">
                <p>NoteFlow API v1.0.0 | Laravel {{ $laravelVersion }} | PHP {{ $phpVersion }}</p>
                @if($canLogin || $canRegister)
                    <div class="mt-4 space-x-4">
                        @if($canLogin)
                            <a href="/login" class="text-blue-600 hover:text-blue-500">Login</a>
                        @endif
                        @if($canRegister)
                            <a href="/register" class="text-blue-600 hover:text-blue-500">Register</a>
                        @endif
                    </div>
                @endif
            </footer>
        </div>
    </div>
</body>
</html>
