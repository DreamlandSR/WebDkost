->withMiddleware(function (Middleware $middleware) {

    // CORS
    $middleware->use([
        \Illuminate\Http\Middleware\HandleCors::class,
    ]);

    // Alias
    $middleware->alias([
        'auth' => Authenticate::class,
        'admin' => AdminMiddleware::class,
        'role' => RoleMiddleware::class,
        'permission' => PermissionMiddleware::class,
        'role_or_permission' => RoleOrPermissionMiddleware::class,
    ]);

    // Web middleware
    $middleware->web([
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        ShareErrorsFromSession::class,
        ValidateCsrfToken::class,
        SubstituteBindings::class,
    ]);

    // API middleware
    $middleware->api([
        'throttle:60,1',
        SubstituteBindings::class,
    ]);

    // CSRF exception (PENTING buat API & Midtrans)
    $middleware->validateCsrfTokens(except: [
        'api/*',
        'api/pembayaran/webhook',
    ]);
})