<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Tr_items;
use Carbon\Carbon;
use App\Models\Tr_tag_infos;
use DB;
use Log;
use App\Libraries\OrderUtility as OdrUtil;

class ItemController extends Controller
{
    public function searchItem(Request $request){

        $item_id = $request->input('item_id');
        $item_names = $request->input('item_name');
        $tag_id = $request->input('special_tag');
        $enabled_only = $request->input('enabled_only');
        $sort_id = $request->input('sort_id');
        // 初期表示の場合は更新日が新しい順を設定
        if($sort_id == null) $sort_id = OdrUtil::ORDER_ITEM_UPDATE_DESC['sortId'];
        $item_order = OdrUtil::ItemOrder[$sort_id];

        // 再利用するためパラメータを次のリクエストまで保存
        $request->flash();

        $itemList = array();

        // パラメータの入力状態によって動的にクエリを発行
        $query = Tr_items::query();
        // 案件IDが入力された場合はその他の検索条件を無視する
        if ($item_id != null) {
            $query->where('id', $item_id);

        } else {
            if ($item_names != null) {
                // 全角スペースを半角スペースに置換
                $item_names = mb_convert_kana($item_names, 's');
                // 文字列を半角スペースで分割
                $item_names = explode(' ', $item_names);
                foreach ($item_names as $item_name) {
                    $query->where('name', 'like', '%'.$item_name.'%');
                }
            }
            if ($tag_id != null) {
                $query->join('link_items_tags', 'items.id', '=', 'link_items_tags.item_id')
                      ->join('tags', 'tags.id', '=', 'link_items_tags.tag_id')
                      ->where('tags.id', '=', $tag_id)
                      ->select('items.*');
            }
            // 無効案件（掲載期間外または論理削除済み）を含めない場合
            if ($enabled_only) {
                // 今日日付を取得
                $today = Carbon::today();
                $query->where('delete_flag', '=', false)
                      ->where('service_start_date', '<=', $today)
                      ->where('service_end_date', '>=', $today);
            }
        }

        // laravel標準のページネーション処理
        $itemList = $query->orderBy($item_order['columnName'], $item_order['sort'])
                          ->paginate(30);

        // 特集タグ取得
        $featureTagInfos = Tr_tag_infos::where('tag_type', 3)
            ->orderBy('sort_order', 'asc')
            ->limit(30)
            ->get();

        // pickupタグ取得
        $pickupTagInfos = Tr_tag_infos::where('tag_type', 2)
            ->orderBy('sort_order', 'asc')
            ->limit(30)
            ->get();

        return view('admin.item_list', compact(
            'itemList',
            'pickupTagInfos',
            'featureTagInfos',
            'sort_id',
            'enabled_only'
        ));
    }
}
