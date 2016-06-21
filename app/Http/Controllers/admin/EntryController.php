<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\AdminController;
use App\Http\Requests\admin\EntrySearchRequest;
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
        return view('admin.entry_list', compact('entryList'));
    }

    /* エントリー詳細画面表示 */
    public function showEntryDetail(Request $request){
        $entry_id = $request->input('id');
        $entry = Tr_item_entries::find($entry_id);
        $today = Carbon::today();
        return view('admin.entry_detail', compact('entry', 'today'));
    }

    /* エントリー検索処理 */
    public function searchEntry(EntrySearchRequest $request){
        $entry_id = $request->input('entry_id');
        $date_from = $request->input('entry_date_from');
        $date_to = $request->input('entry_date_to');
        $enabledOnly = $request->input('enabledOnly');

        // 再利用するためパラメータを次のリクエストまで保存
        $request->flash();

        $entryListAll = array();
        $entryList = array();

        // 追加のvalidation：from日付がto日付より大きい場合エラー
        // エントリーIDが入力されている場合はエラーにしない
        if ($date_from > $date_to && $entry_id == null) {
            $c_error_date = 1;
            return view('admin.entry_list', compact('entryList', 'c_error_date'));
        }

        // ID検索　優先順位１位
        if ($entry_id != null) {
            $entryListAll = Tr_item_entries::where('id', $entry_id)->get();
        // エントリー日付検索
        } else if ($date_from != null && $date_to != null) {
            $entryListAll = Tr_item_entries::whereBetween('entry_date', array($date_from.' 00:00:00', $date_to.' 23:59:59'))->get();

        } else if ($date_from != null && $date_to == null) {
            $entryListAll = Tr_item_entries::where('entry_date', '>=',$date_from.' 00:00:00')->get();

        } else if ($date_from == null && $date_to != null) {
            $entryListAll = Tr_item_entries::where('entry_date', '<=',$date_to.' 23:59:59')->get();

        } else {
            // すべてブランク、もしくは"有効なエントリ"のみ入力の場合全件検索する
            $entryListAll = Tr_item_entries::all();
        }

        if (!empty($entryListAll)) {
            foreach ($entryListAll as $entry) {
                if ($enabledOnly) {
                    // 有効なエントリーのみの場合、論理削除済みのものは含めない
                    if ($entry->delete_flag == 0 && $entry->delete_date == null) {
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
