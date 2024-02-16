@extends('layouts.master')

@section('content')
@include ('presentation.navbar')
<div class="reset-wrapper">
    <div class="reset-container">
        <div class="reset-panel reset-default">
            <div class="reset-heading"><span class="reset-title">Reset Password</span></div>

            <div class="reset-body">
                <form class="form-horizontal" method="POST" action="{{ route('password.request') }}" ref="resetForm">
                    {{ csrf_field() }}
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="field{{ $errors->has('email') ? ' has-error' : '' }}" :class="{'has-error' : forgotReset.errors.email.length}">
                        <label class="label is-small" for="email">EMAIL</label>
                        <input id="email" v-model="forgotReset.email" class="input is-medium" :class="{'has-error' : (forgotReset.errors.email.length>0), 'bounce-enter-active': failState === true}" name="email" type="email" placeholder="Your account's email address" value="{{ old('email') }}" @keyup="forgotReset.errors.email = ''" required autofocus>
                        <p class="help has-error error-message-flash" :class="{'bounce-enter-active': failState === true}" v-if="forgotReset.errors.email.length > 0">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>@{{forgotReset.errors.email}}
                        </p>
                        @if ($errors->has('email'))
                        <p class="help has-error error-message-flash" v-if="forgotReset.errors.email.length===0">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{$errors->first('email') }}
                        </p>
                        @endif
                    </div>

                    <div class="field{{ $errors->has('password') ? ' has-error' : '' }}" :class="{'has-error' : forgotReset.errors.password.length}">
                        <label class="label is-small" for="password">NEW PASSWORD</label>
                        <input id="password" v-model="forgotReset.password" class="input is-medium" :class="{'has-error' : (forgotReset.errors.password.length>0), 'bounce-enter-active': failState === true}" name="password" type="password" placeholder="Your new password" @keyup="forgotReset.errors.password = ''" required>
                        <p class="help has-error error-message-flash" :class="{'bounce-enter-active': failState === true}" v-if="forgotReset.errors.password.length > 0">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>@{{forgotReset.errors.password}}
                        </p>
                        @if ($errors->has('password'))
                        <p class="help has-error error-message-flash" v-if="forgotReset.errors.password.length===0">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{$errors->first('password') }}
                        </p>
                        @endif
                    </div>

                    <div class="field{{ $errors->has('password_confirmation') ? ' has-error' : '' }}" :class="{'has-error' : forgotReset.errors.password_confirmation.length}">
                        <label class="label is-small" for="password-confirm">CONFIRM NEW PASSWORD</label>
                        <input id="password" v-model="forgotReset.password_confirmation" class="input is-medium" :class="{'has-error' : (forgotReset.errors.password_confirmation.length>0), 'bounce-enter-active': failState === true}" name="password_confirmation" type="password" placeholder="Your password confirmation" @keyup="forgotReset.errors.password_confirmation = ''" required>
                        <p class="help has-error error-message-flash" :class="{'bounce-enter-active': failState === true}" v-if="forgotReset.errors.password_confirmation.length > 0">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>@{{forgotReset.errors.password_confirmation}}
                        </p>
                        @if ($errors->has('password_confirmation'))
                        <p class="help has-error error-message-flash" v-if="forgotReset.errors.password_confirmation.length===0">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{$errors->first('password_confirmation') }}
                        </p>
                        @endif
                    </div>

                    <div class="field">
                        <button @click.prevent="attemptReset" class="button is-info">
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ mix('js/home.js') }}"></script>
@endpush
