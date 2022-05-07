@extends('layouts.wrapper', ['title' => Str::ucfirst(Str::replace('-',' ',$resource))])

@section('styles')
@endsection

@section('content')
    <div class="row mb-2">
        <div class="col-4">
            <label for="" class="form-label">Year</label>
            <div class="input-container">
                <select id="year-selector"
                        data-name="year"
                        data-plural="years"
                        class="form-control selectors" aria-label="Default select"
                        name="year"
                        data-placeholder="Year"
                        data-width="100%"
                >
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 d-flex">
            <div class="card w-100">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Revenue</h3>
                    </div>
                </div>
                <div class="card-body">

                    <div class="position-relative mb-4">
                        <canvas id="revenue-chart" width="200" height="200"></canvas>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex">
            <div class="card w-100">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Male - Female Attendance</h3>
                    </div>
                </div>
                <div class="card-body">

                    <div class="position-relative mb-4">
                        <canvas id="gender-chart" width="200" height="200"></canvas>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex">
            <div class="card w-100">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Cities Attendance</h3>
                    </div>
                </div>
                <div class="card-body">

                    <div class="position-relative mb-4">
                        <canvas id="cities-chart" width="200" height="200"></canvas>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex">
            <div class="card w-100">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Top Purchased Users</h3>
                    </div>
                </div>
                <div class="card-body">

                    <div class="position-relative mb-4">
                        <canvas id="users-chart" width="200" height="200"></canvas>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{--    <script type="text/javascript">--}}
    {{--const resource = "{{$resource}}";--}}
    {{--const gender = {{ Js::from($data) }};--}}
    {{--    </script>--}}
    <div id="scripts-container">
        @include('dashboard.scripts')
    </div>
    <script src="{{asset('js/'.$resource.'/main.min.js')}}"></script>
@endsection
