@extends('admin.common.layout')
@section('title', 'エントリー詳細')
@section('content')
<div class="col-md-10">
    <div class="row">
        <div class="col-md-5">
            <div class="row">
                <div class="content-box-header">
                    <div class="panel-title">エントリー詳細</div>
                </div>
                <div class="content-box-large box-with-header">
                    <div class="cal-md-12">
                        <table>
                            <tr>
                                <th>エントリーID</th>
                                <td>EN{{ $entry->id }}</td>
                            </tr>
                            <tr>
                                <th>エントリー日付</th>
                                <td>{{ $entry->entry_date->format('Y年n月j日 G時i分') }}</td>
                            </tr>
                            <tr>
                                <th>スキルシート</th>
                                <td>{!! $entry->skillsheet_upload ? "<a href='/admin/entry/download?id=$entry->id'>アップロード済み</a>" : '未アップロード' !!}</td>
                            </tr>
                            <tr>
                                <th>ステータス</th>
                                <td>{{ $entry->delete_flag ? '無効' : '有効' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="content-box-header">
                    <div class="panel-title">会員詳細</div>
                </div>
                <div class="content-box-large box-with-header">
                    <div class="cal-md-12">
                        <table>
                            <tr>
                                <th>氏名</th>
                                <td>{{ $entry->user->last_name.' '.$entry->user->first_name }}</td>
                            </tr>
                            <tr>
                                <th>氏名(かな)</th>
                                <td>{{ $entry->user->last_name_kana.' '.$entry->user->first_name_kana }}</td>
                            </tr>
                            <tr>
                                <th>生年月日</th>
                                <td>{{ $entry->user->birth_date->format('Y年n月j日') }}</td>
                            </tr>
                            <tr>
                                <th>年齢</th>
                                <td>{{ $entry->user->birth_date->age }} 歳</td>
                            </tr>
                            <tr>
                                <th>性別</th>
                                <td>{{ $entry->user->sex === 'Male' ? '男性' : '女性'}}</td>
                            </tr>
                            <tr>
                                <th>最終学歴</th>
                                <td>{{ $entry->user->education_level or ''}}</td>
                            </tr>
                            <tr>
                                <th>国籍</th>
                                <td>{{ $entry->user->nationality or '' }}</td>
                            </tr>
                            <tr>
                                <th valign="top">希望の契約形態</th>
                                <td>@foreach($entry->user->contractTypes as $conTyep)
                                    {{ $conTyep->name }}</br>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>住所(都道府県)</th>
                                <td>{{ $entry->user->prefecture->name }}</td>
                            </tr>
                            <tr>
                                <th>最寄り駅</th>
                                <td>{{ $entry->user->station or ''}}</td>
                            </tr>
                            <tr>
                                <th>メールアドレス</br>(会員ID)</th>
                                <td>{{ $entry->user->mail }}</td>
                            </tr>
                            <tr>
                                <th>電話番号</th>
                                <td>{{ $entry->user->tel }}</td>
                            </tr>
                            <tr>
                                <th>登録日</th>
                                <td>{{ $entry->user->registration_date->format('Y年n月j日') }}</td>
                            </tr>
                            <tr>
                                <th>ステータス</th>
                                <td>{{ $entry->user->delete_flag ? '無効' : '有効' }}</td>
                            </tr>
                            <tr>
                                <th>スキルシート</th>
                                <td>{!! $entry->user->skillsheet_upload ? "<a href='/admin/member/download?id=$entry->user_id'>アップロード済み</a>" : '未アップロード' !!}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="content-box-header">
                <div class="panel-title">案件詳細</div>
            </div>
            <div class="content-box-large box-with-header">
                <div class="cal-md-12" word-wrap:break-word>
                    <table border="1">
                        <tr>
                            <th>案件ID</th>
                            <td>AN{{ $entry->item->id }}</td>
                        </tr>
                        <tr>
                            <th>案件名</th>
                            <td>{{ $entry->item->name }}</td>
                        </tr>
                        <tr>
                            <th>カテゴリ</th>
                            <td>@foreach($entry->item->searchCategorys as $category)
                                {{ $category->name }}</br>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>業種</th>
                            <td>{{ $entry->item->bizCategorie->name }}</td>
                        </tr>
                        <tr>
                            <th>エリア</th>
                            <td>@foreach($entry->item->areas as $area)
                                {{ $area->name }}</br>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>エリア詳細</th>
                            <td>{{ $entry->item->area_detail }}</td>
                        </tr>
                        <tr>
                            <th>報酬</th>
                            <td>{{ $entry->item->max_rate }}円
                                {!! isset($entry->item->rate_detail) ? '</br>'.$entry->item->rate_detail : '' !!}
                            </td>
                        </tr>
                        <tr>
                            <th>就業期間</th>
                            <td>{{ $entry->item->employment_period or '' }}</td>
                        </tr>
                        <tr>
                            <th>就業時間</th>
                            <td>{{ $entry->item->working_hours or '' }}</td>
                        </tr>
                        <tr>
                            <th>システム種別</th>
                            <td>@foreach($entry->item->sysTypes as $systype)
                                {{ $systype->name }}</br>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>ポシション</th>
                            <td>@foreach($entry->item->jobTypes as $jobtype)
                                {{ $jobtype->name }}</br>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>要求スキル</th>
                            <td>@foreach($entry->item->skills as $skill)
                                {{ $skill->name }}</br>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>詳細</th>
                            <td>{!! nl2br($entry->item->detail) !!}</td>
                        </tr>
                        <tr>
                            <th>タグ</th>
                            <td>@foreach($entry->item->tags as $tag)
                                {{ $tag->term }}</br>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>エントリー受付期間</th>
                            <td>{{ $entry->item->service_start_date->format('Y年n月j日').' 〜 '.$entry->item->service_end_date->format('Y年n月j日') }}</td>
                        </tr>
                        <tr>
                            <th>ステータス</th>

@if($today->lt($entry->item->service_start_date))
                            <td>エントリー受付前</td>
@elseif($today->gt($entry->item->service_end_date))
                            <td>エントリー受付終了</td>
@else
                            <td>エントリー受付中</td>
@endif

                        </tr>
                        <tr>
                            <th>メモ(社内用)</th>
                            <td>{{ $entry->item->note }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
