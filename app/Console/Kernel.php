<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendMailMagazine::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      $schedule->command('mailmagazine:send --force')
      ->everyTenMinutes()
      ->before(function () {
          // $filename = '/var/www/Engineer-Route/storage/logs/laravel-'.date("Y-m-d").'.log';
          // if(file_exists($filename)){
            //chmod('/var/www/Engineer-Route/storage/logs/laravel-2017-05-26.log', 0777);
            exec('chmod 777 /var/www/Engineer-Route/storage/logs/laravel-2017-05-26.log');
          // }
      });
    }
}
