<?php

namespace App\Http\Controllers\front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Libraries\CookieUtility as CkieUtil;
use App\Models\Tr_users;
use App\Models\Tr_items;
use App\Models\Tr_item_entries;
use Storage;
use Carbon\Carbon;
use DB;

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
/*
        dd($_FILES);

        dd($request->skillSheet);
        dd($request->skillSheet->realPath);
*/
        dd(shell_exec('file -bi '.escapeshellcmd($_FILES['skillSheet']['tmp_name'])));

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
        $file_name = 'skillsheetEN';
        $original_name = collect(explode('.', $file->getClientOriginalName()));
        $file_extension = $original_name->isEmpty() ? '' : $original_name->last();
        //$file_extension = !empty($file->guessExtension()) ? $file->guessExtension() : 'bin';

        $data = [
            'user_id' => $user->first()->id,
            'item_id' => $item->first()->id,
            'entry_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'skillsheet_upload' => $request->skillSheet != null,
            'skillsheet_filename' => null,
            'delete_flag' => 0,
            'delete_date' => null,
            'file_name' => $file_name,
            'file_extension' => $file_extension,
        ];

        // トランザクション
        $file_name = DB::transaction(function () use ($data) {
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

                return $insert_entry->skillsheet_filename;

            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        // ファイルをローカルに保存
        $file->move(storage_path('app'), $file_name);

        return redirect('/');
    }
}