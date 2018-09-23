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
use ZipArchive;
use Storage;

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
     * GET:/admin/member/search
     *
     * MemberSearchRequest 使ってない
     */
    public function searchMember(MemberSearchRequest $request){

        // クエリ生成用データ
        $data_query = [
            'member_mail'      => $request->member_mail ?: '',
            'member_name'      => $request->member_name ?: '',
            'member_name_kana' => $request->member_name_kana ?: '',
            'freeword'         => $request->freeword ?: '',
            'enabledOnly'      => $request->enabledOnly ?: '',
            'impression'       => $request->impression ?: [],
            'status'           => $request->status ?: [],
            'sort_id'          => $request->sort_id ?: OdrUtil::ORDER_MEMBER_REGISTRATION_DESC['sortId'],
            'sns'              => $request->sns ?: [],
        ];

        // パラメータの入力状態によって動的にクエリを発行
        $query = Tr_users::query();

        // メールアドレス検索
        if (!empty($data_query['member_mail'])) {
            //　asciiに変換できない文字を含む場合エラー
            if (mb_check_encoding($data_query['member_mail'], 'ASCII')) {
                $member_mail_array = $this->convertPramStrToArray($data_query['member_mail']);
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
        if (!empty($data_query['member_name'])) {
            $member_name_array = $this->convertPramStrToArray($data_query['member_name']);
            foreach ($member_name_array as $name_str) {
                $query->where(DB::raw("CONCAT(last_name, first_name)"),'LIKE',"%".$name_str."%");
            }
        }
        // 会員名（かな）検索
        if (!empty($data_query['member_name_kana'])) {
            $member_name_kana_array = $this->convertPramStrToArray($data_query['member_name_kana']);
            foreach ($member_name_kana_array as $name_kana_str) {
                $query->where(DB::raw("CONCAT(last_name_kana, first_name_kana)"),'LIKE',"%".$name_kana_str."%");
            }
        }
        // フリーワード検索
        if (!empty($data_query['freeword'])) {
            $freeword_array = $this->convertPramStrToArray($data_query['freeword']);
            // Collectionに変換
            $freeword_collection = collect($freeword_array);
            // 性別、評価、ステータスは定数値と合致するものがあれば条件に含める
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
                    "CONCAT(last_name, first_name, last_name_kana, first_name_kana, sex, IFNULL(education_level,''), IFNULL(nationality,''), prefectures.name, IFNULL(station,''), mail, tel, TIMESTAMPDIFF(YEAR,birth_date,CURDATE()), IFNULL(note,''))"),'LIKE',"%".$freeword_str."%"
                );
            }
        }

        // 有効な会員のみの場合、論理削除済みのものは含めない
        //ステータス
        if ($data_query['enabledOnly'] == 'on') {
            $query = $query->enable();
        }

        // valueが'off'の要素を削除する
        //評価
        $data_query['impression'] = array_filter($data_query['impression'], function ($value) {
            return $value !== 'off';
        });

        if (!empty($data_query['impression'])) {
            $query = $query->whereIn('impression', $data_query['impression']);
        }

        //進捗状況
        // valueが'off'の要素を削除する
        $data_query['status'] = array_filter($data_query['status'], function ($value) {
            return $value !== 'off';
        });

        if (!empty($data_query['status'])) {
            $query = $query->whereIn('status', $data_query['status']);
        }

        //表示順
        $item_order = OdrUtil::MemberOrder[$data_query['sort_id']];

        //SNS連携
        // valueが'off'の要素を削除する
        $data_query['sns'] = array_filter($data_query['sns'], function ($value) {
            return $value !== 'off';
        });

        if (!empty($data_query['sns'])) {
          $sns =$data_query['sns'];
          $query = $query->whereExists(function ($query) use ($sns) {
                                            $query->select(DB::raw(1))
                                                  ->from('user_social_accounts')
                                                  ->whereRaw('user_social_accounts.user_id = users.id')
                                                  ->whereIn('user_social_accounts.social_account_type', $sns);
                                        });
        }

        // 検索結果を取得する
        $memberList = $query->select('users.*')
                            ->orderBy($item_order['columnName'], $item_order['sort'])
                            ->paginate(30);
        //有効会員人数                    
        $enable = Tr_users::where('delete_flag','=',0)->count();
        //退会人数                    
        $Unsubscribe = Tr_users::where('delete_flag','>', 0)->count();
        
        return view('admin.member_list', compact('memberList', 'data_query', 'enable', 'Unsubscribe'));
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

    /**
     * 進捗状況メモ編集
     * GET:/admin/member/memo
     */
    public function ajaxEditMemberStatus(Request $request){

      // 更新対象会員を取得
      $member = Tr_users::where('id', $request->id)->first();
      if (empty($member)) {
          abort(404, '指定された会員は存在しません。');
      }

      $data_db = [
          'member' => $member,
          'memo' => $request->text ?: ''
      ];

      // トランザクション
      DB::transaction(function () use ($data_db) {
          try {
              // ユーザーテーブルをアップデート
               $data_db['member']->memo = $data_db['memo'];
               $data_db['member']->save();

          } catch (\Exception $e) {
              Log::error($e);
              abort(400, 'トランザクションが異常終了しました。');
          }
      });

      echo json_encode($data_db['memo']);
    }

    /**
     * 進捗状況選択
     * GET:/admin/member/selectstatus
     */
    public function ajaxSelectMemberStatus(Request $request){

      // 更新対象会員を取得
      $member = Tr_users::where('id', $request->id)->first();
      if (empty($member)) {
          abort(404, '指定された会員は存在しません。');
      }

      $data_db = [
          'member' => $member,
          'status' => $request->selected
      ];

      // トランザクション
      DB::transaction(function () use ($data_db) {
          try {
              // ユーザーテーブルをアップデート
               $data_db['member']->status = $data_db['status'];
               $data_db['member']->save();

          } catch (\Exception $e) {
              Log::error($e);
              abort(400, 'トランザクションが異常終了しました。');
          }
      });

      echo json_encode($data_db['status']);
    }

    /**
     * 詳細画面にて進捗状況更新
     * GET:/admin/member/updatestatus
     */
    public function updateMemberStatus(Request $request){

      // 更新対象会員を取得
      $member = Tr_users::where('id', $request->member_id)->first();
      if (empty($member)) {
          abort(404, '指定された会員は存在しません。');
      }

      $data_db = [
          'member' => $member,
          'status' => $request->selected,
          'memo' => $request->text ?: ''
      ];

      // トランザクション
      DB::transaction(function () use ($data_db) {
          try {
              // ユーザーテーブルをアップデート
               $data_db['member']->status = $data_db['status'];
               $data_db['member']->memo = $data_db['memo'];
               $data_db['member']->save();

          } catch (\Exception $e) {
              Log::error($e);
              abort(400, 'トランザクションが異常終了しました。');
          }
      });

      return redirect('/admin/member/detail?id='.$request->member_id)
          ->with('custom_info_messages',' 進捗状況を更新しました。');

    }

    /**
     * 引数の文字列に対して以下の処理を行う
     * 1.全角スペースを半角に変換する
     * 2.文字列を半角スペースで分割
     * 3.空要素を削除
     * @var $paramStr GetParameter
     * @return $paramStr_hankaku_array array
     */
    private function convertPramStrToArray($paramStr){

        $paramStr_hankaku       = str_replace('　', ' ', $paramStr);
        $paramStr_hankaku_array = explode(' ', $paramStr_hankaku);
        $paramStr_hankaku_array = array_filter($paramStr_hankaku_array, 'strlen');

        return $paramStr_hankaku_array;
    }

    /**
     * スキルシートダウンロード処理
     * GET:/admin/member/download
     */
    public function downloadSkillSheet(Request $request){

        // 会員ID
        $member_id = $request->input('id');
        // ストレージパス
        $localStoragePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        // 会員情報を取得する
        $member = Tr_users::where('id', $member_id)->get()->first();
        if (empty($member)) {
            abort(404, '会員が存在しません。');
        } elseif($member->delete_flag > 0 || $member->delete_date != null) {
            // エントリー削除時にエントリーシートも削除しているため
            abort(404, '会員は既に削除されています。');
        } elseif (!$member->skillsheet_upload) {
            abort(404, 'スキルシート未アップロードです。');
        }

        // ストレージへエントリーシートの存在チェック
        if (!empty($member->skillsheet_1) && !Storage::disk('local')->exists($member->skillsheet_1)) {
            abort(404, 'スキルシートが見つかりません。');
        }
        if (!empty($member->skillsheet_2) && !Storage::disk('local')->exists($member->skillsheet_2)) {
            abort(404, 'スキルシートが見つかりません。');
        }
        if (!empty($member->skillsheet_3) && !Storage::disk('local')->exists($member->skillsheet_3)) {
            abort(404, 'スキルシートが見つかりません。');
        }

        $zip = new ZipArchive();
        //$path = storage_path('app/');
        $targetfiles = array(
                    $member->skillsheet_1, 
                    $member->skillsheet_2, 
                    $member->skillsheet_3
                );

        //処理対象のファイルの存在チェックを行い、存在するもののみのリストを作成
        $existsfiles = array();
        foreach($targetfiles as $targetfile){
            if (file_exists($localStoragePath.$targetfile) && is_file($localStoragePath.$targetfile)) {
                $existsfiles[] = $targetfile;
            }
        }

        // 存在するもののみのリストにしたがってzipを作成
        if (count($existsfiles) > 0) {
            $zip->open(storage_path('app/').'skillsheet.zip', ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
            foreach($existsfiles as $targetfile){
                $zip->addFile($localStoragePath.$targetfile, $targetfile);
            }
            $zip->close();
        } else {
            abort(404, 'ZIPファイルを作ることができませんでした');
        }

        // ダウンロード
        return response()->download($localStoragePath.'skillsheet.zip');
    }
}
