@extends('admin.common.layout')
@section('title', 'スキル一覧')
@section('content')
<?php
use App\Models\Ms_skill_categories;
?>
<div class="col-md-10">
  <div class="row">
    <div class="content-box-large">
      <div class="panel-heading">
        <div class="panel-title" style="font-size:20px">スキル一覧</div>
      </div>
      <div class="panel-body">
        {{-- info message --}}
        @if(\Session::has('custom_info_messages'))
          <div class="alert alert-info">
            <ul>
              <li>{{ \Session::get('custom_info_messages') }}</li>
            </ul>
          </div>
        @endif
        <fieldset>
          <legend>
            <div class="panel-title">検索</div>
          </legend>
          <form class="form-inline" role="form" method="GET" action="{{ url('/admin/skill/search') }}">
            <table class="table table-bordered">
              <tr>
                <th><label class="control-label">スキル名</label></th>
                <td><input type="text" class="form-control" name="name" value="{{ $data_query['name'] }}" /></td>
              </tr>
              <tr>
                <th><label class="control-label">ステータス</label></th>
                <td>
                  <input type="checkbox" name="delete_flag" id="eo_label" {{ empty($data_query['delete_flag']) ?: "checked" }} />
                  <label for="eo_label">
                    <font style="font-weight:normal;">表示のみ</font>
                  </label>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <input type="hidden" name="id" value="{{ $data_query['id'] }}">
                  <button type="submit" class="btn btn-primary btn-md col-xs-2 col-xs-offset-5">検索</button>
                </td>
              </tr>
            </table>
          </form>
        </fieldset>
        <br>
        <br>
        <legend><div class="panel-title">一覧</div></legend>
        <table class="table table-striped table-bordered">
          <thead>
            @if(count($skills)!=0)
              <tr>
                <th width="65%">スキル名</th>
                <th width="10%">表示順</th>
                <th width="10%">ステータス</th>
                <th width="15%"><!-- 一覧/編集/削除ボタン --></th>
              </tr>
            @endif
          </thead>
          <tbody>
            @foreach($skills as $skill)
              <tr>
                <!-- スキルカテゴリー名 -->
                <td>{{ $skill->name }}</td>
                <!-- 表示順 -->
                <td>{{ $skill->sort_order }}</td>
                <!-- ステータス -->
                <td>{{ $skill->delete_flag ? '非表示' : '表示' }}</td>
                <td nowrap>
                  <!-- 一覧/編集/削除/復活ボタン -->
                  <a href="/admin/skill/modify?id={{ $skill->id }}&skill_category_id={{ $skill->skill_category_id }}">
                    <button type="button" class="btn btn-warning btn-xs">編集</button>
                  </a>
                  @if(!$skill->delete_flag)
                    <a href="/admin/skill/delete?id={{ $skill->id }}&sort_order={{ $skill->sort_order }}&skill_category_id={{ $skill->skill_category_id }}" onClick="javascript:return confirm('本当に削除しますか？')">
                      <button type="button" class="btn btn-danger btn-xs">削除</button>
                    </a>
                  @else
                    @foreach(Ms_skill_categories::getSkillCategoryFlag($skill->skill_category_id) as $skillCategory)
                      @if(!$skillCategory->delete_flag)
                        <a href="/admin/skill/insert?id={{ $skill->id }}&skill_category_id={{ $skill->skill_category_id }}" onClick="javascript:return confirm('本当に復活させますか？')">
                          <button type="button" class="btn btn-primary btn-xs">復活</button>
                        </a>
                      @endif
                    @endforeach
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <div class="pull-right">
          {!! $skills->appends([
            'id'      => $data_query['id'],
            'name'    => $data_query['name'],
            'delete_flag' => $data_query['delete_flag'],
          ])->render() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
