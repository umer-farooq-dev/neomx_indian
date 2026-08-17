@extends($activeTemplate.'layouts.frontend')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 ps-lg-5">
                <div class="contact-wrapper rounded-3">
                    <h4 class="mb-3">@lang('Complete Your Profile')</h4>
                    <p class="text-white mb-4">@lang('You logged in with mobile OTP. Set a username, email and password below so you can also log in without OTP next time.')</p>
                    <form method="POST" action="{{ route('user.data.submit') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('Username')</label>
                                    <input type="text" class="form-control form--control checkUser" name="username"
                                        value="{{ old('username', $user->username) }}">
                                    <small class="text--danger usernameExist"></small>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('Email')</label>
                                    <input type="email" class="form-control form--control checkUser" name="email"
                                        value="{{ old('email') }}" required>
                                    <small class="text--danger emailExist"></small>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Password')</label>
                                <input type="password" class="form-control form--control" name="password" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Confirm Password')</label>
                                <input type="password" class="form-control form--control" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn--base w-100">
                                @lang('Submit')
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@push('script')
<script>
    "use strict";
    (function($) {

        $('.checkUser').on('focusout', function(e) {
            var value = $(this).val();
            var name = $(this).attr('name');
            checkUser(value, name);
        });

        function checkUser(value, name) {
            var url = '{{ route('user.checkUser') }}';
            var token = '{{ csrf_token() }}';
            var data = { _token: token };
            data[name] = value;

            $.post(url, data, function(response) {
                if (response.data != false) {
                    $(`.${response.type}Exist`).text(`${response.field} already exist`);
                } else {
                    $(`.${response.type}Exist`).text('');
                }
            });
        }
    })(jQuery);
</script>
@endpush
