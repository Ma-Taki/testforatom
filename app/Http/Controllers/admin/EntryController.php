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
use App\Libraries\OrderUtility as OdrUtil;

class EntryController extends AdminController
{
    /**
     * 詳細画面表示
     * GET:/admin/entry/detail
     */
    public function showEntryDetail(Request $request){

        // エントリー情報を取得する
        $entry = Tr_item_entries::where('id', $request->id)->first();
        if (empty($entry)) {
            abort(404, '指定されたエントリーは存在しません。');
        }

        return view('admin.entry_detail', [
            'entry' => $entry,
            'today' => Carbon::today(),
        ]);
    }

    /**
     * 検索処理
     * GET:/admin/entry/search
     */
    public function searchEntry(EntrySearchRequest $request){

        $data_query = [
            'entry_id'        => $request->entry_id ?: '',        // エントリーID
            'entry_date_from' => $request->entry_date_from ?: '', // エントリー日付(開始日)
            'entry_date_to'   => $request->entry_date_to ?: '',   // エントリー日付(終了日)
            'impression'      => $request->impression ?: [],      // 評価
            'enabledOnly'     => $request->enabledOnly ?: 'off',   // 有効なエントリーのみか
            'sort_id' => $request->sort_id ?: OdrUtil::ORDER_ENTRY_DATE_DESC['sortId'],
        ];
        // ソート順 初期表示の場合はエントリー日付が新しい順を設定
        $item_order = OdrUtil::EntryOrder[$data_query['sort_id']];

        // from日付とto日付がどちらも入力されていて、fromがtoより大きい場合エラー
        if (!empty($entry_date_from) && !empty($entry_date_to) && $entry_date_from > $entry_date_to) {
            // エントリーIDが入力されている場合はエラーにしない
            if (empty($entry_id)) {
                // フラッシュセッションにエラーメッセージを保存
                \Session::flash('custom_error_messages', 'エントリー日付(終了日)がエントリー日付(開始日)より過去になっています。');
                return back()->withInput();
            }
        }

        // パラメータの入力状態によって動的にクエリを発行
        $query = Tr_item_entries::query();

        // ID検索
        if (!empty($data_query['entry_id'])) {
            $query = $query->where('id', $data_query['entry_id']);

        } else {
            // from日付
            if (!empty($data_query['entry_date_from'])) {
                $query = $query->where('entry_date', '>=',$data_query['entry_date_from'].' 00:00:00');
            }
            // to日付
            if (!empty($data_query['entry_date_to'])) {
                $query = $query->where('entry_date', '<=',$data_query['entry_date_to'].' 23:59:59');
            }
            // 評価
            // valueが'off'の要素を削除する
            $data_query['impression'] = array_filter($data_query['impression'], function ($value) {
                return $value !== 'off';
            });
            if (!empty($data_query['impression'])) {
                $query->join('users', 'users.id', '=', 'item_entries.user_id');
                $query = $query->whereIn('users.impression', $data_query['impression']);
            }
            // 有効なエントリーのみの場合、論理削除済みのものは含めない
            if ($data_query['enabledOnly'] == 'on') {
                $query = $query->where('item_entries.delete_flag', '=', 0)
                               ->where('item_entries.delete_date', '=', null);
            }
        }

        // 検索結果を取得する
        $entry_list = $query->select('item_entries.*')
                           ->orderBy($item_order['columnName'], $item_order['sort'])
                           ->paginate(30);

        return view('admin.entry_list', compact('entry_list', 'data_query'));
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
                Log::error($e);
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
