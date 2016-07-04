<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\admin\MemberSearchRequest;
use App\Http\Controllers\AdminController;
use App\Models\Tr_users;
use Carbon\Carbon;
use DB;

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

        // 再利用するためパラメータを次のリクエストまで保存
        $request->flash();

        // パラメータの入力状態によって動的にクエリを発行
        $query = Tr_users::query();

        // メールアドレス検索
        if (!empty($member_mail))
            $query->where('mail', 'LIKE', "%".$member_mail."%");
        // 会員名検索
        if (!empty($member_name))
            $query->where(DB::raw("CONCAT(first_name, last_name)"),'LIKE',"%".$member_name."%");
        // 会員名（かな）検索
        if (!empty($member_name_kana))
            $query->where(DB::raw("CONCAT(first_name_kana, last_name_kana)"),'LIKE',"%".$member_name_kana."%");

        // 有効なエントリーのみの場合、論理削除済みのものは含めない
        if ($enabledOnly) {
            $query = $query->where('delete_flag', '=', 0)
                           ->where('delete_date', '=', null);
        }

        // 検索結果を取得する
        $memberList = $query->get();

        return view('admin.member_list', compact('memberList'));
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

        // トランザクション
        DB::transaction(function () use ($member_id, $memo) {
            try {
                // ユーザーテーブルをアップデート
                 Tr_users::where('id', $member_id)
                         ->update(['note' => $memo,]);
            } catch (\Exception $e) {
                // TODO エラーのログ出力
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
        // 現在時刻
        $timestamp = time();

        // トランザクション
        DB::transaction(function () use ($member_id, $timestamp) {
            try {
                // ユーザーテーブルをアップデート
                Tr_users::where('id', $member_id)->update([
                    'delete_flag' => 1,
                    'delete_date' => date('Y-m-d H:i:s', $timestamp),
                ]);

            } catch (\Exception $e) {
                // TODO エラーのログ出力
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        return redirect('/admin/member/search')
            ->with('custom_info_messages','会員削除は正常に終了しました。');
    }
}
