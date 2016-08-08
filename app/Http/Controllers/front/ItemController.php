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
        $sortOrder = $this->getSortOrder($request->order);
        $limit = $this->getLimit($request->limit);
        $page = $this->getPage($request->page);

        // 一覧取得
        $itemList = Tr_items::select('items.*')
                            ->entryPossible() // 受付期間中の案件のみ
                            ->getItemBySkills($request->skills) // スキル
                            ->getItemBySysTypes($request->sys_type) // システム種別
                            ->getItemByRate($request->search_rate) // 報酬
                            ->getItemByBizCategories($request->biz_categories) // 業種
                            ->getItemByAreas($request->areas) // 勤務地
                            ->getItemByJobTypes($request->job_types) // ポジション
                            ->groupBy('items.id')
                            ->orderBy($sortOrder['columnName'], $sortOrder['sort'])
                            ->paginate(FrntUtil::SEARCH_PAGINATE[$limit]);

        // パラメータを作成
        $params = [
            'order' => $sortOrder['sortId'],
            'limit' => $limit,
            'page' => $page,
        ];
        $params = array_merge($params, $request->all());

        return view('front.item_list', compact('itemList','params'));
    }

    /**
     * 案件詳細画面を表示する
     * GET:/front/detail
     */
    public function showItemDetail(Request $request){

        $item = Tr_items::find($request->id);


        //　おすすめ案件を取得する
        $recoItemList = Tr_items::select('items.*')
                                ->entryPossible()
                                ->getItemByJobTypes(FrntUtil::convertCollectionToIdList($item->jobTypes))
                                ->getItemBySkills(FrntUtil::convertCollectionToIdList($item->skills))
                                ->getItemBySysTypes(FrntUtil::convertCollectionToIdList($item->sysTypes))
                                ->groupBy('items.id')
                                ->orderBy(OdrUtil::ORDER_ITEM_RATE_DESC['columnName'],
                                          OdrUtil::ORDER_ITEM_RATE_DESC['sort'])
                                ->limit(5)
                                ->get();

        // TODO: 自分は弾くように設定

        return view('front.item_detail', compact('item', 'recoItemList'));
    }

    /**
     * 案件検索
     * GET:/front/tag/{id}
     */
    public function searchItemByTag(Request $request, $tag_id){

        // 基本のパラメータはデフォルトを設定する
        $sortOrder = $this->getSortOrder($request->order);
        $limit = $this->getLimit($request->limit);
        $page = $this->getPage($request->page);

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
            'order' => $sortOrder['sortId'],
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

        // 基本のパラメータはデフォルトを設定する
        $sortOrder = $this->getSortOrder($request->order);
        $limit = $this->getLimit($request->limit);
        $page = $this->getPage($request->page);

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
            'order' => $sortOrder['sortId'],
            'limit' => $limit,
            'page' => $page,
        ];

        return view('front.item_list', compact('itemList', 'params'));
    }

    /**
     * 案件のソート順を取得する。
     * デフォルトは登録日降順。
     */
    private function getSortOrder($order){
        if (empty($order) || !array_key_exists($order, OdrUtil::ItemOrder)) {
            $order = 'RegistrationDesc';
        }
        return OdrUtil::ItemOrder[$order];
    }

    /**
     * 1ページの表示件数を取得する。
     * デフォルトは2(20件)。
     */
    private function getLimit($limit){

        if (empty($limit) || !array_key_exists($limit, FrntUtil::SEARCH_PAGINATE)) {
            $limit = 2;
        }
        return $limit;
    }

    /**
     * 表示するページ番号を取得する。
     * デフォルトは1ページ目。
     */
    private function getPage($page){
        return is_int($page) ? $page : 1;
    }
}
