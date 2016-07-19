<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Libraries\ModelUtility as mdlUtil;
use App\Libraries\FrontUtility as frntUtil;
use App\Http\Requests;
use App\Models\Tr_items;
use App\Models\Tr_tag_infos;
use App\Models\Ms_areas;
use App\Models\Tr_search_categories;
use App\Models\Ms_biz_categories;
use App\Models\Ms_job_types;
use App\Models\Ms_sys_types;
use App\Models\Ms_skills;
use App\Models\Ms_skill_categories;
use App\Models\Tr_link_items_areas;
use App\Models\Tr_link_items_job_types;
use App\Models\Tr_link_items_search_categories;
use App\Models\Tr_link_items_skills;
use App\Models\Tr_link_items_sys_types;
use App\Models\Tr_link_items_tags;
use App\Models\Tr_tags;
use App\Models\Tr_skills;
use Carbon\Carbon;

class FrontController extends Controller
{
    /**
     * トップ画面表示
     * GET:/
     */
    public function index() {

        // 今日日付
        $today = Carbon::today();

        // 新着案件を取得
        $newItemList = Tr_items::where('delete_flag', false)
                               ->where('delete_date', null)
                               ->where('service_start_date', '<=', $today)
                               ->where('service_end_date', '>=', $today)
                               ->orderBy('service_start_date', 'desc')
                               ->orderBy('registration_date', 'desc')
                               ->limit(4)
                               ->get();

        // ピックアップ案件を取得
        $pickUpTagList = Tr_tag_infos::where('tag_type', mdlUtil::TAG_TYPE_PICK_UP)->get();
        $pickUpItemList = array();
        foreach ($pickUpTagList as $pickUpTag) {
            $pickUpItemList =  Tr_items::join('link_items_tags', 'items.id', '=', 'link_items_tags.item_id')
                                   ->join('tags', 'tags.id', '=', 'link_items_tags.tag_id')
                                   ->where('items.delete_flag', false)
                                   ->where('items.delete_date', null)
                                   ->where('items.service_start_date', '<=', $today)
                                   ->where('items.service_end_date', '>=', $today)
                                   ->where('tags.term', $pickUpTag->tag->term)
                                   ->orderBy('service_start_date', 'desc')
                                   ->select('items.*')
                                   ->limit(4)
                                   ->get();
        }

        // スキルカテゴリーを取得
        $skill_categories = Ms_skill_categories::where('master_type', '!=', mdlUtil::MASTER_TYPE_INDEX_ONLY)
                                     ->orderBy('sort_order', 'asc')
                                     ->get();

        // システム種別を取得
        $sys_types = Ms_sys_types::where('master_type', '!=', mdlUtil::MASTER_TYPE_INDEX_ONLY)
                                 ->orderBy('sort_order', 'asc')
                                 ->get();

        // 報酬
        $seach_rateList = frntUtil::SEARCH_CONDITION_RATE;

        // 業種
        $biz_categories = Ms_biz_categories::where('master_type', '!=', mdlUtil::MASTER_TYPE_INDEX_ONLY)
                                           ->orderBy('sort_order', 'asc')
                                           ->get();

        // 勤務地
        $areas = Ms_areas::where('master_type', '!=', mdlUtil::MASTER_TYPE_INDEX_ONLY)
                         ->orderBy('sort_order', 'asc')
                         ->get();

        // ポジション
        $job_types = Ms_job_types::where('master_type', '!=', mdlUtil::MASTER_TYPE_INDEX_ONLY)
                                 ->orderBy('sort_order', 'asc')
                                 ->get();

        // 親カテゴリー
        $parentCategories = Tr_search_categories::where('parent_id', null)
                                                ->get();
        // 子カテゴリー
        $childCategories = Tr_search_categories::where('parent_id', '!=', null)
                                               ->get();

        $parentList = array();
        $childWorkList = array();
        $childList = array();
        foreach ($parentCategories as $parentCategory) {
            $parentList += [$parentCategory->id => $parentCategory->name];
            foreach ($childCategories as $childCategory) {
                if ($parentCategory->id == $childCategory->parent_id) {
                    array_push($childWorkList, $childCategory);
                }
            }
            $childList += [$parentCategory->id => $childWorkList];
            $childWorkList = array();
        }

        return view('front.top', compact('newItemList',
                                         'pickUpItemList',
                                         'skill_categories',
                                         'sys_types',
                                         'seach_rateList',
                                         'biz_categories',
                                         'areas',
                                         'job_types',
                                         'parentList',
                                         'childList'));
    }

}
