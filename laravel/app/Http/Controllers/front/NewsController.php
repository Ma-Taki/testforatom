<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\FrontController;
use App\Models\Tr_front_news;
use App\Libraries\FrontUtility as FrontUtil;
use Mail;
use Carbon\Carbon;

class NewsController extends FrontController
{
    /**
     * 内容画面表示
     * GET:/news/detail
     */
    public function showDetail(Request $request){
        $detail = Tr_front_news::where('id', $request->id)->get();
        return view('front.front_news_detail', compact('detail'));
    }
}
