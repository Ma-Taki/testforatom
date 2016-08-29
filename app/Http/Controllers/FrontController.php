<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Libraries\ModelUtility as mdlUtil;
use App\Libraries\FrontUtility as frntUtil;
use App\Http\Requests;
use App\Models\Tr_items;
use App\Models\Tr_tag_infos;

class FrontController extends Controller
{
    /**
     * トップ画面表示
     * GET:/
     */
    public function index() {

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

}
