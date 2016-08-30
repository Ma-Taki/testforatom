<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;
use App\Libraries\{ModelUtility as mdlUtil, FrontUtility as frntUtil};
use App\Http\Requests;
use App\Models\{Tr_items, Tr_tag_infos, Tr_users};

class FrontController extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

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

        return view('front.top', compact('newItemList',
                                         'pickUpItemList'));
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
