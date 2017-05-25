<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\admin\MailMagazineController;
use App\Models\Tr_users;
use App\Models\Tr_mail_magazines;
use App\Models\Tr_link_users_mail_magazines;
use App\Models\Tr_mail_magazines_send_to;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function(){

          $controller = new MailMagazineController;
          $controller->sendMail($data_mail);
       })->->everyTenMinutes();

    }
}
