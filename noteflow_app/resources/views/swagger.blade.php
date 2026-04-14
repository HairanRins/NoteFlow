<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NoteFlow API - Swagger UI</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.17.0/swagger-ui.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
            background: #fafafa;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        .swagger-ui .topbar {
            display: none;
        }
        .swagger-ui .scheme-container {
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            background: #fff;
            padding: 10px 20px;
        }
        #swagger-ui {
            max-width: 1440px;
            margin: 0 auto;
            padding: 20px;
        }
        .loading-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #49cc90;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .loading-text {
            margin-top: 20px;
            color: #3b4151;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div id="loading" class="loading-container">
        <div class="loading-spinner"></div>
        <div class="loading-text">Loading API Documentation...</div>
    </div>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@5.17.0/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.17.0/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = async () => {
            try {
                const ui = SwaggerUIBundle({
                    url: '/docs.openapi',
                    dom_id: '#swagger-ui',
                    deepLinking: true,
                    presets: [
                        SwaggerUIBundle.presets.apis,
                        SwaggerUIStandalonePreset
                    ],
                    plugins: [
                        SwaggerUIBundle.plugins.DownloadUrl
                    ],
                    layout: "StandaloneLayout",
                    docExpansion: 'list',
                    defaultModelsExpandDepth: 1,
                    onComplete: () => {
                        document.getElementById('loading').style.display = 'none';
                    },
                    requestSnippetsEnabled: true,
                    syntaxHighlight: {
                        activated: true,
                        theme: 'agate'
                    }
                });
                window.ui = ui;
            } catch (error) {
                console.error('Failed to load Swagger UI:', error);
                document.getElementById('loading').innerHTML = `
                    <div style="text-align: center; color: #f00;">
                        <h2>Failed to load API documentation</h2>
                        <p>Please try refreshing the page.</p>
                        <p style="font-size: 12px; color: #666; margin-top: 10px;">${error.message}</p>
                    </div>
                `;
            }
        };
    </script>
</body>
</html>
