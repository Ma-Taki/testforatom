<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Libraries\CookieUtility as CkieUtil;
use App\Libraries\FrontUtility as FrntUtil;
use App\Models\Tr_users;
use App\Models\Tr_items;
use App\Models\Tr_item_entries;
use Storage;
use Carbon\Carbon;
use DB;
use Mail;

class EntryController extends Controller
{
    /**
     * エントリー画面を表示する
     * GET:/entry
     **/
    public function index(Request $request){

        $user_id = CkieUtil::get(CkieUtil::COOKIE_NAME_USER_ID);

        // 未ログインはログイン画面へリダイレクト
        if (empty($user_id)) return redirect('/login');

        $user = Tr_users::where('id', $user_id)
                        ->enable()
                        ->get()
                        ->first();

        $item_id = $request->id;
        $item = Tr_items::where('id', $item_id)->get()->first();

        return view('front.entry', compact('user', 'item'));
    }

    /**
     * エントリー処理 & エントリー完了画面を表示する
     * POST:/entry
     **/
    public function store(Request $request){

        // 案件の存在、エントリー期間中かチェック
        $item = Tr_items::where('id', $request->item_id)
                        ->entryPossible()
                        ->get();
        if ($item->isEmpty()) {
            abort(404);
        }

        // ユーザの存在チェック
        $user = Tr_users::where('id', CkieUtil::get(CkieUtil::COOKIE_NAME_USER_ID))
                        ->enable()
                        ->get();
        if ($user->isEmpty()) {
            abort(404);
        }

        //エントリー重複チェック
        $entry = Tr_item_entries::where('user_id', $user->first()->id)
                                ->where('item_id', $item->first()->id)
                                ->enable()
                                ->get();
        if ($entry->count() > 0) {
            abort(400);
        }

        $file = !empty($request->skillSheet) ? $request->skillSheet : null;
        $original_name = null;

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
            $mime_type = shell_exec('file -bi '.escapeshellcmd($_FILES['skillSheet']['tmp_name']));
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
        }
        // △△△ スキルシートアップロード時のバリデーション △△△

        $data = [
            'user_id' => $user->first()->id,
            'item_id' => $item->first()->id,
            'entry_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'skillsheet_upload' => !empty($file),
            'skillsheet_filename' => null,
            'delete_flag' => 0,
            'delete_date' => null,
            'file_name' => 'skillsheetEN',
            'file_extension' => !is_null($original_name) ? $original_name->last() : '',
        ];

        // トランザクション
        $db_return_data = DB::transaction(function () use ($data) {
            try {
                // エントリーテーブルにインサート
                $insert_entry = new Tr_item_entries;
                $insert_entry->user_id = $data['user_id'];
                $insert_entry->item_id = $data['item_id'];
                $insert_entry->entry_date = $data['entry_date'];
                $insert_entry->skillsheet_upload = $data['skillsheet_upload'];
                $insert_entry->skillsheet_filename = $data['skillsheet_filename'];
                $insert_entry->delete_flag = $data['delete_flag'];
                $insert_entry->delete_date = $data['delete_date'];
                $insert_entry->save();

                // 採番されたIDでファイル名を生成、アップデート
                if ($insert_entry->skillsheet_upload) {
                    $insert_entry->skillsheet_filename =
                        $data['file_name'].$insert_entry->id.'.'.$data['file_extension'];
                    $insert_entry->save();
                }
                return [
                    'file_name' => $insert_entry->skillsheet_filename,
                    'entry' => $insert_entry,
                ];


            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        // ファイルをローカルに保存
        if(!empty($file)) {
            $file->move(storage_path('app'), $db_return_data['file_name']);
        }

        // メール送信
        $data_mail = [
            'admin_mail_addresses' => 'entry@engineer-route.com',
            'entry_id' => $db_return_data['entry']->id,
            'item_id' => $item->first()->id,
            'item_name' => $item->first()->name,
            'item_biz_category' => $item->bizCategorie->name,
            'user_mail_address' => $user->first()->mail,
        ];
        $frntUtil = new FrntUtil();
        Mail::send('front.emails.item_entry', $data_mail, function ($message) use ($data_mail, $frntUtil) {
            $message->from($frntUtil->user_entry_mail_from, $frntUtil->user_entry_mail_from_name);
            //$message->to($data_mail['user_mail_address'], $data_mail['user_mail_address']);
            // 開発も含めて、全部自分に送られてくるようにしとく
            $message->to($frontUtil->user_entry_mail_to, $frontUtil->user_entry_mail_to_name);
            $message->subject(FrntUtil::USER_ENTRY_MAIL_TITLE);
        });

        return view('front.entry_complete', [
            'entry' => $db_return_data['entry'],
            'item' => $item->first(),
        ]);
    }
}
