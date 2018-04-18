@extends('admin.common.layout')
@section('title', 'カテゴリー一覧')
@section('content')
<?php
use App\Libraries\AdminUtility as admnUtil;
use App\Libraries\ModelUtility as mdlUtil;
use App\Models\Tr_search_categories;
?>
<div class="col-md-10">
  <div class="row">
    <div class="content-box-large">
      <div class="panel-heading">
        <div class="panel-title" style="font-size:20px">カテゴリー一覧</div>
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
          <form class="form-inline" role="form" method="GET" action="{{ url('/admin/category/search') }}">
            <table class="table table-bordered">
              <tr>
                <th><label class="control-label">親または子カテゴリー名</label></th>
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
                  <button type="submit" class="btn btn-primary btn-md col-xs-2 col-xs-offset-5">検索</button>
                </td>
              </tr>
            </table>
          </form>
        </fieldset>
        <br>
        <br>
        <legend><div class="panel-title">一覧</div></legend>
        <p>親ステータス・子ステータスの表示/非表示は、案件登録・編集・カテゴリーのトップページ表示管理画面のチェック項目の表示または非表示</p>
        <table class="table table-striped table-bordered">
          <thead>
            @if(count($categoryList)!=0)
              <tr>
                <th width="20%">親カテゴリー名</th>
                <th width="5%">親表示順</th>
                <th width="8%">親ステータス</th>
                <th width="20%">子カテゴリー名</th>
                <th width="5%">子表示順</th>
                <th width="8%">子ステータス</th>
                <th width="24%">ページタイトル</th>
                <th width="10%"><!-- 詳細/編集/削除ボタン --></th>
              </tr>
            @endif
          </thead>
          <tbody>
            @foreach($categoryList as $category)
  				    <tr>
                @if(empty($category->parent_id))
                  <!-- 親のとき -->
                  <!-- 親カテゴリー名 -->
                  <td>{{ $category->name }}</td>
                  <!-- 親表示順 -->
                  <td>{{ $category->parent_sort }}</td>
                  <!-- 親ステータス -->
                  <td>{{ $category->delete_flag ? '非表示' : '表示' }} </td>
                  <!-- 子カテゴリー名 -->
                  <td> - </td>
                  <!-- 子表示順 -->
                  <td> - </td>
                  <!-- 子ステータス -->
                  <td> - </td>
                  <!-- ページタイトル -->
                  <td>{{ $category->page_title }}</td>
                @else
                  <!-- 子のとき -->
                  <!-- 親カテゴリー名 -->
                  <td> - </td>
                  <!-- 親表示順 -->
                  <td> - </td>
                  <!-- 親ステータス -->
                  <td> - </td>
                  <!-- 子カテゴリー名 -->
                  <td>{{ $category->name }}</td>
                  <!-- 子表示順 -->
                  <td>{{ $category->child_sort }}</td>
                  <!-- 子ステータス -->
                  <td>{{ $category->delete_flag ? '非表示' : '表示' }}</td>
                  <!-- ページタイトル -->
                  <td>{{ $category->page_title }}</td>
                @endif
                <td nowrap>
                  <!-- 詳細/編集/削除/復活ボタン -->
                  <a href="/admin/category/detail?id={{ $category->id }}&parent_id={{ $category->parent_id }}">
                    <button type="button" class="btn btn-info btn-xs">詳細</button>
                  </a>
                  <a href="/admin/category/modify?id={{ $category->id }}">
                    <button type="button" class="btn btn-warning btn-xs">編集</button>
                  </a>
                  @if(!$category->delete_flag)
                    <a href="/admin/category/delete?id={{ $category->id }}&parent_id={{ $category->parent_id }}&parent_sort={{ $category->parent_sort }}&child_sort={{ $category->child_sort }}" onClick="javascript:return confirm('本当に削除しますか？')">
                      <button type="button" class="btn btn-danger btn-xs">削除</button>
                    </a>
                  @else
                    @if(empty($category->parent_id))
                      <!-- 親のとき -->
                      <a href="/admin/category/insert?id={{ $category->id }}&parent_id={{ $category->parent_id }}" onClick="javascript:return confirm('本当に復活させますか？')">
                        <button type="button" class="btn btn-primary btn-xs">復活</button>
                      </a>
                    @else
                      <!-- 子どもとき 親が非表示のときは子も非表示 -->
                      @foreach(Tr_search_categories::getParentFlag($category->parent_id) as $test)
                        @if(!$test->delete_flag)
                          <a href="/admin/category/insert?id={{ $category->id }}&parent_id={{ $category->parent_id }}" onClick="javascript:return confirm('本当に復活させますか？')">
                            <button type="button" class="btn btn-primary btn-xs">復活</button>
                          </a>
                        @endif
                      @endforeach
                    @endif
                  @endif
                </td>
				      </tr>
            @endforeach
          </tbody>
        </table>
        <div class="pull-right">
          {!! $categoryList->appends([
            'name'    => $data_query['name'],
            'enabled' => $data_query['delete_flag'],
          ])->render() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
