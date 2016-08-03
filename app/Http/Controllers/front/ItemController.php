<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Libraries\OrderUtility as OdrUtil;
use App\Libraries\FrontUtility as FrntUtil;
use App\Models\Tr_items;
use Carbon\Carbon;

class ItemController extends Controller
{
    /**
     * 案件検索
     * GET:/front/search
     */
    public function searchItem(Request $request){

        // 動的にクエリを発行
        $query = Tr_items::query();

        // ソート順
        $order = $request->input('order', 'RegistrationDesc');
        if (!array_key_exists($order, OdrUtil::ItemOrder)) {
            $order = 'RegistrationDesc';
        }
        $sortOrder = OdrUtil::ItemOrder[$order];
        $query->orderBy($sortOrder['columnName'], $sortOrder['sort']) ;

        // 1ページの表示件数
        $limit = $request->input('limit', 2);
        if (!array_key_exists($limit, FrntUtil::SEARCH_PAGINATE)) {
            $limit = 2;
        }

        // 現在のページ数
        $page = $request->input('page', 1);
        if (!is_int($page)) $page = 1;

        // 受付中かつ論理削除されていない
        /*
        $today = Carbon::today();
        $query->where('delete_flag', '=', false)
              ->where('service_start_date', '<=', $today)
              ->where('service_end_date', '>=', $today);
        */
        
        // 一覧取得
        $itemList = $query->paginate(FrntUtil::SEARCH_PAGINATE[$limit]);

        // 必須パラメータを作成
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

    public function searchItemByCategory(Request $request, $category_id){

        $order = $request->input('order', "RegistrationDesc");
        $limit = $request->input('limit', 20);
        $page = $request->input('page', 1);

        $itemList = Tr_items::join('link_items_search_categories', 'items.id', '=', 'link_items_search_categories.item_id')
                            ->join('search_categories', 'search_categories.id', '=', 'link_items_search_categories.search_category_id')
                            ->where('search_categories.id', '=', $category_id)
                            ->orWhere('search_categories.parent_id', '=', $category_id)
                            ->select('items.*')->paginate($limit);

        $params = [
            'order' => $order,
            'limit' => $limit,
            'page' => $page,
        ];

        return view('front.item_list', compact('itemList', 'params'));

    }







}
