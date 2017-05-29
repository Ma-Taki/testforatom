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
    realpath(__DIR__.'/../')
);

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

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

//ログファイル出力カスタマイズ
$app -> configureMonologUsing(function ($monolog) {
  $filename = storage_path('logs/laravel-testlog.log');
  $handler = new Monolog\Handler\RotatingFileHandler($filename);
  $monolog -> pushHandler($handler);

// error_log('configureMonologUsingconfigureMonologUsingconfigureMonologUsing');
// error_log($filename);

  $filename = '/storage/logs/laravel-testlog-'.date("Y-m-d").'.log';

  if(file_exists($filename)){
    exec('chmod 777 '.$filename);
  }

});


return $app;
