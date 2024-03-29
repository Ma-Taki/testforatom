<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\FrontController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Libraries\OrderUtility as OdrUtil;
use App\Libraries\FrontUtility as FrntUtil;
use App\Libraries\ConsiderUtility as CnsUtil;
use App\Models\Tr_items;
use App\Models\Tr_tags;
use App\Models\Tr_search_categories;
use Carbon\Carbon;
use DB;
use App\Models\Ms_sys_types;
use App\Models\Ms_job_types;
use App\Models\Ms_skills;
use App\Models\Tr_column_connects;
use App\Models\Tr_link_column_connects;
use App\Libraries\CookieUtility as CkieUtil;

class ItemController extends FrontController
{
    /**
     * 案件検索
     * GET:/item/search
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
                            ->getItemBySysTypes($request->sys_types) // システム種別
                            ->getItemByRate($request->search_rate) // 報酬
                            ->getItemByBizCategories($request->biz_categories) // 業種
                            ->getItemByAreas($request->areas) // 勤務地
                            ->getItemByJobTypes($request->job_types) // ポジション
                            //->getItemByKeyword($request->search_keyword) // キーワード
                            ->groupBy('items.id')
                            ->orderBy($sortOrder['columnName'], $sortOrder['sort'])
                            ->paginate(FrntUtil::SEARCH_PAGINATE[$limit]);

        // パラメータを作成
        $params = [
            'order' => $sortOrder['sortId'],
            'page' => $page,
        ];
        $params = array_merge($params, $request->all());
        // mergeで上書きされるので後に入れる
        $params['limit'] = $limit;
        //ヒット案件数が0だった場合は掲載終了した案件をランダムに10件取得する
        $params['nodata'] = FrntUtil::getItemsByRandom($itemList,10);

        //wordpress固定ページ表示チェック(スキル検索)
        $columnConnects = array();
        if(!empty($request->skills)){
            foreach ($request->skills as $id) {
                $skillNames[] = Ms_skills::select('name')->where('id',$id)->get();
            }
            foreach ($skillNames as $skillName) {
                foreach ($skillName as $skill) {
                    $skillKeywords[] = $skill['name'];
                }
            }
            $columnConnects = $this->getColumn($skillKeywords);
        }

        return view('front.item_list', compact('itemList','params', 'columnConnects'));
    }

    /**
     * キーワード検索
     * GET:/item/keyword/{keyword}
     */
    public function searchItemByKeyword(Request $request){

        // 基本のパラメータはデフォルトを設定する
        $sortOrder = $this->getSortOrder($request->order);
        $limit = $this->getLimit($request->limit);
        $page = $this->getPage($request->page);

        $itemList = Tr_items::select('items.*')
                            ->entryPossible()
                            ->getItemByKeyword($request->keyword)
                            ->groupBy('items.id')
                            ->orderBy($sortOrder['columnName'], $sortOrder['sort'])
                            ->paginate(FrntUtil::SEARCH_PAGINATE[$limit]);
        $params = [
            'order' => $sortOrder['sortId'],
            'limit' => $limit,
            'page' => $page,
        ];
        $params = array_merge($params, $request->all());

        //ヒット案件数が0だった場合は掲載終了した案件をランダムに10件取得する
        $params['nodata'] = FrntUtil::getItemsByRandom($itemList,10);
        //検索ワードを$paramsに渡す
        $params['keyword'] = $request->keyword;

        //wordpress固定ページ表示チェック(キーワード検索)
        $columnConnects = array();
        if(!empty($request->keyword)){
            $keywords = explode(' ', $request->keyword);
            $keywords = array_filter($keywords, 'strlen');
            $columnConnects = $this->getColumn($keywords);
        }

        return view('front.item_list', compact('itemList','params', 'columnConnects'));
    }

    /**
     * 案件詳細画面を表示する
     * GET:/item/detail
     */
    public function showItemDetail(Request $request){

        // ▽▽▽ 161206 期限切れでも表示するように修正 ▽▽▽
        $item = Tr_items::where('id', $request->id)->first();

        if (empty($item) || $item->delete_flag) {
            abort(404, '指定された案件情報は存在しません。');
        }

        // エントリー可能かのフラグを立てる
        $today = Carbon::today();
        $canEntry = false;
        if ($today->between($item->service_start_date, $item->service_end_date)) {
            $canEntry = true;
        }
        // △△△ 161206 期限切れでも表示するように修正 △△△

        // おすすめ案件を取得する
        $sysTypes = Ms_sys_types::sysTypes($request->id);
        $jobTypes = Ms_job_types::jobTypes($request->id);
        $skills = Ms_skills::itemSkills($request->id);
        $recoItemList = Tr_items::select('items.*')
                                ->where('items.id', '!=', $request->id)
                                ->entryPossible()
                                ->getItemByJobTypes(FrntUtil::convertCollectionToIdList($jobTypes))
                                ->getItemBySkills(FrntUtil::convertCollectionToIdList($skills))
                                ->getItemBySysTypes(FrntUtil::convertCollectionToIdList($sysTypes))
                                ->groupBy('items.id')
                                ->orderBy(OdrUtil::ORDER_ITEM_RATE_DESC['columnName'],
                                          OdrUtil::ORDER_ITEM_RATE_DESC['sort'])
                                ->limit(5)
                                ->get();

        //この案件が検討中かどうか
        $isConsidering = CnsUtil::isConsidering($request->id);

        //wordpress固定ページ表示チェック(タグ検索)
        $columnConnects = array();
        $tagKeywords = array();
        if(!empty($item->tags)){
            foreach ($item->tags as $tag) {
                $tagKeywords[] = $tag->term;
            }
            $columnConnects = $this->getColumn($tagKeywords);
        }

        $cookieUser = CkieUtil::get(CkieUtil::COOKIE_NAME_USER_ID);
        $userFlag = 0;
        if(!empty($cookieUser)){
            $userFlag = 1;
        }

        return view('front.item_detail', compact('item', 'recoItemList', 'canEntry','isConsidering','columnConnects','userFlag'));
    }

    /**
     * 案件検索
     * GET:/item/tag/{id}
     */
    public function searchItemByTag(Request $request, $tag_id){

        // 基本のパラメータはデフォルトを設定する
        $sortOrder = $this->getSortOrder($request->order);
        $limit = $this->getLimit($request->limit);
        $page = $this->getPage($request->page);

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

        // ▽▽▽ 161206 案件一覧のタイトルタグを動的に設定 ▽▽▽
        $tag = Tr_tags::where('id', $tag_id)->first();
        $html_title = '';
        if (empty($tag)) {
            $html_title = '案件一覧';
        } else {
            $html_title = '【'.$tag->term .'】案件一覧';
        }

        //ヒット案件数が0だった場合は掲載終了した案件をランダムに10件取得する
        $params['nodata'] = FrntUtil::getItemsByRandom($itemList,10);

        //wordpress固定ページ表示チェック(タグ検索)
        $tagKeywords[] = $tag->term;
        $columnConnects = $this->getColumn($tagKeywords);

        return view('front.item_list', compact('itemList', 'params', 'html_title', 'columnConnects'));
        // △△△ 161206 案件一覧のタイトルタグを動的に設定 △△△
    }

    /**
     * 案件検索
     * GET:/item/category/{id}
     */
    public function searchItemByCategory(Request $request, $category_id){

        // 基本のパラメータはデフォルトを設定する
        $sortOrder = $this->getSortOrder($request->order);
        $limit = $this->getLimit($request->limit);
        $page = $this->getPage($request->page);

        // TODO: 親カテゴリーは必須で登録されてるから、もっと効率よくかける気がする。
        //       search_categoriesまで連結する必要ない
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

        // ▽▽▽ 161206 案件一覧のタイトルタグを動的に設定 ▽▽▽
        $category = Tr_search_categories::where('id', $category_id)->first();
        $html_title       = '';
        $html_description = '';
        $html_keywords    = '';
        if (empty($category)) {
            $html_title = '案件一覧';
        } else {
            $html_title       = $category->page_title;
            $html_description = $category->page_description;
            $html_keywords    = $category->page_keywords;
        }

        //ヒット案件数が0だった場合は掲載終了した案件をランダムに10件取得する
        $params['nodata'] = FrntUtil::getItemsByRandom($itemList,10);

        //wordpress固定ページ表示チェック(カテゴリー検索)
        $columnConnects = array();
        if(!empty($category_id)){
            $categoryKeywords = Tr_search_categories::select('name')->where('id',$category_id)->get();
            $columnConnects = $this->getColumn($categoryKeywords);
        }

        return view('front.item_list', compact('itemList', 'params', 'html_title','html_description','html_keywords', 'columnConnects'));
        // △△△ 161206 案件一覧のタイトルタグを動的に設定 △△△
    }

    /**
     * スマートフォンでのもっと見るボタン押下時の案件取得
     * GET:/item/search/readmore
     */
    public function ajaxReadMore(Request $request){

        // 基本のパラメータはデフォルトを設定する
        $sortOrder = $this->getSortOrder($request->order);
        $limit = $this->getLimit($request->limit);
        $page = $this->getPage($request->page);
        $path = $request->path;

        if (!empty($path) && $path == '/item/search') {
            // 一覧取得
            $itemList = Tr_items::select('items.*')
                                ->entryPossible() // 受付期間中の案件のみ
                                ->getItemBySkills($request->skills) // スキル
                                ->getItemBySysTypes($request->sys_types) // システム種別
                                ->getItemByRate($request->search_rate) // 報酬
                                ->getItemByBizCategories($request->biz_categories) // 業種
                                ->getItemByAreas($request->areas) // 勤務地
                                ->getItemByJobTypes($request->job_types) // ポジション
                                ->groupBy('items.id')
                                ->orderBy($sortOrder['columnName'], $sortOrder['sort'])
                                ->paginate(FrntUtil::SEARCH_PAGINATE[$limit]);

        // タグ検索
        } elseif (!empty($path) && strstr($path, '/item/tag/')) {
            // "/"で分割した最後の要素をtag_idとする
            $url = collect(explode('/', $path));
            $itemList = Tr_items::select('items.*')
                                ->join('link_items_tags', 'items.id', '=', 'link_items_tags.item_id')
                                ->join('tags', 'tags.id', '=', 'link_items_tags.tag_id')
                                ->entryPossible()
                                ->where('tags.id', '=', $url->last())
                                ->groupBy('items.id')
                                ->orderBy($sortOrder['columnName'], $sortOrder['sort'])
                                ->paginate(FrntUtil::SEARCH_PAGINATE[$limit]);

        // カテゴリー検索
        } elseif (!empty($path) && strstr($path, '/item/category/')) {
            // "/"で分割した最後の要素をcategory_idとする
            $url = collect(explode('/', $path));
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
        }

        // Model=>json変換前に、表示に必要なデータを取得しておく
        foreach ($itemList->items() as $item) {
            $biz_category_name = $item->bizCategorie->name;
            $sys_type = '';
            foreach ($item->sysTypes as $value) {
                $sys_type .= $value->name .'、' ;
            }
            $sys_type = rtrim($sys_type, '、');
            $job_type = '';
            foreach ($item->jobTypes as $value) {
                $job_type .= $value->name .'、' ;
            }
            $job_type = rtrim($job_type, '、');
            $item['biz_category_name'] = $biz_category_name;
            $item['sys_type'] = $sys_type;
            $item['job_type'] = $job_type;
            // 新着のフラグもここで生成
            if($item->registration_date->between(Carbon::now(), Carbon::now()->subDays(7))){
                $item['new_item_flg'] = true;
            } else {
                $item['new_item_flg'] = false;
            }
        }
        $data = [
            'items' => $itemList->items(),
            'hasMorePages' => $itemList->hasMorePages(),
        ];

        // エンコードして返却
        echo json_encode($data);
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

    /**
     * wordpress固定ページの接続IDを取得
     */
    private function getColumn($keywords){
        $connectIDs = Tr_link_column_connects::select('column_connects.connect_id')
                                        ->join('column_connects', 'column_connects.connect_id','=','link_column_connects.connect_id' )
                                        ->whereIn('link_column_connects.keyword', $keywords)
                                        ->where('column_connects.delete_flag', false)
                                        ->groupBy('id')
                                        ->get();
                  
        $columnConnects = array();  
        foreach ($connectIDs as $connectID) {
                $columnConnects[] = $connectID;
        }
        return $columnConnects;
    }
}
