@extends('layouts.master')

@section('content')
@include ('presentation.navbar')
<div class="reset-wrapper">
    <div class="reset-container">
        <div class="reset-panel reset-default">
            @if (session('status'))
            <div class="reset-heading"><span class="reset-title">Check Your Email</span></div>
            @else
            <div class="reset-heading"><span class="reset-title">Forgot Password</span></div>
            @endif

            <div class="reset-body">
                @if (session('status'))
                <div class="alert-body">
                    <div class="confirm-reset">
                        Success! The password reset link was sent to your email address. It should be there momentarily.
                    </div>
                    <div class="confirm-reset boldit">
                        Please check your email and click the link in the message.  After 4 hours, the link will expire.
                    </div>

                    <a class="button back-login" href="/login">Back to Log In</a>
                </div>
                @endif
                @if (!session('status'))
                <form class="form-horizontal" ref="forgotForm" method="POST" action="{{ route('password.email') }}" @submit="attemptForgot">
                    {{ csrf_field() }}
                    <div class="field{{ $errors->has('email') ? ' has-error' : '' }}" :class="{'has-error' : forgotPass.errors.email.length}" >
                        <label class="label is-small" for="email" >EMAIL</label>
                        <input id="password" v-model="forgotPass.email" class="input is-medium" :class="{'has-error' : (forgotPass.errors.email.length>0), 'bounce-enter-active': failState === true}" name="email" type="email" placeholder="Your account's email address" @keyup="forgotPass.errors.email = ''" required>
                        <p class="help has-error error-message-flash" :class="{'bounce-enter-active': failState === true}" v-if="forgotPass.errors.email.length > 0">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>@{{forgotPass.errors.email}}
                        </p>
                        @if ($errors->has('email'))
                        <p class="help has-error error-message-flash" v-if="forgotPass.errors.email.length===0">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>{{$errors->first('email') }}
                        </p>
                        @endif
                    </div>

                    <div class="field footer-buttons">
                        <a class="button cancel-forgot" href="/login">Cancel</a>
                        <button @click.prevent="attemptForgot" class="button is-info" >
                            Send Reset Email
                        </button>
                    </div>
                    <div class="forgot-notification">We'll send you a link to reset your Pdfglue password!</div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ mix('js/home.js') }}"></script>
@endpush
