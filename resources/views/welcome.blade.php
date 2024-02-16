@extends ('layouts.master')

@section ('content')
<div class="main-wrapper welcome-page">
    <div class="welcome-decorations">
    <div class="blue-component">
        <div class="blue-component_image-holder"><img src="/images/blue-drop-crop.jpg" class="blue-component_image" /></div> 
    </div>
    <div class="first-white-component">
        <div class="white-component_image-holder"><img src="/images/white-first-right-crop.png" class="white-component_image" /></div>
    </div>
    <div class="second-white-component">
        <div class="white-component_image-holder"><img src="/images/white-second-left-crop.png" class="white-component_image" /></div>
    </div>
    <div class="third-white-component">
        <div class="white-component_image-holder"><img src="/images/white-third-right-crop.png" class="white-component_image" /></div>
    </div>
    </div>

    @include ('presentation.navbar')
    <div class="content container">
        <section class="container intro-section" data-index="1">
            <div class="col-6">
                <h1 class="main-heading"><strong>Pdfglue</strong> is a modern up to date method of assembling custom pdf documents for individual projects.</h1>

                <div class="cta-segment">
                    <input class="input register-input" v-model="startedEmail" placeholder="Your Email Address" /> <a class="button is-cta" :href="'/login?email=' + startedEmail">Get Started</a>
                </div>
                <div class="hero-description">
                    <p>Projects which change constantly can be difficult to keep literature, documentation and data sheets up to date. In today’s world customers, end users and support staff demand up to date information.</p>
                    <p><strong>Pdfglue</strong> was born to address that need. It is based on uploading static pdf files to the server, attaching a meaningful label to each file and reusing the pages as needed regardless of how many changes a document may require.</p>
                    <p>Documents can be saved on the server (for a limited time), viewed online, saved to your local hard drive and/or emailed. The insures an accurate document with a minimum of effort.</p>
                </div>
            </div>
            <div class="col-6">
                <div class="hero-picture"><img src="/images/hero-picture.png" /></div>
            </div>
        </section>

        <section class="container listing-section" data-index="2">
            <h2 class="secondary-heading">Who can benefit from <strong>Pdfglue?</strong></h2>
            <div class="section-row odd">
                <div class="col-6 image"><img src="/images/picture-sales.jpg" /></div>
                <div class="col-6 description">
                    <h3 class="description-title">Sales People</h3>
                    <p class="description-content">Sales people can attach specific information pertaining to any prepared proposal. No more sending general information and confusing our customer. You can be specific!</p>
                </div>
            </div>
            <div class="section-row even">
                <div class="col-6 image"><img src="/images/picture-support.jpg" /></div>
                <div class="col-6 description">
                    <h3 class="description-title">Support People</h3>
                    <p class="description-content">Support people can now provide detailed information on any issue. No more sending a large document and leaving irrelevant infor-mation applied to a problem!</p>
                </div>
            </div>
            <div class="section-row odd">
                <div class="col-6 image"><img src="/images/picture-management.jpg" /></div>
                <div class="col-6 description">
                    <h3 class="description-title">Management</h3>
                    <p class="description-content">Now managers can supply only what information is necessary to address an issue. For example: Pick pages 2,7,8 & 15 out of a 30 page document ot send via email or print to local printer and make a specific point.</p>
                </div>
            </div>
        </section>
        <section class="container conclusion-section" data-index="3">
            <div class="welcome-row-content">
                <h1 class="welcome-anyone">Anyone can benefit from pdfglue!</h1>
                <h1 class="welcome-save">Save time, save money, be more accurate.</h1>
                <a class="button is-info welcome-get-started" href="/login">Get Started</a>

                <p class="footer-contact">For any further information regarding our website
                    please contact us at: <a class="footer-contact-link" href="mailto:pdfglue@pdfglue.com" target="_top">pdfglue@pdfglue.com</a> </p>
            </div>
        </section>
    </div>

</div>

</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/home.js') }}"></script>
@endpush
