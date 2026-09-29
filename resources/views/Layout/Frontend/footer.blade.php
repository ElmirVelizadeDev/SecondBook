<footer id="footer" class="site-footer">
    <div class="container">

        @php
            $siteName = \App\Models\Setting::get('site_name', 'SecondBook');
            $siteDescription = \App\Models\Setting::get(
                'site_description',
                'SecondBook is an online marketplace where readers can buy, sell and discover quality second-hand books at affordable prices.'
            );
            $supportEmail = \App\Models\Setting::get(
                'support_email',
                'support@secondbook.com'
            );
            $supportPhone = \App\Models\Setting::get(
                'support_phone',
                '+994 50 123 45 67'
            );
            $address = \App\Models\Setting::get(
                'address',
                'M.S.Ordubadi'
            );
            $city = \App\Models\Setting::get(
                'city',
                'Nakhchivan'
            );
            $country = \App\Models\Setting::get(
                'country',
                'Azerbaijan'
            );
            $logo = \App\Models\Setting::get('logo');

            $facebook = \App\Models\Setting::get('facebook');
            $instagram = \App\Models\Setting::get('instagram');
            $tiktok = \App\Models\Setting::get('tiktok');
            $youtube = \App\Models\Setting::get('youtube');
            $whatsapp = \App\Models\Setting::get('whatsapp');
        @endphp

        <div class="footer-main">

            {{-- Brand --}}
            <div class="footer-column footer-brand">
                <a href="{{ route('frontend.home') }}" class="footer-logo-link">
                    <img
                        src="{{ $logo
                            ? asset('storage/' . $logo)
                            : asset('main-logo.png')
                        }}"
                        alt="{{ $siteName }}"
                        class="footer-logo"
                    >
                </a>

                <p class="footer-description">
                    {{ $siteDescription }}
                </p>

                {{-- Social Profiles --}}
                <div class="footer-socials">

                    <a
                        href="{{ $facebook ?: '#' }}"
                        @if($facebook)
                            target="_blank"
                            rel="noopener noreferrer"
                        @endif
                        aria-label="Facebook"
                    >
                        <i class="bi bi-facebook"></i>
                    </a>

                    <a
                        href="{{ $instagram ?: '#' }}"
                        @if($instagram)
                            target="_blank"
                            rel="noopener noreferrer"
                        @endif
                        aria-label="Instagram"
                    >
                        <i class="bi bi-instagram"></i>
                    </a>

                    <a
                        href="{{ $tiktok ?: '#' }}"
                        @if($tiktok)
                            target="_blank"
                            rel="noopener noreferrer"
                        @endif
                        aria-label="TikTok"
                    >
                        <i class="bi bi-tiktok"></i>
                    </a>

                    <a
                        href="{{ $youtube ?: '#' }}"
                        @if($youtube)
                            target="_blank"
                            rel="noopener noreferrer"
                        @endif
                        aria-label="YouTube"
                    >
                        <i class="bi bi-youtube"></i>
                    </a>

                    <a
                        href="{{ $whatsapp
                            ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp)
                            : '#'
                        }}"
                        @if($whatsapp)
                            target="_blank"
                            rel="noopener noreferrer"
                        @endif
                        aria-label="WhatsApp"
                    >
                        <i class="bi bi-whatsapp"></i>
                    </a>

                </div>

                <p class="footer-copyright">
                    © {{ date('Y') }} {{ $siteName }}. All Rights Reserved.
                </p>
            </div>


            {{-- Quick Links --}}
            <div class="footer-column">
                <h5 class="footer-title">Quick Links</h5>

                <ul class="footer-links">
                    <li>
                        <a href="{{ route('frontend.home') }}">Home</a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.books') }}">Books</a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.categories') }}">Categories</a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.authors') }}">Authors</a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.about') }}">About Us</a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.contact') }}">Contact</a>
                    </li>
                </ul>
            </div>


            {{-- Customer Account --}}
            <div class="footer-column">
                <h5 class="footer-title">Customer Account</h5>

                <ul class="footer-links">

                    @guest
                        <li>
                            <a href="{{ route('frontend.auth.login') }}">
                                Login
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('frontend.auth.register') }}">
                                Register
                            </a>
                        </li>
                    @endguest

                    @auth
                        <li>
                            <a href="{{ route('my.profile') }}">
                                My Account
                            </a>
                        </li>
                    @endauth

                    <li>
                        <a href="{{ route('frontend.wishlist') }}">
                            Wishlist
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.cart') }}">
                            Shopping Cart
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.orders') }}">
                            My Orders
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.orders') }}">
                            Order Tracking
                        </a>
                    </li>

                </ul>
            </div>


            {{-- Customer Support --}}
            <div class="footer-column">
                <h5 class="footer-title">Customer Support</h5>

                <ul class="footer-links">
                    <li>
                        <a href="{{ route('frontend.help-center') }}">
                            Help Center
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.faq') }}">
                            FAQ
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.shipping-information') }}">
                            Shipping Information
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.return-policy') }}">
                            Return Policy
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.privacy-policy') }}">
                            Privacy Policy
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('frontend.auth.terms') }}">
                            Terms &amp; Conditions
                        </a>
                    </li>
                </ul>
            </div>


            {{-- Contact --}}
            <div class="footer-column footer-contact">
                <h5 class="footer-title">Contact</h5>

                <ul class="footer-contact-list">

                    <li>
                        <i class="bi bi-envelope"></i>
                        <span>{{ $supportEmail }}</span>
                    </li>

                    <li>
                        <i class="bi bi-telephone"></i>
                        <span>{{ $supportPhone }}</span>
                    </li>

                    <li>
                        <i class="bi bi-geo-alt"></i>
                        <span>
                            {{ $address }}{{ $city ? ', ' . $city : '' }}{{ $country ? ', ' . $country : '' }}
                        </span>
                    </li>

                    <li>
                        <i class="bi bi-clock"></i>
                        <span>Mon - Fri: 09:00 - 18:00</span>
                    </li>

                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <span>{{ $siteName }} Marketplace</span>

            <span class="footer-bottom-separator"></span>

            <span>Books worth reading. Prices worth loving.</span>
        </div>

    </div>
</footer>