{{-- =========================================================
    FOOTER BOTTOM BAR — SecondBook
========================================================== --}}

@php
    $bottomSiteName = \App\Models\Setting::get('site_name', 'SecondBook');
@endphp

<div id="footer-bottom" class="footer-bottom-bar">
    <div class="container">
        <div class="footer-bottom-inner">

            <p class="footer-copyright">
                © {{ date('Y') }} {{ $bottomSiteName }}. All Rights Reserved.
            </p>

            <nav class="footer-legal" aria-label="Legal links">
                <a href="{{ route('frontend.privacy-policy') }}">
                    Privacy Policy
                </a>

                <span class="footer-legal-divider"></span>

                <a href="{{ route('frontend.auth.terms') }}">
                    Terms of Service
                </a>

                <span class="footer-legal-divider"></span>

                <a href="{{ route('frontend.cookies') }}">
                    Cookies
                </a>
            </nav>

        </div>
    </div>
</div>