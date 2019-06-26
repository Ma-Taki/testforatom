<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController;
use App\Libraries\AdminUtility as AdminUtil;
use App\Libraries\ModelUtility as mdlUtil;
use App\Models\Ms_skills;
use App\Models\Ms_skill_categories;
use App\Http\Requests\admin\SkillRegistRequest;
use DB;
use Carbon\Carbon;
use Log;
use Cache;

class SkillController extends AdminController
{
    /**
     * 検索一覧画面・処理
     * GET,POST:/admin/skill/search
     */
    public function searchSkill(Request $request){
        $data_query = [
            'id'            => $request->id,
            'name'          => $request->name ?: '',
            'delete_flag'   => $request->delete_flag ?: '',
        ];
        $query = Ms_skills::select('skills.*');
                                                    
        if(!empty($request->name)){
            //検索「スキル名」に入力したとき
            $query = $query->where('name', 'like', '%'.$request->name.'%');
        }
        if(!empty($request->delete_flag)){
            //検索「ステータス」にチェックしたとき
            $query = $query->where('delete_flag', false);
        }
        $skills = $query->where('skill_category_id', $request->id)->orderBy('sort_order', 'asc')->paginate(20);

        return view('admin.skill_list',compact('skills','data_query'));
    }

    /**
     * スキルカテゴリー変更時に表示順を切り替え
     * POST:/admin/skill/selectBox
     */
    public function ajaxSelectBox(Request $request){
        //スキルカテゴリーに対するスキル最大表示順を取得
        $sortMax = Ms_skills::where('skill_category_id', $request->id)->max('sort_order');
        $data = ['max' => $sortMax + 1];
        //エンコードして返却
        echo json_encode($data);
    }

    /**
     * 新規登録画面表示
     * GET:/admin/skill/input
     */
    public function showSkillInput(){
        $skillCategoryId = 0;
        //スキルカテゴリーを昇順で取得
        $skillCategories = Ms_skill_categories::orderBy('sort_order', 'asc')->get();
        foreach ($skillCategories as $key => $skillCategory) {
            if($key == 0){
                $skillCategoryId = $skillCategory->id;
            }
        }
        //最大表示順を取得
        $sortMax = Ms_skills::where('skill_category_id', $skillCategoryId)->max('sort_order');
        $sortMax = $sortMax + 1;

        return view('admin.skill_input', compact('skillCategories','sortMax'));
    }

    /**
     * 新規登録処理
     * POST:/admin/skill/input
     */
    public function insertSkill(SkillRegistRequest $request){

        $data = array(
                        'skill_category_id' => $request->skill_category_id,
                        'name'              => $request->skill_name,
                        'delete_flag'       => false,
                        'sort_order'        => $request->sort_order,
                    );
        
        //登録する親の表示ステータスを確認
        $skillCategory = Ms_skill_categories::where('id', $request->skill_category_id)->get()->first();

        //非表示のとき
        if($skillCategory->delete_flag){
            $data['delete_flag'] = true;
        }

        //表示順の最大値
        $sortMax = Ms_skills::where('skill_category_id', $request->skill_category_id)->max('sort_order');

        for($value = $data['sort_order']; $value <= $sortMax; $value++){
            //更新したい情報を取得
            $update_skill = Ms_skills::where('sort_order', $value)
                                    ->where('skill_category_id', $request->skill_category_id)
                                    ->first();

            $update_db[] = array(
                        'id'         => $update_skill->id,
                        'sort_order' => $value + 1,
                    );
        }

        //挿入処理
        DB::transaction(function () use ($data) {
            try {
                //テーブルに挿入
                $insert_skill = new Ms_skills;
                $insert_skill->skill_category_id = $data['skill_category_id'];
                $insert_skill->name              = $data['name'];
                $insert_skill->delete_flag       = $data['delete_flag'];
                $insert_skill->sort_order        = $data['sort_order'];
                $insert_skill->save();
            } catch (Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });

        //更新処理
        if(!empty($update_db)){
            foreach($update_db as $update){
                //トランザクション
                DB::transaction(function () use ($update) {
                    try {
                        Ms_skills::where('id', $update['id'])->update([
                            'sort_order' => $update['sort_order']
                        ]); 
                    } catch (\Exception $e) {
                        Log::error($e);
                        abort(400, 'トランザクションが異常終了しました。');
                    }
                });
            }
        }
        return redirect('/admin/skill/search?id='.$request->skill_category_id)
                ->with('custom_info_messages','スキル登録は正常に終了しました。');
    }

    /**
     * 編集画面表示
     * GET:/admin/skill/modify
     */
    public function showSkillModify(Request $request){
        $skill = Ms_skills::where('id', $request->id)->get()->first();
        $sortMax = Ms_skills::where('skill_category_id', $request->skill_category_id)->max('sort_order');

        if (empty($skill)) {
            abort(404, '指定されたスキルは存在しません。');
        }
        return view('admin.skill_modify', compact('skill','sortMax'));
    }

    // /**
    //  * 更新処理
    //  * POST:/admin/skill/modify
    //  */
    public function updateSkill(SkillRegistRequest $request){
        
        $update_db[] = array(
                    'id'        => $request->id,
                    'name'      => $request->skill_name,
                    'sort_order'=> $request->sort_order,
                );

        //編集対象を取得
        $skill = Ms_skills::where('id', $update_db[0]['id'])->get()->first();

        //表示順を変更したいとき
        if($update_db[0]['sort_order'] !== $skill->sort_order){
            if($update_db[0]['sort_order'] < $skill->sort_order){
                //表示順をあげたいとき
                for($value = $update_db[0]['sort_order']; $value < $skill->sort_order; $value++){
                    //表示順に紐づいた情報を取得
                    $update_skill = Ms_skills::where('sort_order', $value)
                                            ->where('skill_category_id', $request->skill_category_id)
                                            ->get()
                                            ->first();
                    $update_db[] = array(
                                        'id'          => $update_skill->id,
                                        'name'        => $update_skill->name,
                                        'sort_order'  => $value + 1,
                                    );
                }
            }else{
                //表示順をさげたいとき
                $sortNum = $skill->sort_order + 1;
                for($value = $sortNum; $value <= $update_db[0]['sort_order']; $value++){
                    //表示順に紐づいた情報を取得
                    $update_skill = Ms_skills::where('sort_order', $value)
                                            ->where('skill_category_id', $request->skill_category_id)
                                            ->get()
                                            ->first();
                    $update_db[] = array(
                                        'id'          => $update_skill->id,
                                        'name'        => $update_skill->name,
                                        'sort_order'  => $value - 1,
                                    );
                }
            }
        }

        //更新処理
        foreach ($update_db as $update) {
            //トランザクション
            DB::transaction(function () use ($update) {
             try {
                    Ms_skills::where('id', $update['id'])->update([
                        'name'       => $update['name'],
                        'sort_order' => $update['sort_order'],
                    ]);
                } catch (\Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });
        }

        return redirect('/admin/skill/search?id='.$request->skill_category_id)
                ->with('custom_info_messages','スキル更新は正常に終了しました。');
    }

    /**
     * 論理削除処理
     * GET:/admin/skill/delete
     */
    public function deleteSkill(Request $request){
        //表示順の最大値
        $sortMax = Ms_skills::where('skill_category_id', $request->skill_category_id)->max('sort_order');

        $update_db[] = array(
                    'id'          => $request->id,
                    'sort_order'  => $sortMax,
                    'delete_flag' => true,
                );
        $sortMin = $request->sort_order + 1;
        for($value = $sortMin; $value <= $update_db[0]['sort_order']; $value++){
            //表示順に紐づいた情報を取得
            $update_skill = Ms_skills::where('sort_order', $value)
                                    ->where('skill_category_id', $request->skill_category_id)
                                    ->get()
                                    ->first();

            $update_db[] = array(
                                'id'          => $update_skill->id,
                                'sort_order'  => $value - 1,
                                'delete_flag' => $update_skill->delete_flag,
                            );
        }

        //更新処理
        foreach ($update_db as $update) {
            //トランザクション
            DB::transaction(function () use ($update) {
             try {
                    Ms_skills::where('id', $update['id'])->update([
                        'sort_order'  => $update['sort_order'],
                        'delete_flag' => $update['delete_flag'],
                    ]);
                } catch (\Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });
        }
        return redirect('/admin/skill/search?id='.$request->skill_category_id)
                ->with('custom_info_messages','スキル削除は正常に終了しました。');
    }

     /**
     * 論理削除から復活処理
     * GET:/admin/skill/insert
     */
    public function insertAgainSkill(Request $request){
        $update = array(
                        'id'          => $request->id,
                        'delete_flag' => false,
                    );
        
        DB::transaction(function () use ($update) {
            try {
                Ms_skills::where('id', $update['id'])->update([
                    'delete_flag' => $update['delete_flag'],
                ]);
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });
        return redirect('/admin/skill/search?id='.$request->skill_category_id)
                ->with('custom_info_messages','復活処理は正常に終了しました。');
    }
}
