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
use DB;

class EntryController extends AdminController
{
    /**
     * 詳細画面表示
     * GET:/admin/entry/detail
     */
    public function showEntryDetail(Request $request){

        // エントリーID
        $entry_id = $request->input('id');

        // 今日日付
        $today = Carbon::today();

        // エントリー情報を取得する
        $entry = Tr_item_entries::where('id', $entry_id)->get()->first();
        if (empty($entry)) {
            abort(404, '指定されたエントリーは存在しません。');
        }

        return view('admin.entry_detail', compact('entry', 'today'));
    }

    /**
     * 検索処理
     * GET,POST:/admin/entry/search
     */
    public function searchEntry(EntrySearchRequest $request){

        // エントリーID
        $entry_id = $request->input('entry_id');
        // エントリー日付(開始日)
        $entry_date_from = $request->input('entry_date_from');
        // エントリー日付(終了日)
        $entry_date_to = $request->input('entry_date_to');
        // 有効なエントリーのみか
        $enabledOnly = $request->input('enabledOnly');

        // 追加のvalidation：from日付がto日付より大きい場合エラー
        // エントリーIDが入力されている場合はエラーにしない
        if (!empty($entry_date_from) && !empty($entry_date_to) && $entry_date_from > $entry_date_to && empty($entry_id)) {
            // フラッシュセッションにエラーメッセージを保存
            \Session::flash('custom_error_messages', 'エントリー日付(終了日)がエントリー日付(開始日)より過去になっています。');
            return back()->withInput();
        }

        // パラメータの入力状態によって動的にクエリを発行
        $query = Tr_item_entries::query();

        // ID検索　優先順位１位
        if (!empty($entry_id)) {
            $query = $query->where('id', $entry_id);
        // エントリー日付検索 from~to
        } else if (!empty($entry_date_from) && !empty($entry_date_to)) {
            $query = $query->whereBetween('entry_date', array($entry_date_from.' 00:00:00', $entry_date_to.' 23:59:59'));
        // エントリー日付検索 from~
        } else if (!empty($entry_date_from) && empty($entry_date_to)) {
            $query = $query->where('entry_date', '>=',$entry_date_from.' 00:00:00');
        // エントリー日付検索 ~to
        } else if (empty($entry_date_from) && !empty($entry_date_to)) {
            $query = $query->where('entry_date', '<=',$entry_date_to.' 23:59:59');
        } else {
            // すべてブランク、もしくは"有効なエントリ"のみ入力の場合全件検索する
        }

        // 有効なエントリーのみの場合、論理削除済みのものは含めない
        if ($enabledOnly) {
            $query = $query->where('delete_flag', '>', 0)
                           ->where('delete_date', '!=', null);
        }

        // 検索結果を取得する
        $entryList = $query->get();

        return view('admin.entry_list', compact(
            'entryList',
            'entry_id',
            'entry_date_from',
            'entry_date_to',
            'enabledOnly'
        ));
    }

    /**
     * 論理削除処理
     * GET:/admin/entry/delete
     */
    public function deleteEntry(Request $request){

        // エントリーID
        $entry_id = $request->input('id');
        // 現在時刻
        $timestamp = time();
        // エントリー情報を取得する
        $entry = Tr_item_entries::where('id', $entry_id)->get()->first();
        if (empty($entry)) {
            abort(404, '指定されたエントリーは存在しません。');
        } elseif($entry->delete_flag > 0 || $entry->delete_date != null) {
            abort(404, '指定されたエントリーは既に削除されています。');
        }

        // トランザクション
        DB::transaction(function () use ($entry_id, $timestamp) {
            try {
                // エントリーテーブルをアップデート
                Tr_item_entries::where('id', $entry_id)->update([
                    'delete_flag' => 1,
                    'delete_date' => date('Y-m-d H:i:s', $timestamp),
                ]);
            } catch (\Exception $e) {
                // TODO エラーのログ出力
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        // エントリーシートを削除
        if (!empty($entry) && $entry->skillsheet_upload && !empty($entry->skillsheet_filename)) {
            Storage::disk('local')->delete($entry->skillsheet_filename);
        }

        return redirect('/admin/entry/search')->with('custom_info_messages','エントリー削除は正常に終了しました。');
    }

    /**
     * エントリーシートダウンロード処理
     * GET:/admin/entry/download
     */
    public function downloadSkillSheet(Request $request){

        // エントリーID
        $entry_id = $request->input('id');
        // ストレージパス
        $localStoragePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        // エントリー情報を取得する
        $entry = Tr_item_entries::where('id', $entry_id)->get()->first();
        if (empty($entry)) {
            abort(404, 'エントリーが存在しません。');
        } elseif($entry->delete_flag > 0 || $entry->delete_date != null) {
            // エントリー削除時にエントリーシートも削除しているため
            abort(404, 'エントリーは既に削除されています。');
        } elseif (!$entry->skillsheet_upload) {
            abort(404, 'エントリーシート未アップロードです。');
        }

        // ストレージへエントリーシートの存在チェック
        if (!Storage::disk('local')->exists($entry->skillsheet_filename)) {
            abort(404, 'エントリーシートが見つかりません。');
        }

        return response()->download($localStoragePath.$entry->skillsheet_filename);
    }
}
