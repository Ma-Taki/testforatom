<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tr_programming_lang_ranking;
use Carbon\Carbon;
use DOMDocument;

class getProgrammingLangRanking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getprogramminglangranking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

      echo "yes";

      //プログラミング言語、人気ランキングサイト解析
      $html = file_get_contents('https://www.tiobe.com/tiobe-index/');
      $domDocument = new \DOMDocument();
      libxml_use_internal_errors( true );
      $domDocument->loadHTML($html);
      libxml_clear_errors();
      $xmlString = $domDocument->saveXML();
      $xmlObject = simplexml_load_string($xmlString);
      $array = json_decode(json_encode($xmlObject), true);

      //トップ２０を配列に入れる
      $top20 = [];
      foreach($array['body']['section']['section']['section']['article']['table'][0]['tbody']['tr'] as $data){
        $top20[] = $data['td'][3];
      }

      //今月の日付（Ym）
      $month = Carbon::now()->format('Ym');

      //今月のランキングがすでに存在していたらUPDATE
      if(Tr_programming_lang_ranking::where('month', $month)->exists()){
        for($i=0; $i<count($top20); $i ++){
          Tr_programming_lang_ranking::where('month', $month)->where('language',$top20[$i])->update(['ranking' => $i+1 ]);
        }
      //存在していなかったらINSERT
      }else{
        for($i=0; $i<count($top20); $i ++){
          $rank = new Tr_programming_lang_ranking;
          $rank->month = $month;
          $rank->ranking = $i + 1;
          $rank->language = $top20[$i];
          $rank->save();
        }
      }

    }
}
