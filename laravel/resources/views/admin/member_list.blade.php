@extends('admin.common.layout')
@section('title', '会員一覧')
@section('content')
<?php
    use App\Libraries\OrderUtility as OdrUtil;
    use App\Libraries\ModelUtility as MdlUtil;
    use App\Libraries\AdminUtility as AdmnUtil;
?>
<style>
    #impression-excellent,
    #impression-normal,
    #impression-notgood,
    #impression-black,
    #inputEnabledOnly {
        margin-top: 0px;
        margin-right: 6px;
        vertical-align: middle;
    }

    .col-md-2{
      width:15em;
    }

    .user-state td label,
    .user-impression td label,
    .user-flow td label,
    .user-sns td label{
        font-weight: normal;
        white-space: nowrap;
    }

    table#memberList>tbody>tr>td.memoTD{
      padding:0;
    }

    table#menberList a,table#menberList a:visited,table#menberList a:hover{
    }

    [class^="memo-"]{
      display:none;
      position:relative;
      background:#ebf6f7;
      height:200px;
      width:100%;
    }

    [class^="body-box-"]{
      padding:10px;
    }

    [class^="textarea-box-"]{
      display:none;
      overflow: hidden;
      width:100%;
      height:100%;
      background:yellow;
    }

    [class^="textarea-box-"] > textarea{
      resize:none;
      padding:10px;
      border:none;
      outline:none;
      width:100%;
      height:100%;
      background-color:white;
    }

    .edit-btn,.edit-btn:visited,.edit-btn:hover{
      color:white;
      display:block;
      text-align:center;
      padding:3px 7px;
      background-color:#5e8796;
      border-color:#5e8796;
      border-radius:3px;
      position: absolute;
      bottom:10px;
      right:10px;
      opacity:0.5;
      transition:.2s;
      color:white;
      text-decoration:none;
    }

    .edit-btn:hover{
      color:white;
      text-decoration:none;
      opacity:1;
    }

    .alert-member{
      font-weight: bold;
      padding-bottom: 1.6%;
    }

</style>
<div class="col-md-10">
  <div class="row">
    <div class="content-box-large">
      <div class="panel-heading">
        <div class="panel-title" style="font-size:20px">会員一覧</div>
			</div>
  		<div class="panel-body">
        <p class="alert-member">有効会員: {{ $enable }}人　退会: {{ $Unsubscribe }}人</p>
          {{-- info：custom --}}
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

        <fieldset>
          <legend><div class="panel-title">検索</div></legend>
          <form class="form-inline" role="form" method="GET" action="{{ url('/admin/member/search') }}">
            <table class="table table-bordered">
              <tr>
                <th class="col-md-3"><label class="control-label">会員ID（メールアドレス）</label></th>
                <td class="col-md-9"><input class="form-control input-sm" type="text" name="member_mail" value="{{ old('member_mail', $data_query['member_mail']) }}" /></td>
              </tr>
              <tr>
                <th><label class="control-label">氏名</label></th>
                <td><input type="text" class="form-control input-sm" name="member_name" value="{{ old('member_name', $data_query['member_name']) }}" /></td>
              </tr>
              <tr>
                <th><label class="control-label">氏名(かな)</label></th>
                <td><input type="text" class="form-control input-sm" name="member_name_kana" value="{{ old('member_name_kana', $data_query['member_name_kana']) }}" /></td>
              </tr>
              <tr>
                <th><label class="control-label">フリーワード</label></th>
                <td><input type="text" class="form-control input-sm" name="freeword" value="{{ old('freeword', $data_query['freeword']) }}" /></td>
              </tr>
              <tr class="user-state">
                <th><label class="control-label">ステータス</label></th>
                <td><div class="col-md-3">
                  <!-- 未チェックでも状態を送信する　 -->
                  <input type="hidden"   name="enabledOnly" value="off">
                  <input type="checkbox" name="enabledOnly" value="on" id="inputEnabledOnly" {{ old('enabledOnly', $data_query['enabledOnly']) == 'on' ? 'checked' : '' }} />
                  <label for="inputEnabledOnly"><font style="font-weight:normal;">有効な会員のみ</font></label>
                </div></td>
              </tr>
              <tr class="user-impression">
                <th><label class="control-label">評価</label></th>
                <td>
                  <!-- 未チェックでも状態を送信する　 -->
                  <div class="col-md-2">
                    <label for="impression-excellent">
                      <input type="hidden"   name="impression[0]" value="off">
                      <input type="checkbox" name="impression[0]" id="impression-excellent" value="{{ MdlUtil::USER_IMPRESSION_EXCELLENT }}" {{ in_array(MdlUtil::USER_IMPRESSION_EXCELLENT, old('impression', $data_query['impression'])) ? "checked" : "" }} />
                      優良
                    </label>
                  </div>
                  <div class="col-md-2">
                    <label for="impression-normal">
                      <input type="hidden"   name="impression[1]" value="off">
                      <input type="checkbox" name="impression[1]" id="impression-normal" value="{{ MdlUtil::USER_IMPRESSION_NORMAL }}" {{ in_array(MdlUtil::USER_IMPRESSION_NORMAL, old('impression', $data_query['impression'])) ? "checked" : "" }} />
                      普通
                    </label>
                  </div>
                  <div class="col-md-2">
                    <label for="impression-notgood">
                      <input type="hidden"   name="impression[2]" value="off">
                      <input type="checkbox" name="impression[2]" id="impression-notgood" value="{{ MdlUtil::USER_IMPRESSION_NOTGOOD }}" {{ in_array(MdlUtil::USER_IMPRESSION_NOTGOOD, old('impression', $data_query['impression'])) ? "checked" : "" }} />
                      いまいち
                    </label>
                  </div>
                  <div class="col-md-2">
                    <label for="impression-black">
                      <input type="hidden"   name="impression[3]" value="off">
                      <input type="checkbox" name="impression[3]" id="impression-black" value="{{ MdlUtil::USER_IMPRESSION_BLACK }}" {{ in_array(MdlUtil::USER_IMPRESSION_BLACK, old('impression', $data_query['impression'])) ? "checked" : "" }} />
                      ブラック
                    </label>
                  </div>
                </td>
              </tr>
              <tr class="user-flow">
              <th>進捗状況</th>
              <td>
                  <div class="col-md-2">
                    <label for="status0">
                      <input type="hidden"   name="status[0]" value="off">
                      <input type="checkbox" name="status[0]" id="status0" value="{{ MdlUtil::UNSUPPORTED }}" {{ in_array(MdlUtil::UNSUPPORTED, old('status', $data_query['status'])) ? "checked" : "" }} />
                      未対応
                    </label>
                  </div>
                  <div class="col-md-2">
                    <label for="status9">
                      <input type="hidden"   name="status[9]" value="off">
                      <input type="checkbox" name="status[9]" id="status9" value="{{ MdlUtil::REQUEST_IN_COUNCELING }}" {{ in_array(MdlUtil::REQUEST_IN_COUNCELING, old('status', $data_query['status'])) ? "checked" : "" }} />
                      カウンセリング依頼中
                    </label>
                  </div>
                  <div class="col-md-2">
                    <label for="status1">
                      <input type="hidden"   name="status[1]" value="off">
                      <input type="checkbox" name="status[1]" id="status1" value="{{ MdlUtil::IN_ADJUSTMENT_COUNCELING }}" {{ in_array(MdlUtil::IN_ADJUSTMENT_COUNCELING, old('status', $data_query['status'])) ? "checked" : "" }} />
                      カウンセリング調整中
                    </label>
                  </div>
                  <div class="col-md-2">
                    <label for="status2">
                      <input type="hidden"   name="status[2]" value="off">
                      <input type="checkbox" name="status[2]" id="status2" value="{{ MdlUtil::FINISHED_COUNCELING }}" {{ in_array(MdlUtil::FINISHED_COUNCELING, old('status', $data_query['status'])) ? "checked" : "" }} />
                      カウンセリング済み
                    </label>
                  </div>
                  <div class="col-md-2">
                    <label for="status3">
                      <input type="hidden"   name="status[3]" value="off">
                      <input type="checkbox" name="status[3]" id="status3" value="{{ MdlUtil::IN_ADJUSTMENT_INTERVIEW }}" {{ in_array(MdlUtil::IN_ADJUSTMENT_INTERVIEW, old('status', $data_query['status'])) ? "checked" : "" }} />
                      案件面談調整中
                    </label>
                  </div>
                  <div class="col-md-2">
                    <label for="status4">
                      <input type="hidden"   name="status[4]" value="off">
                      <input type="checkbox" name="status[4]" id="status4" value="{{ MdlUtil::FINISHED_INTERVIEW }}" {{ in_array(MdlUtil::FINISHED_INTERVIEW, old('status', $data_query['status'])) ? "checked" : "" }} />
                      案件面談済み
                    </label>
                  </div>
                  <div class="col-md-2">
                    <label for="status5">
                      <input type="hidden"   name="status[5]" value="off">
                      <input type="checkbox" name="status[5]" id="status5" value="{{ MdlUtil::IN_OPERATION }}" {{ in_array(MdlUtil::IN_OPERATION, old('status', $data_query['status'])) ? "checked" : "" }} />
                      稼働中
                    </label>
                  </div>
                  <div class="col-md-2">
                    <label for="status6">
                      <input type="hidden"   name="status[6]" value="off">
                      <input type="checkbox" name="status[6]" id="status6" value="{{ MdlUtil::EXIT_OPERATION }}" {{ in_array(MdlUtil::EXIT_OPERATION, old('status', $data_query['status'])) ? "checked" : "" }} />
                      終了
                    </label>
                  </div>
                  <div class="col-md-2">
                    <label for="status7">
                      <input type="hidden"   name="status[7]" value="off">
                      <input type="checkbox" name="status[7]" id="status7" value="{{ MdlUtil::STOP_OPERATION }}" {{ in_array(MdlUtil::STOP_OPERATION, old('status', $data_query['status'])) ? "checked" : "" }} />
                      営業中止
                    </label>
                  </div>
                  <div class="col-md-2">
                    <label for="status8">
                      <input type="hidden"   name="status[8]" value="off">
                      <input type="checkbox" name="status[8]" id="status8" value="{{ MdlUtil::IN_OPARATION_AT_OTHER_COMPANY }}" {{ in_array(MdlUtil::IN_OPARATION_AT_OTHER_COMPANY, old('status', $data_query['status'])) ? "checked" : "" }} />
                      他社稼働中
                    </label>
                  </div>
                  <div class="col-md-2">
                    <label for="status10">
                      <input type="hidden"   name="status[10]" value="off">
                      <input type="checkbox" name="status[10]" id="status10" value="{{ MdlUtil::OPERATING_RECORD }}" {{ in_array(MdlUtil::OPERATING_RECORD, old('status', $data_query['status'])) ? "checked" : "" }} />
                      稼働実績有
                    </label>
                  </div>
                </td>
              </tr>
                <th><label class="control-label" for="select-2">表示順序</label></th>
                <td>
                  <select class="form-control" id="select-2" name="sort_id">
                    @foreach(OdrUtil::MemberOrder as $memberOrder)
                      <option value="{{ $memberOrder['sortId'] }}" {{ old("sort_id", $data_query['sort_id']) ==  $memberOrder['sortId'] ? "selected" : "" }}>{{ $memberOrder['sortName'] }}</option>
                    @endforeach
                  </select>
                </td>
              </tr>

              <tr class="user-sns">
                <th><label class="control-label">SNS連携</label></th>
                <td>
                  <!-- 未チェックでも状態を送信する　 -->
                  <!-- フェイスブック -->
                  <div class="col-md-2">
                    <label for="sns0">
                      <input type="hidden"   name="sns[0]" value="off">
                      <input type="checkbox" name="sns[0]" id="sns0" value="{{ MdlUtil::SOCIAL_TYPE_FACEBOOK }}" {{ in_array(MdlUtil::SOCIAL_TYPE_FACEBOOK, old('sns', $data_query['sns'])) ? "checked" : "" }} />
                      Facebook
                    </label>
                  </div>
                  <!-- ツイッター -->
                  <div class="col-md-2">
                    <label for="sns1">
                      <input type="hidden"   name="sns[1]" value="off">
                      <input type="checkbox" name="sns[1]" id="sns1" value="{{ MdlUtil::SOCIAL_TYPE_TWITTER }}" {{ in_array(MdlUtil::SOCIAL_TYPE_TWITTER, old('sns', $data_query['sns'])) ? "checked" : "" }} />
                      Twitter
                    </label>
                  </div>
                  <!-- github -->
                  <div class="col-md-2">
                    <label for="sns2">
                      <input type="hidden"   name="sns[2]" value="off">
                      <input type="checkbox" name="sns[2]" id="sns2" value="{{ MdlUtil::SOCIAL_TYPE_GITHUB }}" {{ in_array(MdlUtil::SOCIAL_TYPE_GITHUB, old('sns', $data_query['sns'])) ? "checked" : "" }} />
                      Github
                    </label>
                  </div>
                </td>
              </tr>								
              <tr>
                <td colspan="2"><button type="submit" class="btn btn-primary btn-md col-xs-2 col-xs-offset-5">検索</button></td>
              </tr>
            </table>
					</form>
        </fieldset>
        <br>
        <br>
        <legend><div class="panel-title">一覧</div></legend>
  			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="memberList">
          <thead>
            <tr>
              <th>会員ID</th>
              <th>氏名/氏名(かな)</th>
              <th>年齢</th>
              <th>性別</th>
              <th>都道府県</th>
              <th>ステータス</th>
              <th>進捗状況</th>
              <th>SNS連携</th>
              <th>メモ</th>
              <th><!-- レイアウト用Blank --></th>
          </thead>
          <tbody>

            @foreach($memberList as $member)
              <tr>
                <!-- 会員ID -->
                <td>{{ $member->mail }}</td>
                <!-- 氏名/氏名(かな) -->
                <td>{{ $member->last_name }} {{ $member->first_name }} ({{ $member->last_name_kana }} {{ $member->first_name_kana }})</td>
                <!-- 年齢 -->
                <td>{{ $member->birth_date->age }}</td>
                <!-- 性別 -->
                <td>{{ $member->sex === 'Male' ? '男性' : '女性' }}</td>
                <!-- 都道府県 -->
                <td>{{ $member->prefecture->name }}</td>
                <!-- ステータス -->
                <td>{{ $member->delete_flag > 0 ? '無効' : '有効' }}</td>
                <!-- 進捗状況 -->
                <td>
                  <div class="select-box" name="{{ $member->id }}">
                  	<select name="status" class="member-status">
                      <option value="0" @if($member->status==MdlUtil::UNSUPPORTED) selected @endif>未対応</option>
                      <option value="9" @if($member->status==MdlUtil::REQUEST_IN_COUNCELING) selected @endif>カウンセリング依頼中</option>
                      <option value="1" @if($member->status==MdlUtil::IN_ADJUSTMENT_COUNCELING) selected @endif>カウンセリング調整中</option>
                  		<option value="2" @if($member->status==MdlUtil::FINISHED_COUNCELING) selected @endif>カウンセリング済み</option>
                      <option value="3" @if($member->status==MdlUtil::IN_ADJUSTMENT_INTERVIEW) selected @endif>案件面談調整中</option>
                  		<option value="4" @if($member->status==MdlUtil::FINISHED_INTERVIEW) selected @endif>案件面談済み</option>
                      <option value="5" @if($member->status==MdlUtil::IN_OPERATION) selected @endif>稼働中</option>
                      <option value="6" @if($member->status==MdlUtil::EXIT_OPERATION) selected @endif>終了</option>
                      <option value="7" @if($member->status==MdlUtil::STOP_OPERATION) selected @endif>営業中止</option>
                      <option value="8" @if($member->status==MdlUtil::IN_OPARATION_AT_OTHER_COMPANY) selected @endif>他社稼働中</option>
                      <option value="10" @if($member->status==MdlUtil::OPERATING_RECORD) selected @endif>稼働実績有</option>
                  	</select>
                  </div>
                  <br>
                  <a href="javascript:void(0);"><button type="button" class="slide-memo-btn btn btn-info btn-xs" name="{{$member->id}}" style="background-color:#5e8796;border-color:#5e8796;">進捗メモ</button></a>
                </td>
                <!-- SNS連携 -->
                <td>
                    @foreach(AdmnUtil::imgSNS($member->socialAccount) as $imgSNS)
                      <img src="/admin/images/{{ $imgSNS }}.png" width="20px"> 
                    @endforeach   
                </td>
                <!-- メモ -->
                <td>{{ $member->memo == '' ? '-' : '○' }}</td>
                <!-- 詳細・削除 -->
                <td nowrap>
                  <a href="/admin/member/detail?id={{ $member->id }}"><button type="button" class="btn btn-info btn-xs">詳細</button></a>
                  @if(!$member->delete_flag)
                    <a href="/admin/member/delete?id={{ $member->id }}" onClick="javascript:return confirm('本当に削除しますか？')"><button type="button" class="btn btn-danger btn-xs">削除</button></a>
                  @endif
                </td>
  					  </tr>
              <tr>
                <td colspan="8" class="memoTD">
                  <div class="memo-{{$member->id}}">
                    <div class="textarea-box-{{$member->id}}">
                      <textarea class="memoTEXTAREA"></textarea>
                    </div>
                    <p class="body-box-{{$member->id}}">{!!nl2br($member->memo)!!}</p>
                    <a class="edit-btn" href="javascript:void(0);" name="{{ $member->id }}">編集</a>
                  </div>
                </td>
              </tr>
            @endforeach

          </tbody>
        </table>
        <dev class="pull-right">
          {!! $memberList->appends([
            'member_mail'      => $data_query['member_mail'],
            'member_name'      => $data_query['member_name'],
            'member_name_kana' => $data_query['member_name_kana'],
            'freeword'         => $data_query['freeword'],
            'enabledOnly'      => $data_query['enabledOnly'],
            'impression'       => $data_query['impression'],
            'sort_id'          => $data_query['sort_id'],
            'status'           => $data_query['status'],
            'sns'              => $data_query['sns'],
          ])->render() !!}
        </div>
      </div>
    </div>
  </div>
</div>

<script>

$('.slide-memo-btn').on('click',function(){
  $(".memo-"+$(this).attr('name')).slideToggle('fast');
});

//切り替え
$('.edit-btn').on('click',function(){
  var self = $(this);
  var id = self.attr('name');
  if($(".body-box-"+id).is(':visible')){
    $(".body-box-"+id).hide();
    var html = $(".body-box-"+id).html();
    $(".textarea-box-"+id+">textarea").val(html.replace(/(<br>|<br \/>)/gi, '\n'));
    self.text("保存").css({"color":"white","text-decoration":"none"});
    $(".textarea-box-"+id).show();
  }else{
    self.text("保存しています...");
    $.ajaxSetup({ headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
      type: "POST",
      url: "/admin/member/editstatus",
      dataType:'json',
      data: {
        id : id,
        text :$(".textarea-box-"+id+">textarea").val() //テキストエリアのテキスト
      },
      success: function(data){
        $(".body-box-"+id).html(data.replace(/\r?\n/g,"<br>"));
        $(".body-box-"+id).show();
        $(".textarea-box-"+id).hide();
        self.text("編集").css({"color":"white","text-decoration":"none"});
      },
      error: function(XMLHttpRequest,textStatus, errorThrown){
        alert("通信に失敗しました。もう一度ボタンを押してください。");
      }
    });
  }
});

$(".member-status").change(function(){
  var self = $(this);
  var id = self.parent().attr("name");
  $.ajaxSetup({ headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
  $.ajax({
    type: "POST",
    url: "/admin/member/selectstatus",
    dataType:'json',
    data: {
      id : id,
      selected : $(this).val() //セレクトボックスの値
    },
    success: function(data){
    },
    error: function(XMLHttpRequest,textStatus, errorThrown){
      alert("通信に失敗しました。もう一度選択してください。");
    }
  });

});

</script>

@endsection
