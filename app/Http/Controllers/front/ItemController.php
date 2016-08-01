<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Libraries\OrderUtility as OdrUtil;
use App\Models\Tr_items;
use Carbon\Carbon;

class ItemController extends Controller
{
    public function searchItem(Request $request){

        $query = Tr_items::query();

        // 定義されたソート順の場合
        if (array_key_exists($request->order, OdrUtil::ItemOrder)) {
            $order = OdrUtil::ItemOrder[$request->order];
            $query->orderBy($order['columnName'], $order['sort']) ;
        }

        $itemList = $query->paginate(20);

        return view('front.item_list', compact('itemList'));
    }
}
