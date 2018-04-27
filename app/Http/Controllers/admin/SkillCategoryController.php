<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController;
use App\Libraries\AdminUtility as AdminUtil;
use App\Libraries\ModelUtility as mdlUtil;
use App\Models\Ms_skill_categories;
use App\Models\Ms_skills;
use App\Http\Requests\admin\SkillCategoryRegistRequest;
use DB;
use Carbon\Carbon;
use Log;
use Cache;

class SkillCategoryController extends AdminController
{
    /**
     * 検索一覧画面・処理
     * GET,POST:/admin/skill-category/search
     */
    public function searchSkill(Request $request){
        $data_query = [
            'name'        => $request->name ?: '',
            'delete_flag' => $request->delete_flag ?: '',
        ];
        $query = Ms_skill_categories::select('skill_categories.*');
                                                    
        if(!empty($request->name)){
            //検索「スキルカテゴリー名」に入力したとき
            $query = $query->where('name', 'like', '%'.$request->name.'%');
        }
        if(!empty($request->delete_flag)){
            //検索「ステータス」にチェックしたとき
            $query = $query->where('delete_flag', false);
        }
        $skillCategories = $query->orderBy('sort_order', 'asc')->paginate(20);

        return view('admin.skill_category_list',compact('skillCategories','data_query'));
    }

    /**
     * 新規登録画面表示
     * GET:/admin/skill-category/input
     */
    public function showSkillInput(){
        //最大表示順値を取得
        $sortMax = Ms_skill_categories::max('sort_order');
        $sortMax = $sortMax + 1;

        return view('admin.skill_category_input', compact('sortMax'));
    }

    /**
     * 新規登録処理
     * POST:/admin/skill-category/input
     */
    public function insertSkill(SkillCategoryRegistRequest $request){
        
        $data = array(
                        'name'        => $request->skillCategory_name,
                        'delete_flag' => false,
                        'sort_order'  => $request->sort_order,
                    );

        //表示順の最大値
        $sortMax = Ms_skill_categories::max('sort_order');
        for($value = $data['sort_order']; $value <= $sortMax; $value++){
            //更新したい情報を取得
            $update_skillCategory = Ms_skill_categories::where('sort_order', $value)->first();
            $update_db[] = array(
                        'id'         => $update_skillCategory->id,
                        'sort_order' => $value + 1,
                    );
        }

        //挿入処理
        DB::transaction(function () use ($data) {
            try {
                //テーブルに挿入
                $insert_skillCategory               = new Ms_skill_categories;
                $insert_skillCategory->name         = $data['name'];
                $insert_skillCategory->delete_flag  = $data['delete_flag'];
                $insert_skillCategory->sort_order   = $data['sort_order'];
                $insert_skillCategory->save();
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
                        Ms_skill_categories::where('id', $update['id'])->update([
                            'sort_order' => $update['sort_order']
                        ]); 
                    } catch (\Exception $e) {
                        Log::error($e);
                        abort(400, 'トランザクションが異常終了しました。');
                    }
                });
            }
        }
        return redirect('/admin/skill-category/search')->with('custom_info_messages','スキルカテゴリー登録は正常に終了しました。');
    }

    /**
     * 編集画面表示
     * GET:/admin/skill-category/modify
     */
    public function showSkillModify(Request $request){
        $skillCategory = Ms_skill_categories::where('id', $request->id)->get()->first();
        $sortMax = Ms_skill_categories::max('sort_order');

        if (empty($skillCategory)) {
            abort(404, '指定されたスキルカテゴリーは存在しません。');
        }
        return view('admin.skill_category_modify', compact('skillCategory','sortMax'));
    }

    /**
     * 更新処理
     * POST:/admin/skill-category/modify
     */
    public function updateSkill(SkillCategoryRegistRequest $request){
        
        $update_db[] = array(
                    'id'        => $request->id,
                    'name'      => $request->skillCategory_name,
                    'sort_order'=> $request->sort_order,
                );

        //編集対象を取得
        $skillCategory = Ms_skill_categories::where('id', $update_db[0]['id'])->get()->first();

        //表示順を変更したいとき
        if($update_db[0]['sort_order'] !== $skillCategory->sort_order){
            if($update_db[0]['sort_order'] < $skillCategory->sort_order){
                //表示順をあげたいとき
                for($value = $update_db[0]['sort_order']; $value < $skillCategory->sort_order; $value++){
                    //表示順に紐づいた情報を取得
                    $update_skillCategory = Ms_skill_categories::where('sort_order', $value)->first();
                    $update_db[] = array(
                                        'id'          => $update_skillCategory->id,
                                        'name'        => $update_skillCategory->name,
                                        'sort_order'  => $value + 1,
                                    );
                }
            }else{
                //表示順をさげたいとき
                $sortNum = $skillCategory->sort_order + 1;
                for($value = $sortNum; $value <= $update_db[0]['sort_order']; $value++){
                    //表示順に紐づいた情報を取得
                    $update_skillCategory = Ms_skill_categories::where('sort_order', $value)->get()->first();
                    $update_db[] = array(
                                        'id'          => $update_skillCategory->id,
                                        'name'        => $update_skillCategory->name,
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
                    Ms_skill_categories::where('id', $update['id'])->update([
                        'name'       => $update['name'],
                        'sort_order' => $update['sort_order'],
                    ]);
                } catch (\Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });
        }
        return redirect('/admin/skill-category/search')->with('custom_info_messages','スキルカテゴリー更新は正常に終了しました。');
    }

    /**
     * 論理削除処理
     * GET:/admin/skill/delete
     */
    public function deleteSkill(Request $request){
        //*****************
        //スキルカテゴリー
        //*****************
        //表示順の最大値
        $sortMax = Ms_skill_categories::max('sort_order');
        $skillCategoryUpdate_db[] = array(
                    'id'          => $request->id,
                    'sort_order'  => $sortMax,
                    'delete_flag' => true,
                );
        $sortMin = $request->sort_order + 1;
        for($value = $sortMin; $value <= $skillCategoryUpdate_db[0]['sort_order']; $value++){
            //表示順に紐づいた情報を取得
            $update_skillCategory = Ms_skill_categories::where('sort_order', $value)->get()->first();

            $skillCategoryUpdate_db[] = array(
                                'id'          => $update_skillCategory->id,
                                'sort_order'  => $value - 1,
                                'delete_flag' => $update_skillCategory->delete_flag,
                            );
        }

        //*****************
        //スキル
        //*****************
        $update_skills = Ms_skills::where('skill_category_id', $request->id)->get();

        foreach ($update_skills as $update_skill) {
            $skillUpdate_db[] = array(
                    'id'          => $update_skill->id,
                    'delete_flag' => true,
                );
        }

        //スキルカテゴリー更新処理
        foreach ($skillCategoryUpdate_db as $update) {
            //トランザクション
            DB::transaction(function () use ($update) {
             try {
                    Ms_skill_categories::where('id', $update['id'])->update([
                        'sort_order'  => $update['sort_order'],
                        'delete_flag' => $update['delete_flag'],
                    ]);
                } catch (\Exception $e) {
                    Log::error($e);
                    abort(400, 'トランザクションが異常終了しました。');
                }
            });
        }
        //スキル更新処理
        if(!empty($skillUpdate_db)){
            foreach ($skillUpdate_db as $update) {
                //トランザクション
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
            }
        }
        return redirect('/admin/skill-category/search')->with('custom_info_messages','スキルカテゴリー削除は正常に終了しました。');
    }


     /**
     * 論理削除から復活処理
     * GET:/admin/skill-category/insert
     */
    public function insertAgainSkill(Request $request){
        //*****************
        //スキルカテゴリー
        //*****************
        $update = array(
                        'id'          => $request->id,
                        'delete_flag' => false,
                    );
        //*****************
        //スキル
        //*****************
        $update_skills = Ms_skills::where('skill_category_id', $request->id)->get();

        foreach ($update_skills as $update_skill) {
            $skillUpdate_db[] = array(
                    'id'          => $update_skill->id,
                    'delete_flag' => false,
                );
        }

        //スキルカテゴリー
        DB::transaction(function () use ($update) {
            try {
                Ms_skill_categories::where('id', $update['id'])->update([
                    'delete_flag' => $update['delete_flag'],
                ]);
            } catch (\Exception $e) {
                Log::error($e);
                abort(400, 'トランザクションが異常終了しました。');
            }
        });
        //スキル
        if(!empty($skillUpdate_db)){
            foreach ($skillUpdate_db as $update) {
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
            }
        }

        return redirect('/admin/skill-category/search')->with('custom_info_messages','復活処理は正常に終了しました。');
    }
}
