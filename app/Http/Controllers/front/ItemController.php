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

        // 基本のパラメータはデフォルトを設定する
        // ソート順
        $order = $request->input('order', 'RegistrationDesc');
        if (!array_key_exists($order, OdrUtil::ItemOrder)) {
            $order = 'RegistrationDesc';
        }
        $sortOrder = OdrUtil::ItemOrder[$order];

        // 1ページの表示件数
        $limit = $request->input('limit', 2);
        if (!array_key_exists($limit, FrntUtil::SEARCH_PAGINATE)) {
            $limit = 2;
        }

        // 現在のページ数
        $page = $request->input('page', 1);
        if (!is_int($page)) $page = 1;

        // 一覧取得
        $itemList = Tr_items::select('items.*')
                            // 受付期間中の案件のみ
                            ->entryPossible()
                            // スキル
                            ->when(empty(!$request->skills), function($query) use ($request) {
                                return $query->join('link_items_skills', 'items.id', '=', 'link_items_skills.item_id')
                                             ->join('skills', 'skills.id', '=', 'link_items_skills.skill_id')
                                             ->where(function($query) use ($request) {
                                    foreach ((array)$request->skills as $skill) {
                                        $query->orWhere('skills.id', $skill);
                                    }
                                });
                            })
                            // システム種別
                            ->when(empty(!$request->sys_types), function($query) use ($request) {
                                return $query->join('link_items_sys_types', 'items.id', '=', 'link_items_sys_types.item_id')
                                             ->join('sys_types', 'sys_types.id', '=', 'link_items_sys_types.sys_type_id')
                                             ->where(function($query) use ($request) {
                                    foreach ((array)$request->sys_types as $sys_type) {
                                        $query->orWhere('sys_types.id', $sys_type);
                                    }
                                });
                            })
                            // 報酬
                            ->when(array_key_exists($request->search_rate, FrntUtil::SEARCH_CONDITION_RATE),
                                function($query) use ($request) {
                                    return $query->where('items.max_rate', '>=', $request->search_rate);
                            })
                            // 業種
                            ->when(empty(!$request->biz_categories), function($query) use ($request) {
                                return $query->where(function($query) use ($request) {
                                    foreach ((array)$request->biz_categories as $biz_category) {
                                        $query->orWhere('items.biz_category_id', $biz_category);
                                    }
                                });
                            })
                            // 勤務地
                            ->when(empty(!$request->areas), function($query) use ($request) {
                                return $query->join('link_items_areas', 'items.id', '=', 'link_items_areas.item_id')
                                             ->join('areas', 'areas.id', '=', 'link_items_areas.areas_id')
                                             ->where(function($query) use ($request) {
                                    foreach ((array)$request->areas as $area) {
                                        $query->orWhere('area.id', $area);
                                    }
                                });
                            })
                            // ポジション
                            ->when(empty(!$request->job_types), function($query) use ($request) {
                                return $query->join('link_items_job_types', 'items.id', '=', 'link_items_job_types.item_id')
                                             ->join('job_types', 'job_types.id', '=', 'link_items_job_types.job_type_id')
                                             ->where(function($query) use ($request) {
                                    foreach ((array)$request->job_types as $job_type) {
                                        $query->orWhere('job_types.id', $job_type);
                                    }
                                });
                            })
                            ->groupBy('items.id')
                            ->orderBy($sortOrder['columnName'], $sortOrder['sort'])
                            ->paginate(FrntUtil::SEARCH_PAGINATE[$limit]);

        // 必須パラメータを作成
        $params = [
            'order' => $order,
            'limit' => $limit,
            'page' => $page,
        ];

        return view('front.item_list', compact('itemList','params'));
    }

    public function showItemDetail(Request $request){

        $item = Tr_items::find($request->id);
        return view('front.item_detail', compact('item'));
    }

    /**
     * 案件検索
     * GET:/front/tag/{id}
     */
    public function searchItemByTag(Request $request, $tag_id){

        // ソート順
        $order = $request->input('order', 'RegistrationDesc');
        if (!array_key_exists($order, OdrUtil::ItemOrder)) {
            $order = 'RegistrationDesc';
        }
        $sortOrder = OdrUtil::ItemOrder[$order];

        // 1ページの表示件数
        $limit = $request->input('limit', 2);
        if (!array_key_exists($limit, FrntUtil::SEARCH_PAGINATE)) {
            $limit = 2;
        }

        // 現在のページ数
        $page = $request->input('page', 1);
        if (!is_int($page)) $page = 1;

        $today = Carbon::today();
        $itemList = Tr_items::select('items.*')
                            ->join('link_items_tags', 'items.id', '=', 'link_items_tags.item_id')
                            ->join('tags', 'tags.id', '=', 'link_items_tags.tag_id')
                            ->entryPossible()
                            ->where('tags.id', '=', $tag_id)
                            ->groupBy('items.id')
                            ->orderBy($sortOrder['columnName'], $sortOrder['sort'])
                            ->paginate(FrntUtil::SEARCH_PAGINATE[$limit]);

        $params = [
            'order' => $order,
            'limit' => $limit,
            'page' => $page,
        ];

        return view('front.item_list', compact('itemList', 'params'));
    }

    /**
     * 案件検索
     * GET:/front/category/{id}
     */
    public function searchItemByCategory(Request $request, $category_id){

        // ソート順
        $order = $request->input('order', 'RegistrationDesc');
        if (!array_key_exists($order, OdrUtil::ItemOrder)) {
            $order = 'RegistrationDesc';
        }
        $sortOrder = OdrUtil::ItemOrder[$order];

        // 1ページの表示件数
        $limit = $request->input('limit', 2);
        if (!array_key_exists($limit, FrntUtil::SEARCH_PAGINATE)) {
            $limit = 2;
        }

        // 現在のページ数
        $page = $request->input('page', 1);
        if (!is_int($page)) $page = 1;

        $today = Carbon::today();
        $itemList = Tr_items::select('items.*')
                            ->join('link_items_search_categories', 'items.id', '=', 'link_items_search_categories.item_id')
                            ->join('search_categories', 'search_categories.id', '=', 'link_items_search_categories.search_category_id')
                            ->entryPossible()
                            ->where(function($query) use ($category_id) {
                                $query->orWhere('search_categories.id', '=', $category_id)
                                      ->orWhere('search_categories.parent_id', '=', $category_id);
                            })
                            ->groupBy('items.id')
                            ->orderBy($sortOrder['columnName'], $sortOrder['sort'])
                            ->paginate(FrntUtil::SEARCH_PAGINATE[$limit]);

        $params = [
            'order' => $order,
            'limit' => $limit,
            'page' => $page,
        ];

        return view('front.item_list', compact('itemList', 'params'));

    }
}
