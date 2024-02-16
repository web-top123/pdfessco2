<div id="footer" class="presentation-footer {{ request()->path() === '/' ?  'welcome-footer' : ''}}">

    <div class="wrapper">
        <div class="left-side">All rights reserved by Pdfglue © {{ Carbon\Carbon::now()->format('Y') }}</div>

        <div class="links">
            <a class="link {{ request()->path() === 'about' ? 'active' : '' }}" href="/about">
                <div class="text">
                    <p>About</p>
                </div>
            </a>
            <a class="link {{ request()->path() === 'contact-us' ? 'active' : '' }}" href="/contact-us">
                <div class="text">
                    <p>Contact</p>
                </div>
            </a>
        </div>
    </div>
</div>
