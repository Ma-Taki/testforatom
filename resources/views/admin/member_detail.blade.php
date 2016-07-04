@extends('admin.common.layout')
@section('title', '会員詳細')
@section('content')
<div class="col-md-10">
{{-- info：custom --}}
@if(\Session::has('custom_info_messages'))
    <div class="alert alert-info">
        <ul>
            <li>{{ \Session::get('custom_info_messages') }}</li>
        </ul>
    </div>
@endif
    <div class="row">
        <div class="col-md-6">
                <div class="content-box-header">
                    <div class="panel-title">会員詳細</div>
                </div>
                <div class="content-box-large box-with-header">
                    <div class="cal-md-12">
                        <table>
                            <tr>
                                <th>氏名</th>
                                <td>{{ $member->first_name }} {{ $member->last_name }}</td>
                            </tr>
                            <tr>
                                <th>氏名（かな）</th>
                                <td>{{ $member->first_name_kana }} {{ $member->last_name_kana }}</td>
                            </tr>
                            <tr>
                                <th>生年月日</th>
                                <td>{{ $member->birth_date->format('Y年n月j日') }}</td>
                            </tr>
                            <tr>
                                <th>年齢</th>
                                <td>{{ $member->birth_date->age }} 歳</td>
                            </tr>
                            <tr>
                                <th>性別</th>
                                <td>{{ $member->sex === 'Male' ? '男性' : '女性'}}</td>
                            </tr>
                            <tr>
                                <th>最終学歴</th>
                                <td>{{ $member->education_level or ''}}</td>
                            </tr>
                            <tr>
                                <th>国籍</th>
                                <td>{{ $member->nationality or '' }}</td>
                            </tr>
                            <tr>
                                <th valign="top">希望の契約形態</th>
                                <td>@foreach($member->contractTypes as $conTyep)
                                    {{ $conTyep->name }}</br>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>住所(都道府県)</th>
                                <td>{{ $member->prefecture->name }}</td>
                            </tr>
                            <tr>
                                <th>最寄り駅</th>
                                <td>{{ $member->station or ''}}</td>
                            </tr>
                            <tr>
                                <th>メールアドレス</br>(会員ID)</th>
                                <td>{{ $member->mail }}</td>
                            </tr>
                            <tr>
                                <th>電話番号</th>
                                <td>{{ $member->tel }}</td>
                            </tr>
                            <tr>
                                <th>登録日</th>
                                <td>{{ $member->registration_date->format('Y年n月j日') }}</td>
                            </tr>
                            <tr>
                                <th>ステータス</th>
                                <td>{{ $member->delete_flag ? '無効' : '有効' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
        </div>
        <div class="col-md-6">
            <div class="content-box-header">
                <div class="panel-title">メモ</div>
            </div>
            <div class="content-box-large box-with-header">
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/member/update') }}">
                    <div class="form-group">
                        <textarea name="memo" rows="10" class="form-control">{{ $member->note }}</textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" align="right">更新</button>
                    </div>
                    <input type="hidden" name="member_id" value="{{ $member->id }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
            </div>
        </div>
        <div class="col-md-12">
            <div class="content-box-header">
                <div class="panel-title">本会員の応募一覧</div>
            </div>
            <div class="content-box-large box-with-header">
                <table class="table">
                    <thead>
                        <tr>
                             <th class="">エントリーID</th>
                             <th class="">案件ID</th>
                             <th class="">案件名</th>
                             <th class="">エントリー日付</th>
                             <th class="">ステータス</th>
                         </tr>
                     </thead>
                     <tbody>

@foreach($member->entries as $entry)
                        <tr>
                            <th><a href="/admin/entry/detail?id={{ $entry->id }}">EN{{ $entry->id }}</th>
                            <th>AN{{ $entry->item_id }}</th>
                            <th>{{$entry->item->name }}</th>
                            <th>{{ $entry->entry_date->format('Y年n月j日') }}</th>
                            <th>{{ $entry->delete_flag > 0 ? '無効' : '有効' }}</th>
                        </tr>
@endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
