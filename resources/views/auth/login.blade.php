@extends('layouts.master')

@section('content')
@include ('presentation.navbar')
<div class="reset-wrapper">
    <div class="reset-container">
        <div class="reset-panel reset-default">
            <div class="reset-heading"><span class="reset-title">Login</span></div>

            <div class="reset-body">
                <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}

                    <div class="field{{ $errors->has('email') ? ' has-error' : '' }}" :class="{'has-error' : login.errors.email.length}" >
                        <label class="label is-small" for="email">EMAIL</label>
                        <input id="email" v-model="login.email" class="input is-medium" :class="{'has-error' : (login.errors.email.length>0), 'bounce-enter-active': failState === true}" name="email" type="email" placeholder="example@domain.com" value="{{ old('email') }}" @keyup="login.errors.email = ''" required>
                        <p class="help has-error error-message-flash" :class="{'bounce-enter-active':failState === true}" v-if="login.errors.email.length > 0">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>@{{login.errors.email}}
                        </p>
                    </div>

                    <div class="field{{ $errors->has('password') ? ' has-error' : '' }}" :class="{'has-error' : login.errors.password.length}" >
                        <label class="label is-small" for="password">PASSWORD</label>
                        <input id="password" v-model="login.password" class="input is-medium" :class="{'has-error' : (login.errors.password.length>0), 'bounce-enter-active': failState === true}" name="password" type="password" placeholder="Must have at least 6 characters" @keyup="login.errors.password = ''" required>
                        <p class="help has-error error-message-flash" :class="{'bounce-enter-active':failState === true}" v-if="login.errors.password.length>0"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>@{{login.errors.password}}</p>
                    </div>

                    <div class="field is-grouped remember-me">
                        <span>
                            <input type="checkbox" name="remember" class="styled-checkbox" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember"><span class="rmb" >Remember Me</span></label>
                        </span>
                        <a class="" href="{{ route('password.request') }}">Forgot Password?</a>
                    </div>

                    <div class="field">
                        <button type="submit" class="button-base fill main-button upload-main-button">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('js/home.js') }}"></script>
@endpush
