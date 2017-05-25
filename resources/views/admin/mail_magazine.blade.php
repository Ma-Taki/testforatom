@extends('admin.common.layout')
@section('title', 'メルマガ管理')
@section('content')
<link rel="stylesheet" href="/admin/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.css" />
<link rel="stylesheet" type="text/css" href="/admin/datetimepicker/jquery.datetimepicker.css" />
<link rel="stylesheet" type="text/css" href="/admin/bootstrap-datepicker/css/bootstrap-datepicker.min.css" />
<style>
    .wrap-table {
        width: 70%;
    }
    .display-flex {
        display: flex;
        justify-content: space-between;
    }
    .bootstrap-tagsinput {
        width: 100%;
    }
    .input-to-address .bootstrap-tagsinput {
        border-top-left-radius: 0px;
        border-bottom-left-radius: 0px;
    }
    .bootstrap-tagsinput input{
        width: 100%;
    }
</style>

<div class="col-md-10">
	<div class="content-box-large">
		  <div class="panel-heading">
				<div class="panel-title">@if($itemList==null) メルマガ 新規作成 @else メルマガ 編集(記事ID：{{$itemList->id}}) @endif</div>

		  			<div class="panel-body">

@if(count($errors) > 0)
                      <div class="alert alert-danger">
                        <ul>
@foreach($errors->all() as $error)
                          <li>{{ $error }}</li>
@endforeach
                        </ul>
                      </div>
@endif
                        <div id="rootwizard">
                            <div id="tags"></div>
                            <form class="form-horizontal" id="js__mail-magazine-form" role="form" method="POST" action="/admin/mail-magazine">
                            <div class="tab-content">
							  <div class="tab-pane active" id="tab1">
								<div class="form-group">
								  <label for="subject" class="col-sm-2 control-label">件名</label>
								  <div class="col-sm-10">
									<input type="text" class="form-control" id="subject" name="subject" placeholder="件名" value="@if($request['type']=='new'){{ old('subject') }} @elseif($request['type']=='edit'&& $itemList!=null){{$itemList->subject}}@endif">
								  </div>
								</div>
                <div class="tab-pane active" id="tab2">
                        <div class="form-group">
        <label for="sendEmailAddress" class="col-sm-2 control-label">From</label>
        <div class="col-sm-10">
            <span class="form-control" id="sendEmailAddress">sender@solidseed.co.jp</span>
        </div>
    </div>
                        <div class="form-group">
        <label for="toEmailAddress" class="col-sm-2 control-label">To</label>
        <div class="col-sm-10">
                                <div class="radio">
                                  <label>
<?php
  $af="";
  if($request['type'] == 'edit'){
    $af = $itemList->send_to;
  }
?>
                <input type="radio" name="toAddressesFlag" value="0"　@if($af==0) checked @elseif(old('toAddressesFlag') != '1' && old('toAddressesFlag') != '2') checked @endif> 配信希望ユーザのみ
                                  </label>
                                </div>
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="toAddressesFlag" value="1" @if($af==1) checked @elseif(old('toAddressesFlag') == '1') checked @endif> すべての有効なユーザ
                                  </label>
                                </div>
                                <div class="display-flex">
                                  <div class="radio">
                                    <label>
                                      <input type="radio" name="toAddressesFlag" value="2" @if($af==2) checked @elseif(old('toAddressesFlag') == '2') checked @endif> メールアドレスを指定
                                    </label>
                                  </div>
                                  <div class="wrap-table">
                                    <div class="input-group input-to-address">
                                      <span class="input-group-addon">to</span>
                                      <input type="text" class="form-control" name="toAddresses" id="js__toAddresses" data-role="tagsinput" value="@if($request['type']=='new'){{ old('toAddresses') }} @elseif($request['type']=='edit' && $itemList->send_to == 2)@foreach($itemList->mailaddresses as $addresses){{$addresses->mail_address}},@endforeach @endif">
                                    </div>
                                  </div>
            </div>
        </div>
                        </div>

                        <div class="form-group input-cc-address">
        <label for="ccEmailAddress" class="col-sm-2 control-label">Cc</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="ccAddresses" id="js__ccAddresses" data-role="tagsinput" value="@if($request['type']=='new'){{ old('ccAddresses') }} @elseif($request['type']=='edit'){{$itemList->cc}}@endif">
        </div>
    </div>

                        <div class="form-group input-bcc-address">
        <label for="bccEmailAddress" class="col-sm-2 control-label">Bcc</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="bccAddresses" id="js__bccAddresses" data-role="tagsinput" value="@if($request['type']=='new'){{ old('bccAddresses') }} @elseif($request['type']=='edit'){{$itemList->bcc}}@endif">
        </div>
    </div>

<?php
  $sf="";
  if($request['type'] == 'edit'){
    $sf = $itemList->send_flag;
  }
?>
                        <div class="form-group">
        <label for="sendDate" class="col-sm-2 control-label">送信日時</label>
      <div class="col-sm-10">
                            <div class="radio">
                              <label>
            <input type="radio" name="sendDateFlag" value="0" @if($sf==0)checked@elseif(old('sendDateFlag') != '1') checked @endif> 即時
                              </label>
                            </div>
                            <div class="display-flex">
                              <div class="radio">
                                <label>
                                  <input type="radio" name="sendDateFlag" value="1" @if($sf==1) checked @elseif(old('sendDateFlag') == '1') checked @endif>時間を指定する&nbsp;
                                </label>
                              </div>

                              <div class="wrap-table">
                                <div class="input-group">
                                  <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-time"></i>
                                  </span>
                                  <input type="text" class="form-control" name="sendDateTime" id="js__send-DateTime" value="@if($sf==1){{$itemList->send_at}}@else{{ old('sendDateTime') }}@endif" autocomplete="off">
                                </div>
                              </div>
        </div>
                          </div>
                      </div>
								<div class="form-group">
								  <label class="col-sm-2 control-label">本文</label>
								  <div class="col-sm-10">
								    <textarea class="form-control" name="mailText" placeholder="本文" rows="18" value="mailText">@if($request['type']=='edit'){{$itemList->body}}@else{{old('mailText')}}@endif</textarea>
								  </div>
                                </div>
							  </div>


								</div>
                <input type="hidden" name="type" value=@if($request['type'] == 'edit' && !empty($itemList))"edit"@else'new'@endif>
                <input type="hidden" name="id" value=@if($request['type'] == 'edit' && !empty($itemList))"{{$itemList->id}}"@else''@endif>
                                {{ csrf_field() }}
                                <button type="button" class="btn btn-primary pull-right" id="js__submit">
                                    &nbsp;@if($request['type']=='edit') 更新 @else 作成 @endif&nbsp;
                                </button>
					</div>
                    </form>
				</div>
            </div>
        </div>
	</div>
</div>
<script type="text/javascript" src="{{ url('/admin/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ url('/admin/bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js') }}"></script>
<script src="/admin/datetimepicker/build/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="/admin/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript">
$(function() {
     $.datetimepicker.setLocale('ja');
    $('#js__send-DateTime').datetimepicker({
        step: 10,
    });

    if ($("input[name='sendDateFlag']:checked").val() != '1') {
        $('#js__send-DateTime').parents('.wrap-table').hide();
    }
    $("input[name='sendDateFlag']").change(function (){
        $("input[name='sendDateTime']").parents('.wrap-table').slideToggle('fast');
    });

    if (($("input[name='toAddressesFlag']:checked").val()) != '2') {
        $("input[name='toAddresses']").parents('.wrap-table').hide();
    }
    $("input[name='toAddressesFlag']").change(function (){
        if ($(this).val() == '2') {
            $("input[name='toAddresses']").parents('.wrap-table').slideDown('fast');
        } else {
            $("input[name='toAddresses']").parents('.wrap-table').slideUp('fast');
        }
    });

    $('#js__submit').click(function() {

        if (confirm('テスト送信で確認しましたか？')) {
            // LaravelのValidationを一件づつ通すため送信前に分割...しない
            /*
            var $addresses = $('.input-to-address .tag');
            var $form = $('#js__mail-magazine-form');
            for (var i = 0; i < $addresses.length; i++) {
                $('<input />')
                    .attr('type', 'hidden')
                    .attr('name', 'hdn_to_addresses[]')
                    .attr('value', $addresses.eq(i).text())
                    .appendTo($form);
            }
            */
            $('#js__mail-magazine-form').submit();
        }
    });

    /* 無意味だった
    if (rtrn_to_address.length > 0) {
        var $tag_input = $('.input-to-address .bootstrap-tagsinput input');
        var $rm_button = $('<span />').attr('data-role','remove');
        for (var i =0; i < rtrn_to_address.length; i++) {
            $('<span />')
                .attr('class', 'tag label label-info')
                .text(rtrn_to_address[i])
                .append($rm_button)
                .insertBefore($tag_input);
        }
    }
    */

    /* 無意味だった
    var rtrn_to_address = JSON.parse('<?=json_encode(old("hdn_to_addresses")?:[]);?>');
    for (var i =0; i < rtrn_to_address.length; i++) {
        $('#js__toAddres').tagsinput('add', rtrn_to_address[i]);
    }
    */
    var rtrn_to_address = JSON.parse('<?=json_encode(old("toAddresses")?:"");?>');
    if (rtrn_to_address != "") {
        $('#js__toAddresses').tagsinput('add', rtrn_to_address);
    }
    var rtrn_cc_address = JSON.parse('<?=json_encode(old("ccAddresses")?:"");?>');
    if (rtrn_cc_address != "") {
        $('#js__ccAddresses').tagsinput('add', rtrn_cc_address);
    }
    var rtrn_bcc_address = JSON.parse('<?=json_encode(old("bccAddresses")?:"");?>');
    if (rtrn_bcc_address != "") {
        $('#js__bccAddresses').tagsinput('add', rtrn_bcc_address);
    }
});
</script>
@endsection
