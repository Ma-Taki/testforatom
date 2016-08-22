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

        //dd($request);

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

        $data = [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'entry_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'skillsheet_upload' => $request->skillSheet != null,
            'skillsheet_filename' => $file_name,
            'delete_flag' => 0,
            'delete_date' => null,
        ];

        // トランザクション
        DB::transaction(function () use ($data) {
            try {
                // エントリーテーブルにインサート
                Tr_item_entries::where('id', $entry_id)->update([
                ]);
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });


        Storage::disk('local')->put('skillsheetEN124.xls', $file);

        return redirect('/');
    }
}
