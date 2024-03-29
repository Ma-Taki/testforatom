@extends('admin.common.layout')
@section('title', '案件詳細')
@section('content')

<?php
use App\Models\Tr_items;
use App\Models\Ms_sys_types;
use App\Models\Ms_job_types;
use App\Models\Ms_skills;
use App\Models\Tr_search_categories;
?>

@if($item->delete_flag)
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="alert alert-danger">
                <ul>
                    <li>削除済みの案件です。</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

@if(!empty($item->adminUser))
<div class="col-md-10">
    <div class="row">
        <div class="content-box-header">
            <div class="panel-title">登録者</div>
        </div>
        <div class="content-box-large">
            {{ $item->adminUser->admin_name }}
        </div>
    </div>
</div>
@endif

<div class="col-md-10">
    <div class="row">
            <div class="content-box-header">
                <div class="panel-title">案件詳細</div>
                <a href="/admin/item/modify?id={{ $item->id }}"><button type="button" class="btn btn-warning btn-xs">編集</button></a>
            </div>
            <div class="content-box-large box-with-header">
                <div word-wrap:break-word>
                    <table border="1">
                        <tr>
                            <th>案件ID</th>
                            <td>AN{{ $item->id }}</td>
                        </tr>
                        <tr>
                            <th>案件名</th>
                            <td>{{ $item->name }}</td>
                        </tr>
                        <tr>
                            <th>カテゴリ</th>
                            <td>@foreach(Tr_search_categories::getItemCategories($item->id) as $category)
                                    {{ $category->name }}</br>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>業種</th>
                            <td>{{ $item->bizCategorie->name }}</td>
                        </tr>
                        <tr>
                            <th>エリア</th>
                            <td>@foreach($item->areas as $area)
                                {{ $area->name }}</br>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>エリア詳細</th>
                            <td>{{ $item->area_detail }}</td>
                        </tr>
                        <tr>
                            <th>報酬</th>
                            <td>{{ number_format($item->max_rate) }}円
                                {!! isset($entry->item->rate_detail) ? '</br>'.$entry->item->rate_detail : '' !!}
                            </td>
                        </tr>
                        <tr>
                            <th>就業期間</th>
                            <td>{{ $item->employment_period or '' }}</td>
                        </tr>
                        <tr>
                            <th>就業時間</th>
                            <td>{{ $item->working_hours or '' }}</td>
                        </tr>
                        <tr>
                            <th>システム種別</th>
                            <td>@foreach(Ms_sys_types::getSysTypes($item->id) as $systype)
                                    {{ $systype->name }}</br>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>ポシション</th>
                            <td>@foreach(Ms_job_types::getJobTypes($item->id) as $jobtype)
                                    {{ $jobtype->name }}</br>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>要求スキル</th>
                            <td>@foreach(Ms_skills::getItemSkills($item->id) as $skill)   
                                    {{ $skill->name }}</br>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>詳細</th>
                            <td>{!! nl2br($item->detail) !!}</td>
                        </tr>
                        <tr>
                            <th>タグ</th>
                            <td>@foreach($item->tags as $tag)
                                {{ $tag->term }}</br>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>エントリー受付期間</th>
                            <td>{{ $item->service_start_date->format('Y年n月j日').' 〜 '.$item->service_end_date->format('Y年n月j日') }}</td>
                        </tr>
                        <tr>
                            <th>ステータス</th>

@if($today->lt($item->service_start_date))
                            <td>エントリー受付前</td>
@elseif($today->gt($item->service_end_date))
                            <td>エントリー受付終了</td>
@else
                            <td>エントリー受付中</td>
@endif

                        </tr>
                        <tr>
                            <th>メモ(社内用)</th>
                            <td>{!! nl2br($item->note) !!}</td>
                        </tr>
                    </table>
                </div>
            </div>
    </div>

@if($item->entries->isEmpty())
    <div class="row">
        <div class="content-box-large">
            <div class="alert alert-info">
                <ul>
                    <li>この案件にはエントリーがありません。</li>
                </ul>
            </div>
        </div>
    </div>
@else
    <div class="row">
    <div class="content-box-header">
        <div class="panel-title">本案件への応募一覧</div>
    </div>
    <div class="content-box-large box-with-header">
        <table class="table">
            <thead>
                <tr>
                     <th class="">エントリーID</th>
                     <th class="">氏名/氏名(かな)</th>
                     <th class="">エントリー日付</th>
                     <th class="">ステータス</th>
                </tr>
            </thead>
            <tbody>
@foreach($item->entries as $entry)
                <tr>
                    <th><a href="{{ '/admin/entry/detail?id='.$entry->id }}">EN{{ $entry->id }}</a></th>
                    <th>{{ $entry->user->last_name or '名無し' }}&nbsp;{{ $entry->user->first_name or 'さん' }}&nbsp;({{ $entry->user->last_name_kana or 'ななし' }}&nbsp;{{ $entry->user->first_name_kana or 'さん' }})</th>
                    <!-- 開発環境でエラーを出さないために名無しを設定 -->
                    <th>{{ $entry->entry_date->format('Y年n月j日 G時i分') }}</th>
                    <th>{{ $entry->delete_flag ? '無効' : '有効' }}</th>
                </tr>
@endforeach
@endif
            </tbody>
        </table>
    </div>
    </div>
</div>
@endsection
