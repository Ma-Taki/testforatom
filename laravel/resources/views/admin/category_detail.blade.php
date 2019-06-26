@extends('admin.common.layout')
@section('title', 'カテゴリー詳細')
@section('content')

@if($category->delete_flag)
    <div class="col-md-10">
        <div class="row">
            <div class="content-box-large">
                <div class="alert alert-danger">
                    <ul>
                        <li>削除済みのカテゴリーです。</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="col-md-10">
    <div class="row">
        <div class="content-box-header">
            <div class="panel-title">カテゴリー詳細</div>
        </div>
        <div class="content-box-large box-with-header">
            <div word-wrap:break-word>
                <table border="1">
                    <tr>
                        <th>親カテゴリー名</th>
                        <td>
                            @if(empty($category->parent_id))
                                {{ $category->name }}
                            @else
                                {{ $parent->name }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>親表示順</th>
                        <td>{{ $category->parent_sort }}</td>
                    </tr>
                    <tr>
                        <th>子カテゴリー名</th>
                        <td>
                            @if(empty($category->parent_id))
                                -
                            @else
                                {{ $category->name }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>子表示順</th>
                        <td>
                            @if(empty($category->parent_id))
                                -
                            @else
                                {{ $category->child_sort }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>ステータス</th>
                        <td>{{ $category->delete_flag ? '非表示' : '表示' }}</td>
                    </tr>
                    <tr>
                        <th>ページタイトル</th>
                        <td>{{ $category->page_title }}</td>
                    </tr>
                    <tr>
                        <th>ページキーワード</th>
                        <td>{{ $category->page_keywords }}</td>
                    </tr>
                    <tr>
                        <th>ページディスクリプション</th>
                        <td>{{ $category->page_description }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>        
</div>
@endsection
