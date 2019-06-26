@extends('admin.common.layout')
@section('title', 'カテゴリーのトップページ表示管理')
@section('content')
<?php
use App\Libraries\HtmlUtility;
?>
<div class="col-md-10">
  <div class="row">
    <div class="content-box-large">
      <div class="panel-heading">
        <div class="panel-title" style="font-size:20px">カテゴリーのトップページ表示管理</div>
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
        {{-- error：customValidation --}}
        @if(Session::has('custom_error_messages'))
          <div class="alert alert-danger">
            <ul>
              @foreach(Session::get('custom_error_messages') as $message)
                <li>{{ $message }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        <legend><div class="panel-title">一覧</div></legend>
          <form class="form-horizontal" name="categoryForm" role="form" method="POST" action="{{ url('/admin/category/list') }}">
            <div class="form-group">
              <label for="inputArea" class="col-md-2 control-label">カテゴリ</label>
                <div class="col-md-8 tagTarget">
                    @foreach($parents as $parent)
                        <label class="checkbox">
                            <input type="checkbox" name="search_categories[]" value="{{ $parent->id }}" 
                            @if(Session::has('custom_error_messages'))
                              {{ HtmlUtility::isChecked(old('search_categories'), $parent->id) }} 
                            @else
                              @foreach($display_category as $val)
                                @if($val->parent_id == $parent->id && $val->child_id == 0)
                                  checked="checked"
                                @endif
                              @endforeach
                            @endif
                            >{{ $parent->name }}
                        </label>
                        <div class="col-md-offset-1">
                            @foreach($children as $child)
                                @if($parent->id == $child->parent_id)
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="search_categories[]" value="{{ $child->id }}" 
                                        @if(Session::has('custom_error_messages'))
                                          {{ HtmlUtility::isChecked(old('search_categories'), $child->id) }} 
                                        @else
                                          @foreach($display_category as $val)
                                            @if($val->parent_id == $parent->id && $val->child_id == $child->id)
                                              checked="checked"
                                            @endif
                                          @endforeach
                                        @endif
                                        >{{ $child->name }}
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
            </br>
            </br>
            {{ csrf_field() }}
            <div class="col-md-10">
                <div class="text-right">
                    <button type="submit" class="btn btn-md btn-primary">
                        &nbsp;&nbsp;&nbsp;登録&nbsp;&nbsp;&nbsp;
                    </button>
                </div>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>
@endsection
