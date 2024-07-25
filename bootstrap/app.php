<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\{
    Locale,
    RedirectIfAuthenticated,
    Authenticate
};

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        // api: __DIR__.'/../routes/api.php',
        // apiPrefix: 'api/v1',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo('/login');

        // $middleware->append(Locale::class);

        // $middleware->validateSignatures(except: [
        //     '/api/*',
        // ]);

        $middleware->validateCsrfTokens(except: [
            'livewire/*',
        ]);

        $middleware->alias([
            'auth'               => Authenticate::class,
            'guest'              => RedirectIfAuthenticated::class,
            'role'               => Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(fn (Request $request) => route('login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
    })->create();

//     /*
//      * Package Service Providers...
//      */

//      Milon\Barcode\BarcodeServiceProvider::class,
//      Maatwebsite\Excel\ExcelServiceProvider::class,
//      Mccarlosen\LaravelMpdf\LaravelMpdfServiceProvider::class,

//  ],

//  /*
//  |--------------------------------------------------------------------------
//  | Class Aliases
//  |--------------------------------------------------------------------------
//  |
//  | This array of class aliases will be registered when this application
//  | is started. However, feel free to register as many as you wish as
//  | the aliases are "lazy" loaded so they don't hinder performance.
//  |
//  */

//  'aliases' => Facade::defaultAliases()->merge([
//      'DNS1D' => Milon\Barcode\Facades\DNS1DFacade::class,
//      'DNS2D' => Milon\Barcode\Facades\DNS2DFacade::class,
//      'Cart' => Gloudemans\Shoppingcart\Facades\Cart::class,
//      'Excel' => Maatwebsite\Excel\Facades\Excel::class,
//      'PDF' => Mccarlosen\LaravelMpdf\Facades\LaravelMpdf::class
//  ])->toArray(),
