<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\AdminController;
use App\Models\Tr_admin_news;
use App\Libraries\AdminUtility as AdminUtil;
use DB;
use Carbon\Carbon;
use Log;
use Cache;

class TopController extends AdminController
{
	/**
     * 一覧画面の表示
     * GET:/admin/top
     */
    public function show(){
        $newsList = Tr_admin_news::where('delete_flag', false)->orderBy('release_date', 'DESC')->limit(5)->get();
     
// print_r($newsList);exit();

        
        
        return view('admin.top', compact('newsList'));
    }
}
?>
