<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    dirname(__DIR__)
);

$app->usePublicPath(dirname(base_path()));

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

 // project/.env থাকলে সেটা ব্যবহার করবে, না থাকলে markury .env (cPanel deploy fallback)
 $projectEnv = realpath(__DIR__.'/../');
 $markuryEnv = realpath(__DIR__.'/../vendor/markury/src/');
 if (!is_file($projectEnv.'/.env') && $markuryEnv !== false && is_file($markuryEnv.'/.env')) {
     $app->useEnvironmentPath($markuryEnv);
 }

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instancesd('contact');
| from the actual running of the application and sending responses.
|
*/

return $app;
