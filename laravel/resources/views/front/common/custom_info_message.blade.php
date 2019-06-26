@if(\Session::has('custom_info_messages'))
<div class="alert alert-info">
    <ul>
        @foreach(\Session::get('custom_info_messages') as $message)
        <li>{{ $message }}</li>
        @endforeach
    </ul>
</div>
@endif
