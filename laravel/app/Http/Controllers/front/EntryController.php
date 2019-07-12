<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\FrontController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Libraries\{CookieUtility as CkieUtil, FrontUtility as FrntUtil};
use App\Models\{Tr_users, Tr_items, Tr_item_entries};
use Storage;
use Carbon\Carbon;
use DB;
use Mail;
use Log;

class EntryController extends FrontController
{
    /**
     * エントリー画面を表示する
     * GET:/entry
     **/
    public function index(Request $request){

        try {
            $item = parent::getItemById($request->id);
        } catch (ModelNotFoundException $e) {
            abort(404, '指定された案件情報は掲載期限が過ぎているか、存在しません。');
        }

        // ミドルウェアで存在をチェック済み
        $user = parent::getUserById(CkieUtil::get(CkieUtil::COOKIE_NAME_USER_ID));

        return view('front.entry', compact('user', 'item'));
    }

    /**
     * 会員登録完了画面表示
     * POST:/entry/completion
     */
    public function ajaxRegistComp(Request $request) {
        return view('front.entry_complete')->with([
                                                    'entry_id' => $request->entry_id,
                                                    'item_id' => $request->item_id,
                                                    'item_name' => $request->item_name,
                                                    'entry_skillsheet_upload' => $request->entry_skillsheet_upload,
                                                ]);
    }

    /**
     * エントリー処理 & エントリー完了画面を表示する
     * POST:/entry
     **/
    public function store(Request $request){
        $custom_error_messages = [];
        try {
            $item = parent::getItemById($request->item_id);
        } catch (ModelNotFoundException $e) {
            array_push($custom_error_messages, '指定された案件情報は掲載期限が過ぎているか、存在しません。');
        }

        // ミドルウェアで存在をチェック済み
        $user = parent::getUserById(CkieUtil::get(CkieUtil::COOKIE_NAME_USER_ID));

        //エントリー重複チェック
        $entry = Tr_item_entries::where('user_id', $user->id)
                                ->where('item_id', $item->id)
                                ->enable()
                                ->get();

        if ($entry->count() > 0) {
            array_push($custom_error_messages, 'すでにエントリー済みです。');
        }

        //-------------------------------------------------------------------
        //スキルシートのチェック
        //-------------------------------------------------------------------
        //バリデーションエラーでアップロードを何度もやり直していることを考慮
        //登録ボタンを押すたびに新しい配列が作成されるため最新の配列のみチェックする
        $skillsheet_num = 'skillsheet_'.$request->uploadCount;
        $file_extension = array();
        $skillsheet_upload_flag = 0;

        //未選択のとき
        if($request->file_type == ''){
            array_push($custom_error_messages, 'ファイルの登録方法が選択されていません。');
        }

        //「ドラッグ&ドロップ」または「ファイル選択」のとき
        if($request->file_type == 'entry_dd' || $request->file_type == 'entry_fe'){
            $fileCount = 0;

            foreach($request->$skillsheet_num as $key => $file) {
                if(!empty($file)) {
                    // サイズチェック
                    if ($file->getClientSize() > FrntUtil::FILE_UPLOAD_RULE['maximumSize']) {
                        array_push($custom_error_messages, 'スキルシートが1MBを超えています。');
                    }
                    // 拡張子チェック
                    $original_name = collect(explode('.', $file->getClientOriginalName()));
                    if ($original_name->count() != 2
                        || !in_array($original_name->last(), FrntUtil::FILE_UPLOAD_RULE['allowedExtensions'])) {
                            array_push($custom_error_messages, 'スキルシートの拡張子が正しくありません。');
                    }
                    // mimeTypeチェック
                    $mime_type = shell_exec('file -b --mime '.escapeshellcmd($_FILES[$skillsheet_num]['tmp_name'][$key]));
                    $mime_type = trim($mime_type);
                    $mime_type = collect(explode(';', $mime_type));

                    if ($mime_type->isEmpty() || (!$mime_type->isEmpty() && !in_array($mime_type->first(), FrntUtil::FILE_UPLOAD_RULE['allowedTypes']))) {
                            array_push($custom_error_messages, 'スキルシートのファイル形式が正しくありません。');
                    }
                    $file_extension[$key] = $original_name->last();
                    $skillsheet_upload_flag = 1;
                }else{
                    $file_extension[$key] = '';
                    if(1 < $fileCount){
                        array_push($custom_error_messages, 'スキルシートがアップロードされていません。');
                    }
                    $fileCount++;
                }
            }  
        }
       
        if (!empty($custom_error_messages)) {
            $data_content = ['custom_error_messages' => $custom_error_messages];
            echo json_encode($data_content);
        }

        //エラーがないとき
        if(empty($custom_error_messages)){
            if($request->file_type == 'entry_fma'){
                $file_extension[] = null;
            }

            $db_data = [
                'user_id'           => $user->id,
                'item_id'           => $item->id,
                'entry_date'        => Carbon::now()->format('Y-m-d H:i:s'),
                'skillsheet_upload' => $skillsheet_upload_flag,
                'skillsheet_1'      => null,
                'skillsheet_2'      => null,
                'skillsheet_3'      => null,
                'delete_flag'       => 0,
                'delete_date'       => null,
                'file_name'         => 'skillsheetEN',
                'file_extension'    => $file_extension,
            ];

            $skillsheet_list = $request->$skillsheet_num;
            // トランザクション
            $db_return_data = DB::transaction(function () use ($db_data, $skillsheet_list) {
                try {
                    // エントリーテーブルにインサート
                    $entry                    = new Tr_item_entries;
                    $entry->user_id           = $db_data['user_id'];
                    $entry->item_id           = $db_data['item_id'];
                    $entry->entry_date        = $db_data['entry_date'];
                    $entry->skillsheet_upload = $db_data['skillsheet_upload'];
                    $entry->skillsheet_1      = $db_data['skillsheet_1'];
                    $entry->skillsheet_2      = $db_data['skillsheet_2'];
                    $entry->skillsheet_3      = $db_data['skillsheet_3'];
                    $entry->delete_flag       = $db_data['delete_flag'];
                    $entry->delete_date       = $db_data['delete_date'];
                    $entry->save();

                    // 採番されたIDでファイル名を生成、アップデート
                    if ($entry->skillsheet_upload) {
                        $name = $db_data['file_name'].$entry->id;
                        foreach ($skillsheet_list as $key => $file) {
                            if(!empty($file)) {
                                $key_plus = $key + 1;
                                $sheet_num = 'skillsheet_'.$key_plus;
                                $entry->$sheet_num = $name.'_no'.$key_plus.'.'.$db_data['file_extension'][$key];
                                $file_name[$key] = $entry->$sheet_num;
                            }else{
                                $file_name[$key] = null;
                            }
                        }
                        $entry->save();
                    }else{
                        $file_name = null;
                    }

                    return [
                        'file_name' => $file_name,
                        'entry' => $entry,
                    ];
                } catch (Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });

            // ファイルをローカルに保存
            if(isset($skillsheet_list) && count($skillsheet_list) > 0){
                foreach ($skillsheet_list as $key => $file) {
                    if(!empty($file)) {
                        $file->move(storage_path('app'), $db_return_data['file_name'][$key]);
                    }
                }
            }

            // メール送信
            $data_mail = [
                'admin_mail_addresses' => 'entry@engineer-route.com',
                'entry_id'             => $db_return_data['entry']->id,
                'item_id'              => $item->id,
                'item_name'            => $item->name,
                'item_biz_category'    => $item->bizCategorie->name,
                'user_name'            => $user->last_name.' ' .$user->first_name,
                'user_mail_address'    => $user->mail,
            ];
            $frntUtil = new FrntUtil();
            Mail::send('front.emails.item_entry', $data_mail, function ($message) use ($data_mail, $frntUtil) {
                $message->from($frntUtil->user_entry_mail_from, $frntUtil->user_entry_mail_from_name);
                $message->to($data_mail['user_mail_address']);
                if (!empty($frntUtil->user_entry_mail_to_bcc)) {
                    $message->bcc($frntUtil->user_entry_mail_to_bcc);
                }
                $message->subject(FrntUtil::USER_ENTRY_MAIL_TITLE);
            });

            //エントリーID・案件ID・案件名・スキルシートアップロードフラグ・
            $data_content = ['url' => '/entry/completion?entry_id='.$db_return_data['entry']->id.'&item_id='.$item->id.'&item_name='.$item->name.'&entry_skillsheet_upload='.$db_return_data['entry']->skillsheet_upload];
            
            //エンコードして返却
            echo json_encode($data_content);
        }
    }

    /**
     * スキルシートのダウンロードを行う
     * GET:/entry/download
     **/
    public function download(){
        // エントリーシートの存在チェック
        if (!Storage::disk('public')->exists('skillsheet.zip')) {
            Log::critical('['.__METHOD__ .'#'.__LINE__.'] skillsheet not found');
            abort(404, 'システムエラーが発生致しました。恐れ入りますが、しばらく時間をおいてから再度アクセスしてください。');
        }
        return response()->download(storage_path('app/public').'/skillsheet.zip');
    }
}
