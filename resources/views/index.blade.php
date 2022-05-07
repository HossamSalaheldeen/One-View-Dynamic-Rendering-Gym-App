@extends('layouts.wrapper', ['title' => Str::ucfirst(Str::replace('-',' ',$resource))])

@section('styles')
@endsection

@section('content')
    @includeWhen($resource == App\Models\Revenue::getTableName(), 'revenues.card')
    @include('datatables.table')
@endsection

@section('scripts')
    @include('datatables.scripts')
    <script type="text/javascript">
        const resource = "{{$resource}}";
        console.log(resource)
    </script>
    <script src="{{asset('js/'.$resource.'/main.min.js')}}"></script>
@endsection
