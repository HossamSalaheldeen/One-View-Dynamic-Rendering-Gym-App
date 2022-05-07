@extends('layouts.wrapper', ['title' => Str::ucfirst(Str::replace('-',' ',$resource))])

@section('content')
<div class="container">
    <div class="d-flex flex-column align-items-center justify-content-center">
        <h4 class="welcome-title p-3">Welcome {{auth()->user()->name}} To Gym Management</h4>
        <img src="{{asset('images/default-cover.jpg')}}" width="411" height="411">
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        const resource = "{{$resource}}";
    </script>
    <script src="{{asset('js/'.$resource.'/main.min.js')}}"></script>
@endsection
