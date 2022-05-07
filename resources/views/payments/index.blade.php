@extends('layouts.wrapper', ['title' => Str::ucfirst(Str::replace('-',' ',$resource))])

@section('styles')
@endsection

@section('content')
    @include('payments.form')
@endsection

@section('scripts')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script type="text/javascript">
        const resource = "{{$resource}}";
        console.log(resource)
    </script>
    <script src="{{asset('js/'.$resource.'/main.min.js')}}"></script>
@endsection
