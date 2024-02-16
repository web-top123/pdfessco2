@extends('layouts.master')
@section ('content')
    <div class="main-wrapper">
        @include ('presentation.navbar')
        <div class="presentation-about">
            <div><span class="page-title">About</span></div>
            <img class="about-image" src="/images/about-picture.png">
            <p class="about-text">
                Projects which change constantly can be difficult to keep literature, documentation and data sheets up to date. In today’s world customers, end users and support staff demand up to date information.
            </p>
            <p class="about-text">
                <b>Pdfglue</b> was born to address that need. It is based on uploading static pdf files to the server, attaching a meaningful label to each file and reusing the pages as needed regardless of how many changes a document may require.
            </p>
            <p class="about-text">
                Documents can be saved on the server (for a limited time), viewed online, saved to your local hard drive and/or emailed. The insures an accurate document with a minimum of effort.
            </p>
            <p class="about-text">
                Sales people can attach specific information pertaining to any prepared proposal. No more sending general information and confusing our customer. You can be specific!
            </p>
            <p class="about-text">
                Support people can now provide detailed information on any issue. No more sending a large document and leaving irrelevant information applied to a problem!
            </p>
            <p class="about-text">
                Now managers can supply only what information is necessary to address an issue. Pick specific pages out of a multi-page document to send via email or print to local printer and make a specific point.
            </p>
        </div>
        {{-- @include ('presentation.footer') --}}
    </div>
@endsection
@push('scripts')
    <script src="{{ mix('js/home.js') }}"></script>
@endpush
