@extends('layouts.master')
@section ('content')
    @include ('presentation.navbar')
    <div class="presentation-contact">
        <div class="left-side">
            @if (session('status'))
                <article class="message is-success">
                  <div class="message-body">
                      {{ session('status') }}
                  </div>
                </article>
            @endif
            <div><span class="page-title">Get in Touch</span></div>

            <form class="contact-form" method="post" action="/contact-us">

                <div class="row">
                    <div class="contact-input {{ !$errors->has('first_name') ?: 'error' }}">
                        <label>First Name</label>
                        <input type="text" name="first_name" placeholder="Your first name" @keyup="clearError">
                    </div>
                    <div class="contact-input {{ !$errors->has('last_name') ?: 'error' }}">
                        <label>Last Name</label>
                        <input type="text" name="last_name" placeholder="Your last name" @keyup="clearError">
                    </div>
                </div>

                <div class="row">
                    <div class="contact-input {{ !$errors->has('email') ?: 'error' }}">
                        <label>Email</label>
                        <input type="text" name="email" placeholder="example@domain.com" @keyup="clearError">
                    </div>
                </div>

                <div class="row">
                    <div class="contact-input {{ !$errors->has('subject') ?: 'error' }}">
                        <label>Subject</label>
                        <input type="text" name="subject" placeholder="Main topic" @keyup="clearError">
                    </div>
                </div>

                <div class="row">
                    <div class="contact-input {{ !$errors->has('message') ?: 'error' }}">
                        <label>Message</label>
                        <textarea rows="6" name="message" placeholder="Your message" @keyup="clearError"></textarea>
                    </div>
                </div>
                {{ csrf_field() }}
                <input type="submit" value="Send" class="button-base fill main-button">
            </form>
        </div>
        <div class="right-side">
            <div class="item">
                <p class="contact-label">Email</p>
                <p class="contact-content">info@pdfglue.com</p>
            </div>
            <div class="item">
                <p class="contact-label">Location</p>
                <p class="contact-content">Reno, Nevada</p>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ mix('js/home.js') }}"></script>
@endpush
