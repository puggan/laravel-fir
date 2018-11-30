<?php

    namespace App\Http;

    use App\Http\Middleware\Authenticate;
    use App\Http\Middleware\CheckForMaintenanceMode;
    use App\Http\Middleware\EncryptCookies;
    use App\Http\Middleware\RedirectIfAuthenticated;
    use App\Http\Middleware\TrimStrings;
    use App\Http\Middleware\TrustProxies;
    use App\Http\Middleware\VerifyCsrfToken;
    use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
    use Illuminate\Auth\Middleware\Authorize;
    use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
    use Illuminate\Contracts\Foundation\Application;
    use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
    use Illuminate\Foundation\Http\Kernel as HttpKernel;
    use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
    use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
    use Illuminate\Http\Middleware\SetCacheHeaders;
    use Illuminate\Routing\Middleware\SubstituteBindings;
    use Illuminate\Routing\Middleware\ThrottleRequests;
    use Illuminate\Routing\Middleware\ValidateSignature;
    use Illuminate\Routing\Router;
    use Illuminate\Session\Middleware\AuthenticateSession;
    use Illuminate\Session\Middleware\StartSession;
    use Illuminate\View\Middleware\ShareErrorsFromSession;

    class Kernel extends HttpKernel
    {
        public function __construct(Application $app, Router $router)
        {
            /**
             * The application's global HTTP middleware stack.
             *
             * These middleware are run during every request to your application.
             *
             * @var array
             */
            $this->middleware = [
                CheckForMaintenanceMode::class,
                ValidatePostSize::class,
                TrimStrings::class,
                ConvertEmptyStringsToNull::class,
                TrustProxies::class,
            ];

            /**
             * The application's route middleware groups.
             *
             * @var array
             */
            $this->middlewareGroups = [
                'web' => [
                    EncryptCookies::class,
                    AddQueuedCookiesToResponse::class,
                    StartSession::class,
                    ShareErrorsFromSession::class,
                    VerifyCsrfToken::class,
                    SubstituteBindings::class,
                ],

                'api' => [
                    'throttle:200,1',
                    'bindings',
                ],
            ];

            /**
             * The application's route middleware.
             *
             * These middleware may be assigned to groups or used individually.
             *
             * @var array
             */
            $this->routeMiddleware = [
                'auth' => Authenticate::class,
                'auth.basic' => AuthenticateWithBasicAuth::class,
                'bindings' => SubstituteBindings::class,
                'cache.headers' => SetCacheHeaders::class,
                'can' => Authorize::class,
                'guest' => RedirectIfAuthenticated::class,
                'signed' => ValidateSignature::class,
                'throttle' => ThrottleRequests::class,
                'verified' => EnsureEmailIsVerified::class,
            ];

            /**
             * The priority-sorted list of middleware.
             *
             * This forces the listed middleware to always be in the given order.
             *
             * @var array
             */
            $this->middlewarePriority = [
                StartSession::class,
                ShareErrorsFromSession::class,
                Authenticate::class,
                AuthenticateSession::class,
                SubstituteBindings::class,
                Authorize::class,
            ];
            parent::__construct($app, $router);
        }
    }
