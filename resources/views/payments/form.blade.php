@if (Session::has('success'))
    <div class="alert alert-success text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <p>{{ Session::get('success') }}</p>
    </div>
@endif
<form
    role="form"
    action="{{ route('payments.store') }}"
    method="POST"
    class="require-validation"
    data-cc-on-file="false"
    data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
    id="payment-form">
    @csrf
    <div class="row">
        @isset($userFieldShowRoles)
            @role($userFieldShowRoles)
        <div class="col mb-3">
            <label for="" class="form-label">User</label>
            <div class="input-container">
                <select id="user-selector-0"
                        data-name="user"
                        data-plural="users"
                        class="form-control selectors" aria-label="Default select"
                        name="user_id"
                        data-placeholder="User"
                        data-width="100%">
                </select>
            </div>
        </div>
            @endrole
        @endisset
        <div class="col mb-3">
            <label for="" class="form-label">Training Package</label>
            <div class="input-container">
                <select id="training-package-selector-0"
                        data-name="training-package"
                        data-plural="training-packages"
                        class="form-control selectors" aria-label="Default select"
                        name="training_package_id"
                        data-placeholder="Training Package"
                        data-width="100%">
                </select>
            </div>
        </div>
        @isset($gymFieldShowRoles)
            @role($gymFieldShowRoles)
                <div class="col mb-3">
                <label for="" class="form-label">Gym</label>
                <div class="input-container">
                    <select id="gym-selector-0"
                            data-name="gym"
                            data-plural="gyms"
                            class="form-control selectors" aria-label="Default select"
                            name="gym_id"
                            data-placeholder="Gym"
                            data-width="100%">
                    </select>
                </div>
            </div>
            @endrole
        @endisset

    </div>
    {{--    <div class='form-row row'>--}}
    {{--        <div class='col-xs-12 col-md-6 form-group required'>--}}
    {{--            <label class='control-label'>Name on Card</label>--}}
    {{--            <input class='form-control' size='4' placeholder='ex. Jane Doe' type='text'>--}}
    {{--        </div>--}}
    {{--        <div class='col-xs-12 col-md-6 form-group required'>--}}
    {{--            <label class='control-label'>Card Number</label>--}}
    {{--            <input autocomplete='off' class='form-control card-number' size='20' placeholder='ex. 4242 4242 4242 4242'--}}
    {{--                   type='text'>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    <div class='form-row row'>--}}
    {{--        <div class='col-xs-12 col-md-4 form-group cvc required'>--}}
    {{--            <label class='control-label'>CVC</label>--}}
    {{--            <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='text'>--}}
    {{--        </div>--}}
    {{--        <div class='col-xs-12 col-md-4 form-group expiration required'>--}}
    {{--            <label class='control-label'>Expiration Month</label>--}}
    {{--            <input class='form-control card-expiry-month' placeholder='MM' size='2' type='text'>--}}
    {{--        </div>--}}
    {{--        <div class='col-xs-12 col-md-4 form-group expiration required'>--}}
    {{--            <label class='control-label'>Expiration Year</label>--}}
    {{--            <input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text'>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    <div class='form-row row'>--}}
    {{--        <div class='col-md-12 error form-group hide'>--}}
    {{--            <div class='alert-danger alert'>Please correct the errors and try again.</div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <div class="row">
        <div class="col-xs-12">
            <button class="button ajax-start submit-btn" type="submit">
                <span class="button__text">Buy</span>
            </button>
        </div>
    </div>
</form>
