@extends('admin.common.layout')
@section('title', 'ログイン')
@section('content')
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

{{-- error：auth --}}
@if(!empty($auth_error))
<div class="alert alert-danger">
    <ul>
        <li>ログインできません</li>
    </ul>
</div>
@endif

<div class="page-content container">
	<div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-wrapper">
                <div class="box">
                    <div class="content-wrap">
                        <form role="form" method="POST" action="{{ url('/admin/login') }}">
                            <div align="left"><span class="alert-danger">{{$errors->first('login_id')}}</span></div>
                            <input type="text" name="login_id" value="{{ old('login_id') }}" class="form-control" maxlength="20" placeholder="ログインID">
                            <div align="left"><span class="alert-danger text-left">{{$errors->first('password')}}</span></div>
                            <input type="password" name="password" class="form-control" maxlength="20" placeholder="パスワード">
                            <button type="submit" class="btn btn-primary signup ">ログイン</button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
			        </div>
			    </div>
			</div>
		</div>
	</div>
</div>
@endsection
