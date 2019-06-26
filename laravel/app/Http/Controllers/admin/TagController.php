<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController;
use App\Models\Tr_tags;
use DB;

class TagController extends AdminController
{

    /**
     * タグ一覧表示
     * GET:/admin/item/tags
     */
    public function showTags(Request $request){

      $data_query = [
          'freeword' => $request->freeword ?: '',
          'enabled'  => $request->enabled ?: ''
      ];

      $itemList = DB::table('tags')
                     ->select(DB::raw('count(link_items_tags.item_id) as total_cnt,count(case when items.service_end_date > now() then 1 else null end) as in_cnt,count(case when items.service_end_date < now() then 1 else null end) as out_cnt, tags.id,tags.term'))
                     ->leftJoin('link_items_tags', 'tags.id' ,'=', 'link_items_tags.tag_id')
                     ->leftJoin('items','link_items_tags.item_id', '=' ,'items.id')
                     ->groupBy('tags.id')
                     ->paginate(30);
      return view('admin.tag_list',compact('itemList','data_query'));
    }

    /**
     * タグ削除
     * GET:/admin/item/tags/delete
     */
    public function deleteTags(Request $request){
      Tr_tags::where('id', $request->id)->delete();
      return redirect($_SERVER['HTTP_REFERER'])
        ->with('custom_info_messages','タグは正常に削除されました');
    }

    /**
     * タグ検索
     * GET:/admin/item/tags/search
     */
    public function searchTags(Request $request){

      $request->flash();

      $data_query = [
          'freeword' => $request->freeword ?: '',
          'enabled'  => $request->enabled ?: ''
      ];


      $splited_order = explode("-",$data_query['enabled']);

      if(empty($splited_order[1])){
        $splited_order[0]='tags.id';
        $splited_order[1]='asc';
      }

      $itemList = DB::table('tags')
                     ->select(DB::raw('count(link_items_tags.item_id) as total_cnt,count(case when items.service_end_date > now() then 1 else null end) as in_cnt,count(case when items.service_end_date < now() then 1 else null end) as out_cnt, tags.id,tags.term'))
                     ->leftJoin('link_items_tags', 'tags.id' ,'=', 'link_items_tags.tag_id')
                     ->leftJoin('items','link_items_tags.item_id', '=' ,'items.id')
                     ->where('tags.term','like','%'.$data_query['freeword'].'%')
                     ->groupBy('tags.id')
                     ->orderBY($splited_order[0],$splited_order[1])
                     ->paginate(30);

      return view('admin.tag_list',compact('itemList','data_query'));
    }

    /**
     * タグ候補
     * POST:/admin/item/input/tagsuggest
     */
    public function suggestTags(Request $request){
      $itemList = Tr_tags::select('term')->where('term','like','%'.$request->input.'%')->get();
      echo json_encode($itemList);
    }



}
