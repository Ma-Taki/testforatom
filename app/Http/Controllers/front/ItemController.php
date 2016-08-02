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
        $order = $request->input('order', "RegistrationDesc");
        if (array_key_exists($request->order, OdrUtil::ItemOrder)) {
            $sortOrder = OdrUtil::ItemOrder[$request->order];
            $query->orderBy($sortOrder['columnName'], $sortOrder['sort']) ;
        }


        // TODO: $count int以外弾くようにする
        $limit = $request->input('limit', 20);
        $page = $request->input('page', 1);
        $itemList = $query->paginate($limit);

        $params = [
            'order' => $order,
            'limit' => $limit,
            'page' => $page,
        ];

        return view('front.item_list', compact('itemList','params'));
    }

    public function detailItem(Request $request){

        $item = Tr_items::find($request->id);
        return view('front.item_detail', compact('item'));
    }

    public function searchItemByTag(Request $request, $tag_id){

        $order = $request->input('order', "RegistrationDesc");
        $limit = $request->input('limit', 20);
        $page = $request->input('page', 1);

        $itemList = Tr_items::join('link_items_tags', 'items.id', '=', 'link_items_tags.item_id')
                            ->join('tags', 'tags.id', '=', 'link_items_tags.tag_id')
                            ->where('tags.id', '=', $tag_id)
                            ->select('items.*')->paginate($limit);

        $params = [
            'order' => $order,
            'limit' => $limit,
            'page' => $page,
        ];

        return view('front.item_list', compact('itemList', 'params'));
    }







}
