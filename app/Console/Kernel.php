<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Commandsに登録したコマンドを設置する
     *
     * @var array
     */
    protected $commands = [
      Commands\SendMailMagazine::class,
      Commands\getProgrammingLangRanking::class,
    ];

    /**
     *スケジュール内容を書く
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      $schedule->command('mailmagazine:send') //メルマガ送信コマンドを呼び出し(Commands\SendMailMagazineに登録している)
      ->everyTenMinutes(); //１０分おきに実行
      // ->before(function () { //スケジューラ実行前にログのpermissionエラーを防ぐため777権限を与える
      //     $filename = '/var/www/Engineer-Route/storage/logs/laravel-'.date("Y-m-d").'.log';
      //     if(file_exists($filename)){
      //       exec('chmod 777 '.$filename);
      //     }
      // });

      //毎週月曜日に人気プログラミング言語ランキングT0P20解析
      // $schedule->command('getprogramminglangranking')->weekly()->mondays()->at('6:00');
      $schedule->command('getprogramminglangranking')->everyTenMinutes();









    }
}
