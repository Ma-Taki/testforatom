{{-- validation error --}}
@if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
    @endforeach
                </ul>
            </div>
@endif
{{-- custom error --}}
@if(count($errors) == 0)
    @if(Session::has('custom_error_messages'))
            <div class="alert alert-danger">
                <ul>
        @foreach(Session::get('custom_error_messages') as $message)
                    <li>{{ $message }}</li>
        @endforeach
                </ul>
            </div>
    @endif
@endif
