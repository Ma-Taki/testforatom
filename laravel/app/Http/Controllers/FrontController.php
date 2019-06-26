<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Tr_search_categories;
use App\Models\Ms_skills;
use App\Models\Ms_skill_categories;
use App\Models\Tr_search_categories_display;
use App\Models\Tr_front_news;
use Illuminate\Http\Request;
use App\Libraries\{ModelUtility as mdlUtil, FrontUtility as frntUtil};
use App\Http\Requests;
use App\Models\{Tr_items, Tr_tag_infos, Tr_users};
use DB;

class FrontController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * トップ画面表示
     * GET:/
     */
    public function showTop() {

        // 新着案件を取得
        $newItemList = Tr_items::entryPossible()
                               ->orderBy('service_start_date', 'desc')
                               ->orderBy('registration_date', 'desc')
                               ->limit(frntUtil::NEW_ITEM_MAX_RESULT)
                               ->get();

        // ピックアップ案件を取得
        $pickUpTagList = Tr_tag_infos::where('tag_type', mdlUtil::TAG_TYPE_PICK_UP)->get();
        $pickUpItemList = [];
        foreach ($pickUpTagList as $pickUpTag) {
            $pickUpItemList =  Tr_items::join('link_items_tags', 'items.id', '=', 'link_items_tags.item_id')
                                       ->join('tags', 'tags.id', '=', 'link_items_tags.tag_id')
                                       ->entryPossible()
                                       ->where('tags.term', $pickUpTag->tag->term)
                                       ->orderBy('service_start_date', 'desc')
                                       ->select('items.*')
                                       ->limit(frntUtil::PICK_UP_ITEM_MAX_RESULT)
                                       ->get();
        }

        //表示するカテゴリーを取得
        $display_category = Tr_search_categories_display::all();
        $parent_array = array();
        $child_array = array();
        foreach ($display_category as $value) {
            if(!in_array($value->parent_id, $parent_array)){
                array_push($parent_array,$value->parent_id);
            }

            if($value->child_id != 0 && !in_array($value->child_id, $child_array)){
                array_push($child_array,$value->child_id);
            }
        }

        if(!empty($parent_array)){
            $parents = Tr_search_categories::whereIn('id', $parent_array)
                                 ->orderBy('parent_sort', 'asc')
                                 ->get();
        }
 
        if(!empty($child_array)){
            $children = Tr_search_categories::whereIn('id', $child_array)
                                 ->orderBy('child_sort', 'asc')
                                 ->get();
        }

        //スキルカテゴリーを取得
        $skillCategories = Ms_skill_categories::select('skill_categories.*')
                                                ->whereExists(function ($query) {
                                                    $query->select(DB::raw(1))
                                                          ->from('skills')
                                                          ->whereRaw('skills.skill_category_id = skill_categories.id')
                                                          ->where('skills.delete_flag',false);
                                                })
                                                ->where('skill_categories.delete_flag',false)
                                                ->orderBy('skill_categories.sort_order', 'asc')
                                                ->get();

        //お知らせを取得
        $newsList = Tr_front_news::where('delete_flag', false)->orderBy('release_date', 'DESC')->limit(5)->get();

        return view('front.top', compact('newItemList','pickUpItemList','parents','children','skillCategories','newsList'));
    }

    /**
     * メールアドレスから、有効なユーザを取得する
     *
     * @param string $mail
     * @return Tr_users $user
     * @throws ModelNotFoundException
     */
    protected function getUserByMail($mail){
        $user = Tr_users::where('mail', $mail)
                        ->enable()
                        ->get();
        if($user->isEmpty()) throw new ModelNotFoundException;
        return $user;
    }

    /**
     * ユーザIDから、有効なユーザを1件取得する
     *
     * @param string $uesr_id
     * @return Tr_users $user
     * @throws ModelNotFoundException
     */
    protected function getUserById($user_id){
        $user = Tr_users::where('id', $user_id)
                        ->enable()
                        ->get();
        if($user->isEmpty()) throw new ModelNotFoundException;
        return $user->first();
    }

    /**
     * 案件IDから、エントリー可能な案件を1件取得する
     *
     * @param string $item_id
     * @return Tr_items $user
     * @throws ModelNotFoundException
     */
    protected function getItemById($item_id){
        $item = Tr_items::where('id', $item_id)
                        ->entryPossible()
                        ->get();
        if($item->isEmpty()) throw new ModelNotFoundException;
        return $item->first();
    }
}
