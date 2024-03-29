<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\admin\ItemRegistRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController;
use App\Models\Tr_items;
use Carbon\Carbon;
use App\Models\Tr_tag_infos;
use DB;
use Log;
use App\Libraries\AdminUtility as AdmnUtil;
use App\Libraries\SessionUtility as ssnUtil;
use App\Libraries\OrderUtility as OdrUtil;
use App\Models\Ms_areas;
use App\Models\Tr_search_categories;
use App\Models\Ms_biz_categories;
use App\Models\Ms_job_types;
use App\Models\Ms_sys_types;
use App\Models\Ms_skills;
use App\Models\Tr_link_items_areas;
use App\Models\Tr_link_items_job_types;
use App\Models\Tr_link_items_search_categories;
use App\Models\Tr_link_items_skills;
use App\Models\Tr_link_items_sys_types;
use App\Models\Tr_link_items_tags;
use App\Models\Tr_tags;
use App\Models\Ms_skill_categories;
use App\Libraries\ModelUtility as mdlUtil;

class ItemController extends AdminController
{
    /**
     * 検索処理
     * GET,POST:/admin/item/search
     */
    public function searchItem(Request $request){

        // クエリ生成用データ
        $data_query = [
            'id'       => $request->id ?: '',
            'name'     => $request->name ?: '',
            'freeword' => $request->freeword ?: '',
            'tag_id'   => $request->tag_id ?: '',
            'enabled'  => $request->enabled ?: '',
            'sort_id'  => $request->sort_id ?: OdrUtil::ORDER_ITEM_UPDATE_DESC['sortId'],
        ];

        // クエリを動的に発行
        $query = Tr_items::select('items.*')
            ->when(!empty($data_query['id']), function ($query) use ($data_query) {
                return $query->id($data_query['id']);
            })
            ->when(empty($data_query['id']), function ($query) use ($data_query) {
                return $query->when(!empty($data_query['name']), function ($query) use ($data_query) {
                    $name_array = AdmnUtil::convertArrayToSearchStr($data_query['name']);
                    return $query->name($name_array);
                })->when(!empty($data_query['freeword']), function ($query) use ($data_query) {
                    $freeword_array = AdmnUtil::convertArrayToSearchStr($data_query['freeword']);
                    return $query->freeword($freeword_array);
                })->when(!empty($data_query['tag_id']), function ($query) use ($data_query) {
                    return $query->tagId($data_query['tag_id']);
                })->when(!empty($data_query['enabled']), function ($query) use ($data_query) {
                    return $query->entryPossible();
                });
            });

        // ソート順
        $item_order = OdrUtil::ItemOrder[$data_query['sort_id']];

        // laravel標準のページネーション
        $itemList = $query->groupBy('items.id')
                          ->orderBy($item_order['columnName'], $item_order['sort'])
                          ->paginate(30);

        //案件総数
        $allItems = Tr_items::where('delete_flag','=',0)->count();

        //募集中
        $today = Carbon::today();
        $wantedItems = Tr_items::where('delete_flag','=',0)
                        ->where('service_start_date', '<', $today)
                        ->where('service_end_date', '>' ,$today)->count();
                     
        return view('admin.item_list', compact('itemList', 'data_query','allItems','wantedItems'));
    }

    /**
     * 詳細画面表示
     * GET:/admin/item/detail
     */
    public function showItemDetail(Request $request){

        $item = Tr_items::where('id', $request->id)->get()->first();
        if (empty($item)) {
            abort(404, '指定された案件は存在しません。');
        }
        $today = Carbon::today();
        return view('admin.item_detail', compact('item', 'today'));
    }

    /**
     * 新規登録画面表示
     * GET:/admin/item/input
     */
    public function showItemInput(){

        // 各種マスタのMaster_Type:3(IndexOnly)以外を取得
        // エリア
        $master_areas = Ms_areas::where('master_type', '!=', 3)->get();
        // 親カテゴリ
        $master_search_categories_parent = Tr_search_categories::where('parent_id', null)->where('delete_flag', false)->orderBy('parent_sort', 'asc')->get();
        // 子カテゴリ
        $master_search_categories_child = Tr_search_categories::where('parent_id', '!=', null)->where('delete_flag', false)->orderBy('child_sort', 'asc')->get();
        // 業種
        $master_biz_categories = Ms_biz_categories::where('master_type', '!=', 3)->orderBy('sort_order', 'desc')->get();
        // 職種
        $master_job_types = Ms_job_types::where('delete_flag', false)
                                        ->orderBy('sort_order', 'asc')
                                        ->get();
        // システム種別
        $master_sys_types = Ms_sys_types::where('delete_flag', false)
                                        ->orderBy('sort_order', 'asc')
                                        ->get();
        //スキルカテゴリーを取得
        $master_skill_category = Ms_skill_categories::select('skill_categories.*')
                                                ->whereExists(function ($query) {
                                                    $query->select(DB::raw(1))
                                                          ->from('skills')
                                                          ->whereRaw('skills.skill_category_id = skill_categories.id')
                                                          ->where('skills.delete_flag',false);
                                                })
                                                ->where('skill_categories.delete_flag',false)
                                                ->orderBy('skill_categories.sort_order', 'asc')
                                                ->get();
        // スキル
        $master_skills = Ms_skills::where('delete_flag', false)
                                    ->orderBy('sort_order', 'asc')
                                    ->get();
        // 特集タグ取得
        $featureTagInfos = Tr_tag_infos::where('tag_type', 3)
                                        ->orderBy('sort_order', 'desc')
                                        ->limit(30)
                                        ->get();
        // pickupタグ取得
        $pickupTagInfos = Tr_tag_infos::where('tag_type', 2)
                                        ->orderBy('sort_order', 'asc')
                                        ->limit(30)
                                        ->get();

        return view('admin.item_input', compact(
            'master_areas',
            'master_search_categories_parent',
            'master_search_categories_child',
            'master_biz_categories',
            'master_job_types',
            'master_sys_types',
            'master_skill_category',
            'master_skills',
            'featureTagInfos',
            'pickupTagInfos'
        ));
    }

    /**
     * 新規登録処理
     * POST:/admin/item/input
     */
    public function insertItem(ItemRegistRequest $request){

        // 案件名
        $item_name = $request->input('item_name');
        // エントリー受付期間(開始日)
        $item_date_from = $request->input('item_date_from');
        // エントリー受付期間(終了日)
        $item_date_to = $request->input('item_date_to');
        // 報酬(検索用)
        $item_max_rate = $request->input('item_max_rate');
        // 報酬(表示用)
        $item_rate_detail = $request->input('item_rate_detail');
        // エリア
        $areas = $request->input('areas');
        // エリア詳細
        $item_area_detail = $request->input('item_area_detail');
        // 就業期間
        $item_employment_period = $request->input('item_employment_period');
        // 就業時間
        $item_working_hours = $request->input('item_working_hours');
        // カテゴリ
        $search_categories = $request->input('search_categories');
        // 業種
        $item_biz_category = $request->input('item_biz_category');
        // ポジション
        $job_types = $request->input('job_types');
        // システム種別
        $sys_types = $request->input('sys_types');
        // 要求スキル
        $skills = $request->input('skills');
        // タグ
        $item_tags = $request->input('item_tag');
        // 詳細
        $item_detail = $request->input('item_detail');
        // メモ(社内用)
        $item_note = $request->input('item_note');
        // 管理者ID(登録者保持のため)
        $item_admin_id = session(ssnUtil::SESSION_KEY_ADMIN_ID);

        // タグの編集
        // 改行コードを"\n"で統一する
        $item_tags = str_replace(array("\r\n", "\r"), '\n', $item_tags);
        // 全角スペースを半角に変換する
        $item_tags = str_replace('　', ' ', $item_tags);
        // 文字列前後の空白（改行コード含む）を削除
        $item_tags = trim($item_tags);
        // 配列に変換する
        $item_tagList = explode('\n',$item_tags);
        // すべての文字列要素の前後の空白を削除する
        $item_tagList = array_map('trim', $item_tagList);
        // 空要素を削除
        $item_tagList = array_filter($item_tagList, 'strlen');
        // indexを振り直す
        $item_tagList = array_values($item_tagList);

        // ▽▽▽ 追加のvalidation ▽▽▽
        $valiCheck = true;
        $custom_error_messages = array();
        // エントリー受付期間(終了日)がエントリー受付期間(開始日)より早い場合
        $carbon_date_from = Carbon::parse($item_date_from.' 00:00:00');
        $carbon_date_to = Carbon::parse($item_date_to.' 23:59:59');
        if ($carbon_date_to->lt($carbon_date_from)) {
            array_push($custom_error_messages, 'エントリー受付期間(終了日)がエントリー受付期間(開始日)より過去になっています。');
            $valiCheck =false;
        }
        // 報酬(検索用)に半角数字以外が含まれる、または0~9999以外の値をとる場合
        if (!preg_match("/^[0-9]+$/",$item_max_rate)
            || !((int)$item_max_rate >= 0 && (int)$item_max_rate <= 9999)) {
            array_push($custom_error_messages, '報酬(検索用)は整数値1~9999の間を入力してください。');
            $valiCheck =false;
        }
        // 20文字を超えるタグが存在する場合
        foreach ($item_tagList as $item_tag) {
            if (mb_strlen($item_tag) > 20) {
                array_push($custom_error_messages, '20文字を超えるタグは登録できません。');
                $valiCheck =false;
                break;
            }
        }
        // 総タグ数が80個を超える場合
        if (count($item_tagList) > 80) {
            array_push($custom_error_messages, '登録できるタグは80個までです。');
            $valiCheck =false;
        }
        if (!$valiCheck) {
            // フラッシュセッションにエラーメッセージを保存
            \Session::flash('custom_error_messages', $custom_error_messages);
            return back()->withInput();
        }
        // △△△ 追加のvalidation △△△

        // タグ名からタグIDを取得する
        $tag_idList = array();
        $tag_termList = array();
        foreach ($item_tagList as $item_tag) {
            $tag = Tr_tags::where('term', '=', $item_tag)->get()->first();
            if (empty($tag)) {
                // DBに存在しない場合はトランザクション内でタグテーブルにインサートを行う
                array_push($tag_termList, $item_tag);
            } else {
                array_push($tag_idList, $tag->id);
            }
        }

        // 選択されたカテゴリIDから、紐づく親カテゴリの一覧を取得する
        $parent_categories = Tr_search_categories::whereIn('id', $search_categories)
                                                 ->where('parent_id', '>=', 0)
                                                 ->select('parent_id')
                                                 ->distinct()
                                                 ->get();

        // 子カテゴリのみ選択されていた場合、対象の親カテゴリを追加する
        foreach ($parent_categories as $parent_category) {
            if (!in_array($parent_category->parent_id, $search_categories)) {
                array_push($search_categories, $parent_category->parent_id);
            }
        }

        // 現在時刻
        $timestamp = time();

        // トランザクション
        DB::transaction(function () use ($item_name,
                                         $item_biz_category,
                                         $item_date_from,
                                         $item_date_to,
                                         $timestamp,
                                         $item_employment_period,
                                         $item_working_hours,
                                         $item_max_rate,
                                         $item_rate_detail,
                                         $item_area_detail,
                                         $item_detail,
                                         $item_note,
                                         $areas,
                                         $job_types,
                                         $search_categories,
                                         $skills,
                                         $sys_types,
                                         $tag_idList,
                                         $tag_termList,
                                         $item_admin_id) {
            try {
                // 案件テーブルにインサート
                $item = Tr_items::create([
                    'name' => $item_name,
                    'biz_category_id' => $item_biz_category,
                    'service_start_date' => date('Y-m-d', strtotime($item_date_from)),
                    'service_end_date' => date('Y-m-d', strtotime($item_date_to)),
                    'registration_date' => date('Y-m-d H:i:s', $timestamp),
                    'last_update' => date('Y-m-d H:i:s', $timestamp),
                    'employment_period' => $item_employment_period,
                    'working_hours' => $item_working_hours,
                    'max_rate' => $item_max_rate * 10000,
                    'rate_detail' => $item_rate_detail,
                    'area_detail' => $item_area_detail,
                    'detail' => $item_detail,
                    'delete_flag' => false,
                    'delete_date' => null,
                    'note' => $item_note,
                    'version' => 0,
                    'admin_id' => $item_admin_id,
                ]);
                // 案件エリア中間テーブルにインサート
                foreach ((array)$areas as $area) {
                    Tr_link_items_areas::create([
                        'item_id' => $item->id,
                        'area_id' => $area,
                    ]);
                }
                // 案件職種中間テーブルにインサート
                foreach ((array)$job_types as $job_type) {
                    Tr_link_items_job_types::create([
                        'item_id' => $item->id,
                        'job_type_id' => $job_type,
                    ]);
                }
                // 案件業種検索中間テーブルにインサート
                foreach ((array)$search_categories as $search_category) {
                    Tr_link_items_search_categories::create([
                        'item_id' => $item->id,
                        'search_category_id' => $search_category,
                    ]);
                }
                // 案件スキル中間テーブルにインサート
                foreach ((array)$skills as $skill) {
                    Tr_link_items_skills::create([
                        'item_id' => $item->id,
                        'skill_id' => $skill,
                    ]);
                }
                // 案件システム種別中間テーブルにインサート
                foreach ((array)$sys_types as $sys_type) {
                    Tr_link_items_sys_types::create([
                        'item_id' => $item->id,
                        'sys_type_id' => $sys_type,
                    ]);
                }

                // タグテーブルにインサート
                foreach ((array)$tag_termList as $tag_term) {
                    $tag = Tr_tags::create([
                        'term' => $tag_term,
                    ]);
                    array_push($tag_idList, $tag->id);
                }

                // 案件タグ中間テーブルにインサート
                foreach ((array)$tag_idList as $tag_id) {
                    Tr_link_items_tags::create([
                        'item_id' => $item->id,
                        'tag_id' => $tag_id,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        return redirect('/admin/item/search')
            ->with('custom_info_messages','案件登録は正常に終了しました。');
    }

    /**
     * 編集画面表示
     * GET:/admin/item/modify
     */
    public function showItemModify(Request $request){

        // 案件ID
        $item_id = $request->input('id');
        $item = Tr_items::where('id', $item_id)->get()->first();
        if (empty($item)) {
            abort(404, '指定された案件は存在しません。');
        } elseif ($item->delete_flag || !empty($item->delete_date)) {
            abort(404, '指定された案件は既に削除されています。');
        }
        // max_rateを表示用に整形
        $item->max_rate = $item->max_rate / 10000;

        // 各種マスタのMaster_Type:3(IndexOnly)以外を取得
        // エリア
        $master_areas = Ms_areas::where('master_type', '!=', 3)->get();
        // 親カテゴリ
        $master_search_categories_parent = Tr_search_categories::where('parent_id', null)->where('delete_flag', false)->orderBy('parent_sort', 'asc')->get();
        // 子カテゴリ
        $master_search_categories_child = Tr_search_categories::where('parent_id', '!=', null)->where('delete_flag', false)->orderBy('child_sort', 'asc')->get();
        // 業種
        $master_biz_categories = Ms_biz_categories::where('master_type', '!=', 3)
                                                  ->orderBy('sort_order', 'desc')
                                                  ->get();
        // 職種
        $master_job_types = Ms_job_types::where('delete_flag', false)
                                        ->orderBy('sort_order', 'asc')
                                        ->get();
        // システム種別
        $master_sys_types = Ms_sys_types::where('delete_flag', false)
                                        ->orderBy('sort_order', 'asc')
                                        ->get();
        //スキルカテゴリーを取得
        $master_skill_category = Ms_skill_categories::select('skill_categories.*')
                                                ->whereExists(function ($query) {
                                                    $query->select(DB::raw(1))
                                                          ->from('skills')
                                                          ->whereRaw('skills.skill_category_id = skill_categories.id')
                                                          ->where('skills.delete_flag',false);
                                                })
                                                ->where('skill_categories.delete_flag',false)
                                                ->orderBy('skill_categories.sort_order', 'asc')
                                                ->get();
        // スキル
        $master_skills = Ms_skills::where('delete_flag', false)
                                ->orderBy('sort_order', 'asc')
                                ->get();
        // 特集タグ取得
        $featureTagInfos = Tr_tag_infos::where('tag_type', 3)
                                        ->orderBy('sort_order', 'desc')
                                        ->limit(30)
                                        ->get();
        // pickupタグ取得
        $pickupTagInfos = Tr_tag_infos::where('tag_type', 2)
                                        ->orderBy('sort_order', 'asc')
                                        ->limit(30)
                                        ->get();

        return view('admin.item_modify', compact(
            'item',
            'master_areas',
            'master_search_categories_parent',
            'master_search_categories_child',
            'master_biz_categories',
            'master_job_types',
            'master_sys_types',
            'master_skill_category',
            'master_skills',
            'featureTagInfos',
            'pickupTagInfos'
        ));
    }

    /**
     * 更新処理
     * POST:/admin/item/modify
     */
    public function updateItem(ItemRegistRequest $request){

        // 案件ID
        $item_id = $request->input('item_id');
        $item = Tr_items::where('id', $item_id)->get()->first();
        if (empty($item)) {
            abort(404, '指定された案件は存在しません。');
        } elseif ($item->delete_flag || !empty($item->delete_date)) {
            abort(404, '指定された案件は既に削除されています。');
        }

        // 案件名
        $item_name = $request->input('item_name');
        // エントリー受付期間(開始日)
        $item_date_from = $request->input('item_date_from');
        // エントリー受付期間(終了日)
        $item_date_to = $request->input('item_date_to');
        // 報酬(検索用)
        $item_max_rate = $request->input('item_max_rate');
        // 報酬(表示用)
        $item_rate_detail = $request->input('item_rate_detail');
        // エリア
        $areas = $request->input('areas');
        // エリア詳細
        $item_area_detail = $request->input('item_area_detail');
        // 就業期間
        $item_employment_period = $request->input('item_employment_period');
        // 就業時間
        $item_working_hours = $request->input('item_working_hours');
        // カテゴリ
        $search_categories = $request->input('search_categories');
        // 業種
        $item_biz_category = $request->input('item_biz_category');
        // ポジション
        $job_types = $request->input('job_types');
        // システム種別
        $sys_types = $request->input('sys_types');
        // 要求スキル
        $skills = $request->input('skills');
        // タグ
        $item_tags = $request->input('item_tag');
        // 詳細
        $item_detail = $request->input('item_detail');
        // メモ(社内用)
        $item_note = $request->input('item_note');

        // タグの編集
        // 改行コードを"\n"で統一する
        $item_tags = str_replace(array("\r\n", "\r"), '\n', $item_tags);
        // 全角スペースを半角に変換する
        $item_tags = str_replace('　', ' ', $item_tags);
        // 文字列前後の空白（改行コード含む）を削除
        $item_tags = trim($item_tags);
        // 配列に変換する
        $item_tagList = explode('\n',$item_tags);
        // すべての文字列要素の前後の空白を削除する
        $item_tagList = array_map('trim', $item_tagList);
        // 空要素を削除
        $item_tagList = array_filter($item_tagList, 'strlen');
        // indexを振り直す
        $item_tagList = array_values($item_tagList);

        // ▽▽▽ 追加のvalidation ▽▽▽
        $valiCheck = true;
        $custom_error_messages = array();
        // エントリー受付期間(終了日)がエントリー受付期間(開始日)より早い場合
        $carbon_date_from = Carbon::parse($item_date_from.' 00:00:00');
        $carbon_date_to = Carbon::parse($item_date_to.' 23:59:59');
        if ($carbon_date_to->lt($carbon_date_from)) {
            array_push($custom_error_messages, 'エントリー受付期間(終了日)がエントリー受付期間(開始日)より過去になっています。');
            $valiCheck =false;
        }
        // 報酬(検索用)に半角数字以外が含まれる、または0~9999以外の値をとる場合
        if (!preg_match("/^[0-9]+$/",$item_max_rate)
            || !((int)$item_max_rate >= 0 && (int)$item_max_rate <= 9999)) {
            array_push($custom_error_messages, '報酬(検索用)は整数値1~9999の間を入力してください。');
            $valiCheck =false;
        }
        // 20文字を超えるタグが存在する場合
        foreach ($item_tagList as $item_tag) {
            if (mb_strlen($item_tag) > 20) {
                array_push($custom_error_messages, '20文字を超えるタグは登録できません。');
                $valiCheck =false;
                break;
            }
        }
        // 総タグ数が80個を超える場合
        if (count($item_tagList) > 80) {
            array_push($custom_error_messages, '登録できるタグは80個までです。');
            $valiCheck =false;
        }
        if (!$valiCheck) {
            // フラッシュセッションにエラーメッセージを保存
            \Session::flash('custom_error_messages', $custom_error_messages);
            return back()->withInput();
        }
        // △△△ 追加のvalidation △△△

        // タグ名からタグIDを取得する
        $tag_idList = array();
        $tag_termList = array();
        foreach ($item_tagList as $item_tag) {
            $tag = Tr_tags::where('term', '=', $item_tag)->get()->first();
            if (empty($tag)) {
                // DBに存在しない場合はトランザクション内でタグテーブルにインサートを行う
                array_push($tag_termList, $item_tag);
            } else {
                array_push($tag_idList, $tag->id);
            }
        }


        // 選択されたカテゴリIDから、紐づく親カテゴリの一覧を取得する
        $parent_categories = Tr_search_categories::whereIn('id', $search_categories)
                                                 ->where('parent_id', '>=', 0)
                                                 ->select('parent_id')
                                                 ->distinct()
                                                 ->get();

        // 子カテゴリのみ選択されていた場合、対象の親カテゴリを追加する
        foreach ($parent_categories as $parent_category) {
            if (!in_array($parent_category->parent_id, $search_categories)) {
                array_push($search_categories, $parent_category->parent_id);
            }
        }

        // 現在時刻
        $timestamp = time();
        // トランザクション
        DB::transaction(function () use ($item_name,
                                         $item_biz_category,
                                         $item_date_from,
                                         $item_date_to,
                                         $timestamp,
                                         $item_employment_period,
                                         $item_working_hours,
                                         $item_max_rate,
                                         $item_rate_detail,
                                         $item_area_detail,
                                         $item_detail,
                                         $item_note,
                                         $item,
                                         $item_id,
                                         $areas,
                                         $job_types,
                                         $search_categories,
                                         $skills,
                                         $sys_types,
                                         $tag_idList,
                                         $tag_termList) {
            try {
                // 案件テーブルをアップデート
                Tr_items::where('id', $item_id)->update([
                    'name' => $item_name,
                    'biz_category_id' => $item_biz_category,
                    'service_start_date' => date('Y-m-d', strtotime($item_date_from)),
                    'service_end_date' => date('Y-m-d', strtotime($item_date_to)),
                    'registration_date' => $item->registration_date,
                    'last_update' => date('Y-m-d H:i:s', $timestamp),
                    'employment_period' => $item_employment_period,
                    'working_hours' => $item_working_hours,
                    'max_rate' => $item_max_rate * 10000,
                    'rate_detail' => $item_rate_detail,
                    'area_detail' => $item_area_detail,
                    'detail' => $item_detail,
                    'delete_flag' => $item->delete_flag,
                    'delete_date' => $item->delete_date,
                    'note' => $item_note,
                    'version' => $item->version,
                ]);
                // 案件エリア中間テーブルをデリートインサート
                Tr_link_items_areas::where('item_id', $item_id)->delete();
                foreach ((array)$areas as $area) {
                    Tr_link_items_areas::create([
                        'item_id' => $item->id,
                        'area_id' => $area,
                    ]);
                }
                // 案件職種中間テーブルにデリートインサート
                Tr_link_items_job_types::where('item_id', $item_id)->delete();
                foreach ((array)$job_types as $job_type) {
                    Tr_link_items_job_types::create([
                        'item_id' => $item->id,
                        'job_type_id' => $job_type,
                    ]);
                }
                // 案件業種検索中間テーブルにデリートインサート
                Tr_link_items_search_categories::where('item_id', $item_id)->delete();
                foreach ((array)$search_categories as $search_category) {
                    Tr_link_items_search_categories::create([
                        'item_id' => $item->id,
                        'search_category_id' => $search_category,
                    ]);
                }
                // 案件スキル中間テーブルにデリートインサート
                Tr_link_items_skills::where('item_id', $item_id)->delete();
                foreach ((array)$skills as $skill) {
                    Tr_link_items_skills::create([
                        'item_id' => $item->id,
                        'skill_id' => $skill,
                    ]);
                }
                // 案件システム種別中間テーブルにデリートインサート
                Tr_link_items_sys_types::where('item_id', $item_id)->delete();
                foreach ((array)$sys_types as $sys_type) {
                    Tr_link_items_sys_types::create([
                        'item_id' => $item->id,
                        'sys_type_id' => $sys_type,
                    ]);
                }
                // タグテーブルにインサート
                foreach ((array)$tag_termList as $tag_term) {
                    $tag = Tr_tags::create([
                        'term' => $tag_term,
                    ]);
                    array_push($tag_idList, $tag->id);
                }
                // 案件タグ中間テーブルにデリートインサート
                Tr_link_items_tags::where('item_id', $item_id)->delete();
                foreach ((array)$tag_idList as $tag_id) {
                    Tr_link_items_tags::create([
                        'item_id' => $item->id,
                        'tag_id' => $tag_id,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        return redirect('/admin/item/search')
            ->with('custom_info_messages','案件更新は正常に終了しました。');
    }

    /**
     * 論理削除処理
     * GET:/admin/item/delete
     */
    public function deleteItem(Request $request){

        // 案件ID
        $item_id = $request->input('id');
        $item = Tr_items::where('id', $item_id)->get()->first();
        if (empty($item)) {
            abort(404, '指定された案件は存在しません。');
        } elseif ($item->delete_flag || !empty($item->delete_date)) {
            abort(404, '指定された案件は既に削除されています。');
        }

        // トランザクション
        DB::transaction(function () use ($item_id) {
            try {
                Tr_items::where('id', $item_id)->update([
                    'delete_flag' => true,
                    'delete_date' => date('Y-m-d H:i:s', time()),
                ]);
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });
        return redirect('/admin/item/search')
            ->with('custom_info_messages','案件削除は正常に終了しました。');;
    }

}
