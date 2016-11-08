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
use App\Libraries\ModelUtility as MdlUtil;

class MemberController extends AdminController
{
    /**
     * 詳細画面表示
     * GET:/admin/member/detail
     */
    public function showMemberDetail(Request $request){
        // 会員情報を取得する
        $member = Tr_users::where('id', $request->id)->first();
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

        // パラメータの入力状態によって動的にクエリを発行
        $query = Tr_users::query();

        // メールアドレス検索
        if (!empty($request->member_mail)) {
            //　asciiに変換できない文字を含む場合エラー
            if (mb_check_encoding($request->member_mail, 'ASCII')) {
                // 文字列を半角スペースで分割
                $member_mail_array = explode(' ', $request->member_mail);
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
        if (!empty($request->member_name)) {
            // 全角スペースを半角に変換する
            $member_name_hankaku = str_replace('　', ' ', $request->member_name);
            // 文字列を半角スペースで分割
            $member_name_array = explode(' ', $member_name_hankaku);
            // 空要素を削除
            $member_name_array = array_filter($member_name_array, 'strlen');
            foreach ($member_name_array as $name_str) {
                $query->where(DB::raw("CONCAT(last_name, first_name)"),'LIKE',"%".$name_str."%");
            }
        }
        // 会員名（かな）検索
        if (!empty($request->member_name_kana)) {
            // 全角スペースを半角に変換する
            $member_name_kana_hankaku = str_replace('　', ' ', $request->member_name_kana);
            // 文字列を半角スペースで分割
            $member_name_kana_array = explode(' ', $member_name_kana_hankaku);
            // 空要素を削除
            $member_name_kana_array = array_filter($member_name_kana_array, 'strlen');
            foreach ($member_name_kana_array as $name_kana_str) {
                $query->where(DB::raw("CONCAT(last_name_kana, first_name_kana)"),'LIKE',"%".$name_kana_str."%");
            }
        }
        // フリーワード検索
        if (!empty($request->freeword)) {
            // 全角スペースを半角に変換する
            $freeword_hankaku = str_replace('　', ' ', $request->freeword);
            // 文字列を半角スペースで分割
            $freeword_hankaku_array = explode(' ', $freeword_hankaku);
            // 空要素を削除
            $freeword_hankaku_array = array_filter($freeword_hankaku_array, 'strlen');

            // 性別、評価、ステータスは定数があれば条件に含める
            $freeword_collection = collect($freeword_hankaku_array);
            if (($index = $freeword_collection->search('優良')) !== false) {
                $query->where('users.impression', MdlUtil::USER_IMPRESSION_EXCELLENT);
                $freeword_collection->forget($index);
            }
            if (($index = $freeword_collection->search('普通')) !== false) {
                $query->where('users.impression', MdlUtil::USER_IMPRESSION_NORMAL);
                $freeword_collection->forget($index);
            }
            if (($index = $freeword_collection->search('いまいち')) !== false) {
                $query->where('users.impression', MdlUtil::USER_IMPRESSION_NOTGOOD);
                $freeword_collection->forget($index);
            }
            if (($index = $freeword_collection->search('ブラック')) !== false) {
                $query->where('users.impression', MdlUtil::USER_IMPRESSION_BLACK);
                $freeword_collection->forget($index);
            }
            if (($index = $freeword_collection->search('男性')) !== false) {
                $query->where('users.sex', 'Male');
                $freeword_collection->forget($index);
            }
            if (($index = $freeword_collection->search('女性')) !== false) {
                $query->where('users.sex', 'Female');
                $freeword_collection->forget($index);
            }
            if (($index = $freeword_collection->search('有効')) !== false) {
                $query->enable();
                $freeword_collection->forget($index);
            }
            if (($index = $freeword_collection->search('無効')) !== false) {
                $query->disable();
                $freeword_collection->forget($index);
            }
            $query->join('prefectures', 'users.prefecture_id', '=', 'prefectures.id');
            foreach ($freeword_collection as $freeword_str) {
                $query->where(DB::raw(
                    "CONCAT(last_name, first_name, last_name_kana, first_name_kana, sex, IFNULL(education_level,''), IFNULL(nationality,''), prefectures.name, IFNULL(station,''), mail, tel, TIMESTAMPDIFF(YEAR,birth_date,CURDATE()), note)"),'LIKE',"%".$freeword_str."%"
                );
            }
        }

        // 有効な会員のみの場合、論理削除済みのものは含めない
        if ($request->enabledOnly) {
            $query = $query->enable();
        }

        // 評価にチェックがあった場合、チェックのなかったものは含めない
        $impression_array = (array)$request->impression ?: [];
        if (!empty($impression_array)) {
            $query = $query->whereIn('impression', $impression_array);
        }

        // 表示順序を設定(初期表示時は登録日が新しい順)
        $sort_id = $request->sort_id ?: OdrUtil::ORDER_MEMBER_REGISTRATION_DESC['sortId'];
        $item_order = OdrUtil::MemberOrder[$sort_id];

        // 検索結果を取得する
        $query->select('users.*');
        $memberList = $query->orderBy($item_order['columnName'], $item_order['sort'])
                            ->paginate(30);
                            
        return view('admin.member_list', [
            'memberList' => $memberList,
            'sort_id' => $sort_id,
            'member_mail' => $request->member_mail,
            'member_name' => $request->member_name,
            'member_name_kana' => $request->member_name_kana,
            'freeword' => $request->freeword,
            'enabledOnly' => $request->enabledOnly,
            'impression_array' => $impression_array,
        ]);
    }

    /**
     * 更新処理（メモと評価）
     * POST:/admin/member/update
     */
    public function updatehMemberMemo(Request $request){

        // 更新対象会員を取得
        $member = Tr_users::where('id', $request->member_id)->first();
        if (empty($member)) {
            abort(404, '指定された会員は存在しません。');
        }

        $data_db = [
            'member' => $member,
            'memo' => $request->memo ?: '',
            'impression' => $request->impression ?: MdlUtil::USER_IMPRESSION_NORMAL,
        ];

        // トランザクション
        DB::transaction(function () use ($data_db) {
            try {
                // ユーザーテーブルをアップデート
                 $data_db['member']->note = $data_db['memo'];
                 $data_db['member']->impression = $data_db['impression'];
                 $data_db['member']->save();

            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        return redirect('/admin/member/detail?id='.$request->member_id)
            ->with('custom_info_messages','会員評価 / メモを更新しました。');
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
