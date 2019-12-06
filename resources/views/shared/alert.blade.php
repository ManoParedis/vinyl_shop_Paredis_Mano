{{-- session key = success --}}
@if (session()->has('success'))
    <div class="alert alert-success">
        <p>{!! session()->get('success') !!}</p>
    </div>
@endif

{{-- session key = danger --}}
@if (session()->has('danger'))
    <div class="alert alert-danger">
        <p>{!! session()->get('danger') !!}</p>
    </div>
@endif