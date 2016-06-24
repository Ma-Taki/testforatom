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
    /* 会員一覧画面表示 */
    public function showMemberList(){
        $memberList = Tr_users::all();
        return view('admin.member_list', compact('memberList'));
    }

    /* 会員詳細画面表示 */
    public function showMemberDetail(Request $request){
        $member_id = $request->input('id');
        $member = Tr_users::find($member_id);
        if ($member == null) {
        }
        return view('admin.member_detail', compact('member'));
    }

    /* 会員検索処理 */
    public function searchMember(MemberSearchRequest $request){
        $member_mail = $request->input('member_mail');
        $member_name = $request->input('member_name');
        $member_name_kana = $request->input('member_name_kana');
        $enabledOnly = $request->input('enabledOnly');

        // 再利用するためパラメータを次のリクエストまで保存
        $request->flash();

        $memberListAll = array();
        $memberList = array();

        // パラメータの入力状態によって動的にクエリを発行
        $query = Tr_users::query();
        if ($member_mail != null) {
            $query->where('mail', 'LIKE', "%".$member_mail."%");
        } elseif ($member_name != null) {
            $query->where(DB::raw("CONCAT(first_name, last_name)"),'LIKE',"%".$member_name."%");
        } elseif ($member_name_kana != null) {
            $query->where(DB::raw("CONCAT(first_name_kana, last_name_kana)"),'LIKE',"%".$member_name_kana."%");
        }
        $memberListAll = $query->get();



        // これも動的に含めてよくないか
        if (!empty($memberListAll)) {
            foreach ($memberListAll as $member) {
                if ($enabledOnly) {
                    // 有効なユーザのみの場合、論理削除済みのものは含めない
                    if ($member->delete_flag == 0 && $member->delete_date == null) {
                        array_push($memberList, $member);
                    }
                } else {
                    array_push($memberList, $member);
                }
            }
        }

        return view('admin.member_list', compact('memberList'));
    }

    /* 更新処理（メモ） */
    public function updatehMemberMemo(Request $request){
        $memo = $request->input('memo');
        $member_id = $request->input('member_id');

        // ユーザーテーブルをアップデート
         Tr_users::where('id', $member_id)->update([
            'note' => $memo,
        ]);

        return redirect('/admin/member/detail?id='.$member_id);
    }

    /* 会員論理削除処理 */
    public function deleteMember(Request $request){
        $member_id = $request->input('id');

        // 現在時刻
        $timestamp = time();

        // ユーザーテーブルをアップデート
        Tr_users::where('id', $member_id)->update([
            'delete_flag' => 1,
            'delete_date' => date('Y-m-d H:i:s', $timestamp),
        ]);

        return redirect('/admin/member/list');
    }
}
