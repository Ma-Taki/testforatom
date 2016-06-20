<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\AdminController;
use App\Models\Tr_item_entries;
use App\Models\Tr_items;
use App\Models\Tr_users;
use Carbon\Carbon;
use Storage;

class EntryController extends AdminController
{
    /* エントリー一覧画面表示 */
    public function showEntryList(){
        $entryList = Tr_item_entries::all();
        return view('admin.entry_list', compact('entryList', $entryList));
    }

    /* エントリー詳細画面表示 */
    public function showEntryDetail(Request $request){
        $entry_id = $request->input('id');
        $entry = Tr_item_entries::find($entry_id);
        $today = Carbon::today();
        return view('admin.entry_detail', compact('entry', $entry,
                                                  'today', $today));
    }

    /* エントリー検索処理 */
    public function searchEntry(Request $request){
        $entry_id = $request->input('entry_id');
        $date_from = $request->input('entry_date_from');
        $date_to = $request->input('entry_date_to');
        $enabledOnly = $request->input('enabledOnly');

        $entryListAll = array();

        // ID検索が優先順位１位
        if ($entry_id != null) {
            if ($enabledOnly) {
                $entryListAll = Tr_item_entries::where('id', $entry_id)->where('delete_flag', 0)->where('delete_date', null)->get();
            } else {
                $entryListAll = Tr_item_entries::where('id', $entry_id)->get();
            }
        } else if ($date_from != null && $date_to != null) {
            if ($enabledOnly) {
                $entryListAll = Tr_item_entries::whereBetween('entry_date', array($date_from, $date_to))->where('delete_flag', 0)->where('delete_date', null)->get();
            } else {
                $entryListAll = Tr_item_entries::whereBetween('entry_date', array($date_from, $date_to))->get();
            }
        } else {
            //　とりあえず全件検索してる
            $entryListAll = Tr_item_entries::all();
        }

        $entryList = array();
        if (!empty($entryListAll)) {
            foreach ($entryListAll as $entry) {
                if ($enabledOnly) {
                    // 有効なエントリーのみの場合、論理削除済みのものは含めない
                    if ($entry->delete_flag == 0
                        && $entry->delete_date == null) {
                            array_push($entryList, $entry);
                    }
                } else {
                    array_push($entryList, $entry);
                }
            }
        }

        return view('admin.entry_list', compact('entryList'));
    }

    /* エントリー論理削除処理 */
    public function deleteEntry(Request $request){
        $entry_id = $request->input('id');
        $entry = Tr_item_entries::find($entry_id);

        // 既に論理削除済み、または未アップロード
        if ($entry == null || $entry->delete_flag > 0 || !$entry->skillsheet_upload){
            return redirect('admin/top');
        }

        // エントリーシートを削除
        if ($entry != null && $entry->skillsheet_upload && $entry->skillsheet_filename != null) {
            Storage::disk('local')->delete('test.xls');
        }

        // 現在時刻
        $timestamp = time();

        // エントリーテーブルをアップデート
        Tr_item_entries::where('id', $entry_id)->update([
            'delete_flag' => 1,
            'delete_date' => date('Y-m-d H:i:s', $timestamp),
        ]);

        // エントリーシートを削除
        if ($entry != null && $entry->skillsheet_upload && $entry->skillsheet_filename != null) {
            Storage::disk('local')->delete('test.xls');
        }
    }

    /* エントリーシートダウンロード処理 */
    public function downloadSkillSheet(Request $request){
        $entry_id = $request->input('id');
        $entry = Tr_item_entries::where('id', $entry_id)->where('skillsheet_upload', 1)->get();
        if ($entry->isEmpty()) {
            redirect('admin.top');
        }
        return response()->download('/Users/sd-pc019/Engineer-Route/storage/app/'.$entry->first()->skillsheet_filename);
    }

}
