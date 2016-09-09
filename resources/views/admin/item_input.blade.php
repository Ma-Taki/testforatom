@extends('admin.common.layout')
@section('title', '案件登録')
@section('content')

<link rel="stylesheet" type="text/css" href="{{ url('/admin/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
<script type="text/javascript" src="{{ url('/admin/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ url('/admin/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js') }}"></script>
<script type="text/javascript">
$(function() {
    $('.datepicker').datepicker({
      format: 'yyyy/mm/dd',
      language: 'ja',
      autoclose: true,
      clearBtn: true,
      todayHighlight: true,
  });
});
</script>
{{-- 相互反映用js --}}
<script src="{{ url('/admin/js/item.js') }}"></script>
<?php
use App\Libraries\HtmlUtility;
 ?>
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="panel-heading">
                <div class="panel-title" style="font-size:20px">案件登録</div>
    		</div>
            <div class="panel-body">

{{-- error：validation --}}
@if(count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
@foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
@endforeach
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

                <div class="row">
                    <div class="col-md-4">案件情報入力（<font color="#FF0000">*</font>は入力必須項目）</div>
                </div>
                </br>
                <form class="form-horizontal" name="itemForm" role="form" method="POST" onSubmit="mutualApplyBeforeSubmit()" action="{{ url('/admin/item/input') }}">
                    <fieldset>
                        <div class="form-group">
                            <label for="inputItemName" class="col-md-2 control-label">案件名<font color="#FF0000">*</font></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="inputItemName" name="item_name" value="{{ old('item_name') }}" maxlength="50" placeholder="案件名">
                                <span class="">(50文字まで)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEntryDate" class="col-md-2 control-label">エントリー受付期間<font color="#FF0000">*</font></label>
                            <div class="col-md-2"><input type="text" class="datepicker" name="item_date_from" value="{{ old('item_date_from') }}" maxlength="10" readonly="readonly"/></div>
                            <div class="col-md-1 text-center">～</div>
                            <div class="col-md-2"><input type="text" class="datepicker" name="item_date_to" value="{{ old('item_date_to') }}"  maxlength="10" readonly="readonly"/></div>
                            <div class="col-md-2">(YYYY/MM/DD形式)</div>
                        </div>
                        <div class="form-group">
                            <label for="inputMaxRate" class="col-md-2 control-label">報酬(検索用)<font color="#FF0000">*</font></label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="inputMaxRate" name="item_max_rate" value="{{ old('item_max_rate') }}" maxlength="4" placeholder="検索用">
                                            <span class="input-group-addon">万円(半角数字)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputRateDetail" class="col-md-2 control-label">報酬(表示用)<font color="#FF0000">*</font></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="inputRateDetail" name="item_rate_detail" value="{{ old('item_rate_detail') }}" maxlength="20" placeholder="表示用">
                                <span class="">(20文字まで)</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputArea" class="col-md-2 control-label">エリア<font color="#FF0000">*</font></label>
                            <div class="col-md-8">
@foreach($master_areas as $area)
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="areas[]" value="{{ $area->id }}" {{ HtmlUtility::isChecked(old('areas'), $area->id) }}>{{ $area->name }}
                                </label>
@endforeach
                                </br>
                                <span class="">(5個まで)</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputAreaDetail" class="col-md-2 control-label">エリア(詳細)<font color="#FF0000">*</font></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="inputAreaDetail" name="item_area_detail" value="{{ old('item_area_detail') }}" maxlength="20" placeholder="エリア詳細">
                                <span class="">(20文字まで)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmploymentPeriod" class="col-md-2 control-label">就業期間</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="inputEmploymentPeriod" name="item_employment_period" value="{{ old('item_employment_period') }}" maxlength="50" placeholder="就業期間">
                                <span class="">(50文字まで)</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputWorkingHours" class="col-md-2 control-label">就業時間</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="inputWorkingHours" name="item_working_hours" value="{{ old('item_working_hours') }}" maxlength="50" placeholder="就業時間">
                                <span class="">(50文字まで)</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputArea" class="col-md-2 control-label">カテゴリ</label>
                            <div class="col-md-8 tagTarget">
                            (20個まで。子カテゴリを登録すると、親カテゴリも登録されます)</br>

@foreach($master_search_categories_parent as $search_category_parent)
                                <label class="checkbox">
                                    <input type="checkbox" name="search_categories[]" value="{{ $search_category_parent->id }}" {{ HtmlUtility::isChecked(old('search_categories'), $search_category_parent->id) }}>{{ $search_category_parent->name }}
                                </label>
                                <div class="col-md-offset-1">
@foreach($master_search_categories_child as $search_category_child)
@if($search_category_parent->id == $search_category_child->parent_id)
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="search_categories[]" value="{{ $search_category_child->id }}" {{ HtmlUtility::isChecked(old('search_categories'), $search_category_child->id) }}>{{ $search_category_child->name }}
                                    </label>
@endif
@endforeach
                            </div>
@endforeach

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_biz_category" class="col-md-2 control-label">業種<font color="#FF0000">*</font></label>
                            <div class="col-md-4">
                                <select class="form-control" id="input_biz_category" name="item_biz_category">

@foreach($master_biz_categories as $biz_category)
                                    <option value="{{ $biz_category->id }}" {{ old('item_biz_category') == $biz_category->id ? "selected" : "" }}>{{ $biz_category->name }}</option>
@endforeach

                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_job_type" class="col-md-2 control-label">ポジション</label>
                            <div class="col-md-8">
                                (20個まで)</br>
                                <div class="row tagTarget">
@foreach($master_job_types as $job_type)
                                    <div class="col-md-6">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="job_types[]" value="{{ $job_type->id }}" {{ HtmlUtility::isChecked(old('job_types'), $job_type->id) }}>{{ $job_type->name }}
                                        </label>
                                    </div>
@endforeach
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_sys_type" class="col-md-2 control-label">システム種別</label>
                            <div class="col-md-8">
                                (20個まで)</br>
                                <div class="row tagTarget">
@foreach($master_sys_types as $sys_type)
                                    <div class="col-md-4">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="sys_types[]" value="{{ $sys_type->id }}" {{ HtmlUtility::isChecked(old('sys_types'), $sys_type->id) }}>{{ $sys_type->name }}
                                        </label>
                                    </div>
@endforeach
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_skill" class="col-md-2 control-label">要求スキル</label>
                            <div class="col-md-8">
                                (20個まで)</br>
                                <div class="row tagTarget">

@foreach($master_skills as $skill)
                                    <div class="col-md-4">
                                        <div class="checkbox-inline">
                                            <label><input type="checkbox" name="skills[]" value="{{ $skill->id }}" {{ HtmlUtility::isChecked(old('skills'), $skill->id) }}><font style="font-weight:normal;">{{ $skill->name }}</font></label>
                                        </div>
                                    </div>
@endforeach
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_sys_type" class="col-md-2 control-label">タグ</label>
                            <div class="col-md-8">
                                (20文字以内、80個まで。キーワード毎に改行してください。)</br>
                                <div class="row">
                                    <div class="col-md-5">
                                        <textarea name="item_tag" rows="15" cols"30" class="form-control" style="font-size:12px" id="tagTextArea">{{ old('item_tag') }}</textarea>
                                    </div>
                                    <div class="col-md-7">
                                        <button type="button" class="btn btn-sm btn-default" onclick="mutualApply()">相互反映</button>
                                        カテゴリ、ポジション、システム種別、要求スキル("その他"以外)</br>
                                        </br>
                                        ▼pickupタグ</br>
                                        <select id="pkts" class="input-sm btn-default">
@foreach($pickupTagInfos as $tagInfo)
                                            <option value="{{ $tagInfo->tag->term }}">{{ $tagInfo->tag->term }}</option>
@endforeach
                                        </select>
                                        <button type="button" class="btn btn-sm btn-default" onclick="addTag(document.getElementById('pkts').value)">追加</button>
                                        </br>
                                        </br>
                                        ▼特集タグ</br>
                                        <select id="ftts" class="input-sm btn-default">
@foreach($featureTagInfos as $tagInfo)
                                    		<option value="{{ $tagInfo->tag->term }}">{{ $tagInfo->tag->term }}</option>
@endforeach
                                        </select>
                                        <button type="button" class="btn btn-sm btn-default" onclick="addTag(document.getElementById('ftts').value)">追加</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_detail" class="col-md-2 control-label">詳細</label>
                            <div class="col-md-8">
                                (1000文字まで)</br>
                                <textarea name="item_detail" rows="15" cols"60" class="form-control" style="font-size:12px" maxlength="1000" id="tagTextArea">
{{ HtmlUtility::setTextValueByRequest('【仕事内容】&#13;&#13;【要求スキル】&#13;（必須）&#13;・&#13;（尚可）&#13;・&#13;&#13;【募集人数】&#13;名&#13;&#13;【面接回数】&#13;回（弊社同席)', old('item_detail')) }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_note" class="col-md-2 control-label">メモ(社内用)</label>
                            <div class="col-md-8">
                                (1000文字まで)</br>
                                <textarea name="item_note" rows="7" cols"60" class="form-control" style="font-size:12px" maxlength="1000" id="tagTextArea">{{ old('item_note') }}</textarea>
                            </div>
                        </div>

                    </br>
                    </br>
                    {{ csrf_field() }}
                    <div class="col-md-10">
                    <div class="text-right">
                        <div class="checkbox-inline">
                            <label><input type="checkbox" name="mutualApplyBeforeSubmitChkbx" id="mutualApplyBeforeSubmitChkbx" checked="checked">
                                <font style="font-weight:normal;">登録時にタグの相互反映を実施する</font></br>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-md btn-primary">&nbsp;&nbsp;&nbsp;登録&nbsp;&nbsp;&nbsp;</button>
                    </div>
                    </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
