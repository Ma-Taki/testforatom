<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\admin\MemberSearchRequest;
use App\Http\Controllers\AdminController;
use App\Models\Tr_users;
use App\Models\Tr_user_social_accounts;
use Carbon\Carbon;
use DB;
use App\Libraries\OrderUtility as OdrUtil;

class MemberController extends AdminController
{
    /**
     * 詳細画面表示
     * GET:/admin/member/detail
     */
    public function showMemberDetail(Request $request){

        // 会員ID
        $member_id = $request->input('id');

        // 会員情報を取得する
        $member = Tr_users::where('id', $member_id)->get()->first();
        if (empty($member)) {
            abort(404, '指定された会員は存在しません。');
        }
        return view('admin.member_detail', compact('member'));
    }

    /**
     * 検索処理
     * GET,POST:/admin/member/search
     */
    public function searchMember(MemberSearchRequest $request){

        // メールアドレス
        $member_mail = $request->input('member_mail');
        // 会員名
        $member_name = $request->input('member_name');
        // 会員名（かな）
        $member_name_kana = $request->input('member_name_kana');
        // 有効なエントリーのみか
        $enabledOnly = $request->input('enabledOnly');
        // ソートID 初期表示の場合は更新日が新しい順を設定
        $sort_id = $request->input('sort_id', OdrUtil::ORDER_MEMBER_REGISTRATION_DESC['sortId']);
        // ソート順
        $item_order = OdrUtil::MemberOrder[$sort_id];

        // パラメータの入力状態によって動的にクエリを発行
        $query = Tr_users::query();

        // メールアドレス検索
        if (!empty($member_mail)) {
            //　asciiに変換できない文字を含む場合エラー
            if (mb_check_encoding($member_mail, 'ASCII')) {
                // 文字列を半角スペースで分割
                $member_mail_array = explode(' ', $member_mail);
                // 空要素を削除
                $member_mail_array = array_filter($member_mail_array, 'strlen');
                foreach ($member_mail_array as $mail_str) {
                    $query->where('mail', 'like', '%'.$mail_str.'%');
                }
            } else {
                // フラッシュセッションにエラーメッセージを保存
                \Session::flash('custom_error_messages', ['メールアドレスに使用できない文字が含まれています。']);
                return back()->withInput();
            }
        }
        // 会員名検索
        if (!empty($member_name)) {
            // 全角スペースを半角に変換する
            $member_name_hankaku = str_replace('　', ' ', $member_name);
            // 文字列を半角スペースで分割
            $member_name_array = explode(' ', $member_name_hankaku);
            // 空要素を削除
            $member_name_array = array_filter($member_name_array, 'strlen');
            foreach ($member_name_array as $name_str) {
                $query->where(DB::raw("CONCAT(last_name, first_name)"),'LIKE',"%".$name_str."%");
            }
        }
        // 会員名（かな）検索
        if (!empty($member_name_kana)) {
            // 全角スペースを半角に変換する
            $member_name_kana_hankaku = str_replace('　', ' ', $member_name_kana);
            // 文字列を半角スペースで分割
            $member_name_kana_array = explode(' ', $member_name_kana_hankaku);
            // 空要素を削除
            $member_name_kana_array = array_filter($member_name_kana_array, 'strlen');
            foreach ($member_name_kana_array as $name_kana_str) {
                $query->where(DB::raw("CONCAT(last_name_kana, first_name_kana)"),'LIKE',"%".$name_kana_str."%");
            }
        }
        // 有効な会員のみの場合、論理削除済みのものは含めない
        if ($enabledOnly) {
            $query = $query->where('delete_flag', '=', 0)
                           ->where('delete_date', '=', null);
        }

        // 検索結果を取得する
        $memberList = $query->orderBy($item_order['columnName'], $item_order['sort'])
                            ->paginate(30);

        return view('admin.member_list', compact(
            'memberList',
            'sort_id',
            'member_mail',
            'member_name',
            'member_name_kana',
            'enabledOnly'));
    }

    /**
     * 更新処理（メモ）
     * POST:/admin/member/update
     */
    public function updatehMemberMemo(Request $request){

        // 会員ID
        $member_id = $request->input('member_id');
        // メモ
        $memo = $request->input('memo');

        // 更新対象会員を取得
        $member = Tr_users::where('id', $member_id)->get()->first();
        if (empty($member)) {
            abort(404, '指定された会員は存在しません。');
        }
        // トランザクション
        DB::transaction(function () use ($member_id, $memo) {
            try {
                // ユーザーテーブルをアップデート
                 Tr_users::where('id', $member_id)
                         ->update(['note' => $memo,]);
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        return redirect('/admin/member/detail?id='.$member_id)
            ->with('custom_info_messages','会員メモを更新しました。');
    }

    /**
     * 論理削除処理
     * GET:/admin/member/delete
     */
    public function deleteMember(Request $request){

        // 会員ID
        $member_id = $request->input('id');

        // 削除対象会員を取得
        $member = Tr_users::where('id', $member_id)->get()->first();
        if (empty($member)) {
            abort(404, '指定された会員は存在しません。');
        } elseif ($member->delete_flag > 0 || $member->delete_date != null) {
            abort(404, '指定された会員は既に削除されています。');
        }

        // 現在時刻
        $timestamp = time();

        // トランザクション
        DB::transaction(function () use ($member_id, $timestamp) {
            try {
                // ユーザテーブルをアップデート
                Tr_users::where('id', $member_id)->update([
                    'delete_flag' => 1,
                    'delete_date' => date('Y-m-d', $timestamp),
                ]);

                // SNS連携テーブルをデリート
                Tr_user_social_accounts::where('user_id', $member_id)->delete();

            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        return redirect('/admin/member/search')
            ->with('custom_info_messages','会員削除は正常に終了しました。');
    }
}
