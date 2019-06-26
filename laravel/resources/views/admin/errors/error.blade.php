@extends('admin.common.layout')
@section('title', 'エラー')
@section('content')
<div class="col-md-10">
    <div class="row">
        <div class="content-box-large">
            <div class="alert alert-danger">
                <ul>
                    <li>{{ $message }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
