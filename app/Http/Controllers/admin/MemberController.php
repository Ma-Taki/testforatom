<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\AdminController;
use App\Models\Tr_users;
use Carbon\Carbon;

class MemberController extends AdminController
{
    /* 会員一覧画面表示 */
    public function showMemberList(){
        $memberList = Tr_users::all();
        return view('admin.member_list', compact('memberList', $memberList));
    }

    /* 会員詳細画面表示 */
    public function showMemberDetail(Request $request){
        $member_id = $request->input('id');
        $member = Tr_users::find($member_id);
        if ($member == null) {
        }
        return view('admin.member_detail', compact('member', $member));
    }

    /* 会員検索処理 */
    public function searchMember(Request $request){
        $member_id = $request->input('member_id');
        $member_name = $request->input('member_name');
        $member_name_kana = $request->input('member_name_kana');
        $enabledOnly = $request->input('enabledOnly');

        /*
        if ($entry_id != '') {
            $entryList = Tr_item_entries::where('id', $entry_id)->get();
            if (!$entryList->isEmpty()) {
                if (!$enabledOnly) {
                    return view('admin.entry_list', compact('entryList', $entryList));
                } else {
                    if ($entryList->first()->delete_flag == 0
                        && $entryList->first()->delete_date == null) {
                            return view('admin.entry_list', compact('entryList', $entryList));
                    }
                }
            }
        } else if ($date_from != null && $date_to != null) {

        }
        */
        $memberList = Tr_users::all();
        return view('admin.member_list', compact('memberList', $memberList));
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
