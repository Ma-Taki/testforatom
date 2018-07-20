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
     * エントリー処理 & エントリー完了画面を表示する
     * POST:/entry
     **/
    public function store(Request $request){

        try {
            $item = parent::getItemById($request->item_id);
        } catch (ModelNotFoundException $e) {
            abort(404, '指定された案件情報は掲載期限が過ぎているか、存在しません。');
        }

        // ミドルウェアで存在をチェック済み
        $user = parent::getUserById(CkieUtil::get(CkieUtil::COOKIE_NAME_USER_ID));

        //エントリー重複チェック
        $entry = Tr_item_entries::where('user_id', $user->id)
                                ->where('item_id', $item->id)
                                ->enable()
                                ->get();

        if ($entry->count() > 0) {
            abort(400, 'すでにエントリー済みです。');
        }

        $skillFiles = array(
                        $request->skillsheet_filename_first, 
                        $request->skillsheet_filename_second, 
                        $request->skillsheet_filename_third
                    );
        $files = array_filter($skillFiles);
        $filesName = array(
                        "skillsheet_filename_first", 
                        "skillsheet_filename_second", 
                        "skillsheet_filename_third"
                    );

        $original_name = null;
        $file_extension = array();
        foreach ($files as $key => $file) {
    
            // ▽▽▽ スキルシートアップロード時のバリデーション ▽▽▽
            if(!empty($file)) {
                $custom_error_messages = [];
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
                $mime_type = shell_exec('file -b --mime '.escapeshellcmd($_FILES[$filesName[$key]]['tmp_name']));
                $mime_type = trim($mime_type);
                $mime_type = collect(explode(';', $mime_type));

                if ($mime_type->isEmpty()
                    || (!$mime_type->isEmpty()
                        && !in_array($mime_type->first(), FrntUtil::FILE_UPLOAD_RULE['allowedTypes']))) {
                        array_push($custom_error_messages, 'スキルシートのファイル形式が正しくありません。');
                }

                if (!empty($custom_error_messages)) {
                    // フラッシュセッションにエラーメッセージを保存
                    \Session::flash('custom_error_messages', $custom_error_messages);
                    return back()->withInput();
                }

                $file_extension[] = $original_name->last();

            }else{

                $file_extension[] = null;
            }
        }
        // △△△ スキルシートアップロード時のバリデーション △△△

        $data = [
            'user_id'                    => $user->id,
            'item_id'                    => $item->id,
            'entry_date'                 => Carbon::now()->format('Y-m-d H:i:s'),
            'skillsheet_upload'          => !empty($files),
            'skillsheet_filename_first'  => null,
            'skillsheet_filename_second' => null,
            'skillsheet_filename_third'  => null,
            'delete_flag'                => 0,
            'delete_date'                => null,
            'file_name'                  => 'skillsheetEN',
            'file_extension'             => $file_extension,
        ];
        
        // トランザクション
        $db_return_data = DB::transaction(function () use ($data, $files) {
            try {
                // エントリーテーブルにインサート
                $insert_entry                               = new Tr_item_entries;
                $insert_entry->user_id                      = $data['user_id'];
                $insert_entry->item_id                      = $data['item_id'];
                $insert_entry->entry_date                   = $data['entry_date'];
                $insert_entry->skillsheet_upload            = $data['skillsheet_upload'];
                $insert_entry->skillsheet_filename_first    = $data['skillsheet_filename_first'];
                $insert_entry->skillsheet_filename_second   = $data['skillsheet_filename_second'];
                $insert_entry->skillsheet_filename_third    = $data['skillsheet_filename_third'];
                $insert_entry->delete_flag                  = $data['delete_flag'];
                $insert_entry->delete_date                  = $data['delete_date'];
                $insert_entry->save();

                // 採番されたIDでファイル名を生成、アップデート
                $name = $data['file_name'].$insert_entry->id;
                if ($insert_entry->skillsheet_upload) {
                    foreach ($files as $key => $file) {
                        switch ($key) {
                        case 0:
                            if(!empty($file)) {
                                $insert_entry->skillsheet_filename_first = $name.'_no1.'.$data['file_extension'][$key];
                                $file_name[] = $insert_entry->skillsheet_filename_first;
                            }else{
                                $file_name[] = null;
                            }
                            break;
                        case 1:
                            if(!empty($file)) {
                                $insert_entry->skillsheet_filename_second = $name.'_no2.'.$data['file_extension'][$key];
                                $file_name[] = $insert_entry->skillsheet_filename_second;
                            }else{
                                $file_name[] = null;
                            }
                            break;
                        case 2:
                            if(!empty($file)) {
                                $insert_entry->skillsheet_filename_third = $name.'_no3.'.$data['file_extension'][$key];
                                $file_name[] = $insert_entry->skillsheet_filename_third;
                            }else{
                                $file_name[] = null;
                            }
                            break;
                        }
                    }                  
                    $insert_entry->save();
                }
                return [
                    'file_name' => $file_name,
                    'entry' => $insert_entry,
                ];
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        // ファイルをローカルに保存
        foreach ($files as $key => $file) {
            if(!empty($file)) {
                $file->move(storage_path('app'), $db_return_data['file_name'][$key]);
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

        return view('front.entry_complete', ['entry' => $db_return_data['entry'], 'item' => $item]);
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
