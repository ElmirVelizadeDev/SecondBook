@extends('Layout.Frontend.master')

@section('title', 'Become a Seller | SecondBook')

@push('css')
    <link rel="stylesheet" href="{{ asset('frontend-assets/css/seller-application.css') }}">
@endpush

@section('content')

<main class="sb-seller-page">

    <div class="container">

        {{-- =====================================================
             HERO
        ====================================================== --}}

        <section class="sb-seller-hero">

            <div class="sb-seller-hero-content">

                <span class="sb-seller-eyebrow">

                    <span class="sb-seller-eyebrow-icon">
                        <i class="bi bi-shop"></i>
                    </span>

                    Seller Program

                </span>

                <h1>
                    Build your own
                    <span>bookstore.</span>
                </h1>

                <p>
                    Turn your passion for books into your own SecondBook store.
                    Reach readers, manage your listings and grow your sales
                    from one place.
                </p>

            </div>

            <div class="sb-seller-hero-mark">

                <div class="sb-seller-hero-circle">
                    <i class="bi bi-shop-window"></i>
                </div>

                <span>
                    SECOND<br>
                    BOOK
                </span>

            </div>

        </section>


        {{-- =====================================================
             ALERTS
        ====================================================== --}}

        @if(session('success'))

            <div class="sb-seller-alert sb-seller-alert-success">

                <div class="sb-seller-alert-icon">
                    <i class="bi bi-check2"></i>
                </div>

                <div>

                    <strong>Application submitted</strong>

                    <p>
                        {{ session('success') }}
                    </p>

                </div>

            </div>

        @endif


        @if(session('info'))

            <div class="sb-seller-alert sb-seller-alert-info">

                <div class="sb-seller-alert-icon">
                    <i class="bi bi-info-lg"></i>
                </div>

                <div>

                    <strong>Seller program</strong>

                    <p>
                        {{ session('info') }}
                    </p>

                </div>

            </div>

        @endif


        @if($errors->any())

            <div class="sb-seller-alert sb-seller-alert-danger">

                <div class="sb-seller-alert-icon">
                    <i class="bi bi-exclamation-lg"></i>
                </div>

                <div>

                    <strong>Please review your application</strong>

                    <ul>

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        @endif


        {{-- =====================================================
             PENDING APPLICATION
        ====================================================== --}}

        @if($application && $application->status === 'pending')

            <section class="sb-seller-status">

                <div class="sb-seller-status-visual">

                    <div class="sb-seller-status-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>

                    <span class="sb-seller-status-pulse"></span>

                </div>


                <div class="sb-seller-status-content">

                    <span class="sb-seller-section-label">
                        Application status
                    </span>

                    <h2>
                        Your application is
                        <span>under review.</span>
                    </h2>

                    <p>
                        Your seller application has been submitted successfully.
                        Our administration team will review your information
                        and update your application status.
                    </p>

                    <div class="sb-seller-status-meta">

                        <span class="sb-seller-status-badge pending">
                            <i class="bi bi-clock"></i>
                            Pending review
                        </span>

                        <span class="sb-seller-status-note">
                            <i class="bi bi-shield-check"></i>
                            Reviewed by SecondBook
                        </span>

                    </div>

                </div>

            </section>


        {{-- =====================================================
             REJECTED APPLICATION
        ====================================================== --}}

        @elseif($application && $application->status === 'rejected')


            {{-- =================================================
                 REJECTION STATUS
            ================================================== --}}

            <section class="sb-seller-status rejected">

                <div class="sb-seller-status-visual">

                    <div class="sb-seller-status-icon">
                        <i class="bi bi-x-lg"></i>
                    </div>

                </div>


                <div class="sb-seller-status-content">

                    <span class="sb-seller-section-label">
                        Application status
                    </span>

                    <h2>
                        Let's try
                        <span>again.</span>
                    </h2>

                    <p>
                        Your previous seller application was rejected.
                        Review the information below and submit a new
                        application with the necessary changes.
                    </p>


                    @if($application->rejection_reason)

                        <div class="sb-seller-rejection">

                            <span>
                                <i class="bi bi-chat-left-text"></i>
                                Review note
                            </span>

                            <p>
                                {{ $application->rejection_reason }}
                            </p>

                        </div>

                    @endif

                </div>

            </section>


            {{-- =================================================
                 NEW APPLICATION FORM AFTER REJECTION
            ================================================== --}}

            <section class="sb-seller-application-grid">


                {{-- =================================================
                     INFORMATION PANEL
                ================================================== --}}

                <aside class="sb-seller-info">

                    <div class="sb-seller-info-top">

                        <span class="sb-seller-info-label">
                            Apply again
                        </span>

                        <div class="sb-seller-info-icon">
                            <i class="bi bi-book-half"></i>
                        </div>

                        <h2>
                            Improve your
                            <span>application.</span>
                        </h2>

                        <p>
                            Update your information based on the review
                            and submit a new seller application.
                        </p>

                    </div>


                    <div class="sb-seller-benefits">

                        <div class="sb-seller-benefit">

                            <div class="sb-seller-benefit-number">
                                01
                            </div>

                            <div class="sb-seller-benefit-icon">
                                <i class="bi bi-pencil-square"></i>
                            </div>

                            <div class="sb-seller-benefit-content">

                                <h3>
                                    Update your details
                                </h3>

                                <p>
                                    Review your store information and
                                    make any necessary changes.
                                </p>

                            </div>

                        </div>


                        <div class="sb-seller-benefit">

                            <div class="sb-seller-benefit-number">
                                02
                            </div>

                            <div class="sb-seller-benefit-icon">
                                <i class="bi bi-shop"></i>
                            </div>

                            <div class="sb-seller-benefit-content">

                                <h3>
                                    Refine your store
                                </h3>

                                <p>
                                    Give readers a clearer idea of the
                                    books and experience you offer.
                                </p>

                            </div>

                        </div>


                        <div class="sb-seller-benefit">

                            <div class="sb-seller-benefit-number">
                                03
                            </div>

                            <div class="sb-seller-benefit-icon">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>

                            <div class="sb-seller-benefit-content">

                                <h3>
                                    Submit again
                                </h3>

                                <p>
                                    Send your updated application for
                                    another review by our team.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="sb-seller-info-footer">

                        <i class="bi bi-stars"></i>

                        <span>
                            Your next application can be
                            stronger than the first.
                        </span>

                    </div>

                </aside>


                {{-- =================================================
                     APPLICATION FORM
                ================================================== --}}

                <div class="sb-seller-form-card">

                    <div class="sb-seller-form-heading">

                        <div>

                            <span class="sb-seller-section-label">
                                New seller application
                            </span>

                            <h2>
                                Tell us about
                                <span>your store.</span>
                            </h2>

                        </div>


                        <div class="sb-seller-form-step">

                            <span>
                                01
                            </span>

                            <small>
                                Re-application
                            </small>

                        </div>

                    </div>


                    <div class="sb-seller-form-intro">
                        Update your details and submit your application
                        again for another review.
                    </div>


                    <form
                        action="{{ route('frontend.seller-application.store') }}"
                        method="POST"
                        class="sb-seller-form sb-seller-reapply-form"
                    >

                        @csrf


                        {{-- =================================================
                             STORE NAME
                        ================================================== --}}

                        <div class="sb-seller-field">

                            <label for="store_name">
                                Store name
                                <span>*</span>
                            </label>

                            <div class="sb-seller-input">

                                <i class="bi bi-shop"></i>

                                <input
                                    type="text"
                                    id="store_name"
                                    name="store_name"
                                    value="{{ old('store_name') }}"
                                    placeholder="e.g. The Reading Corner"
                                    maxlength="100"
                                    required
                                >

                            </div>


                            @error('store_name')

                                <small class="sb-seller-field-error">

                                    <i class="bi bi-exclamation-circle"></i>

                                    {{ $message }}

                                </small>

                            @enderror

                        </div>


                        {{-- =================================================
                             DESCRIPTION
                        ================================================== --}}

                        <div class="sb-seller-field">

                            <label for="description">
                                Store description
                            </label>

                            <div class="sb-seller-textarea">

                                <textarea
                                    id="description"
                                    name="description"
                                    rows="5"
                                    maxlength="1000"
                                    placeholder="Tell readers about your store and the books you plan to sell..."
                                >{{ old('description') }}</textarea>

                                <span>
                                    <i class="bi bi-pencil"></i>
                                </span>

                            </div>


                            @error('description')

                                <small class="sb-seller-field-error">

                                    <i class="bi bi-exclamation-circle"></i>

                                    {{ $message }}

                                </small>

                            @enderror

                        </div>


                        {{-- =================================================
                             PHONE + ADDRESS
                        ================================================== --}}

                        <div class="sb-seller-fields-row">


                            {{-- Phone --}}

                            <div class="sb-seller-field">

                                <label for="phone">
                                    Phone number
                                </label>

                                <div class="sb-seller-input">

                                    <i class="bi bi-telephone"></i>

                                    <input
                                        type="text"
                                        id="phone"
                                        name="phone"
                                        value="{{ old('phone', auth()->user()->phone) }}"
                                        placeholder="+994 XX XXX XX XX"
                                        maxlength="15"
                                        inputmode="numeric"
                                        autocomplete="tel"
                                    >

                                </div>


                                @error('phone')

                                    <small class="sb-seller-field-error">

                                        <i class="bi bi-exclamation-circle"></i>

                                        {{ $message }}

                                    </small>

                                @enderror

                            </div>


                            {{-- Address --}}

                            <div class="sb-seller-field">

                                <label for="address">
                                    Address
                                </label>

                                <div class="sb-seller-input">

                                    <i class="bi bi-geo-alt"></i>

                                    <input
                                        type="text"
                                        id="address"
                                        name="address"
                                        value="{{ old('address', auth()->user()->address) }}"
                                        placeholder="City, street..."
                                        maxlength="255"
                                        autocomplete="street-address"
                                    >

                                </div>


                                @error('address')

                                    <small class="sb-seller-field-error">

                                        <i class="bi bi-exclamation-circle"></i>

                                        {{ $message }}

                                    </small>

                                @enderror

                            </div>

                        </div>


                        {{-- =================================================
                             FORM FOOTER
                        ================================================== --}}

                        <div class="sb-seller-form-footer">

                            <div class="sb-seller-security">

                                <span class="sb-seller-security-icon">
                                    <i class="bi bi-shield-check"></i>
                                </span>

                                <div>

                                    <strong>
                                        Your information is reviewed securely.
                                    </strong>

                                    <span>
                                        Our administration team will review
                                        your new application before approval.
                                    </span>

                                </div>

                            </div>


                            <button
                                type="submit"
                                class="sb-seller-submit"
                            >

                                <span>
                                    Submit application
                                </span>

                                <i class="bi bi-arrow-up-right"></i>

                            </button>

                        </div>

                    </form>

                </div>

            </section>


        {{-- =====================================================
             APPLICATION FORM
             NO APPLICATION
        ====================================================== --}}

        @else

            <section class="sb-seller-application-grid">


                {{-- =================================================
                     INFORMATION PANEL
                ================================================== --}}

                <aside class="sb-seller-info">

                    <div class="sb-seller-info-top">

                        <span class="sb-seller-info-label">
                            Why sell on SecondBook?
                        </span>

                        <div class="sb-seller-info-icon">
                            <i class="bi bi-book-half"></i>
                        </div>

                        <h2>
                            Your books.
                            <span>Your store.</span>
                        </h2>

                        <p>
                            Everything you need to turn your collection
                            into a professional online bookstore.
                        </p>

                    </div>


                    <div class="sb-seller-benefits">

                        <div class="sb-seller-benefit">

                            <div class="sb-seller-benefit-number">
                                01
                            </div>

                            <div class="sb-seller-benefit-icon">
                                <i class="bi bi-book"></i>
                            </div>

                            <div class="sb-seller-benefit-content">

                                <h3>
                                    Sell your books
                                </h3>

                                <p>
                                    List your books and make them
                                    available to new readers.
                                </p>

                            </div>

                        </div>


                        <div class="sb-seller-benefit">

                            <div class="sb-seller-benefit-number">
                                02
                            </div>

                            <div class="sb-seller-benefit-icon">
                                <i class="bi bi-shop"></i>
                            </div>

                            <div class="sb-seller-benefit-content">

                                <h3>
                                    Own your storefront
                                </h3>

                                <p>
                                    Create a dedicated store identity
                                    inside SecondBook.
                                </p>

                            </div>

                        </div>


                        <div class="sb-seller-benefit">

                            <div class="sb-seller-benefit-number">
                                03
                            </div>

                            <div class="sb-seller-benefit-icon">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>

                            <div class="sb-seller-benefit-content">

                                <h3>
                                    Manage your sales
                                </h3>

                                <p>
                                    Keep track of your books, orders
                                    and sales from your seller panel.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="sb-seller-info-footer">

                        <i class="bi bi-stars"></i>

                        <span>
                            A simple way to start your
                            SecondBook journey.
                        </span>

                    </div>

                </aside>


                {{-- =================================================
                     APPLICATION FORM
                ================================================== --}}

                <div class="sb-seller-form-card">

                    <div class="sb-seller-form-heading">

                        <div>

                            <span class="sb-seller-section-label">
                                Seller application
                            </span>

                            <h2>
                                Tell us about
                                <span>your store.</span>
                            </h2>

                        </div>


                        <div class="sb-seller-form-step">

                            <span>
                                01
                            </span>

                            <small>
                                Application
                            </small>

                        </div>

                    </div>


                    <div class="sb-seller-form-intro">
                        Share a few details about your store.
                        You can update your information later.
                    </div>


                    <form
                        action="{{ route('frontend.seller-application.store') }}"
                        method="POST"
                        class="sb-seller-form"
                    >

                        @csrf


                        {{-- =================================================
                             STORE NAME
                        ================================================== --}}

                        <div class="sb-seller-field">

                            <label for="store_name">
                                Store name
                                <span>*</span>
                            </label>

                            <div class="sb-seller-input">

                                <i class="bi bi-shop"></i>

                                <input
                                    type="text"
                                    id="store_name"
                                    name="store_name"
                                    value="{{ old('store_name') }}"
                                    placeholder="e.g. The Reading Corner"
                                    maxlength="100"
                                    required
                                >

                            </div>


                            @error('store_name')

                                <small class="sb-seller-field-error">

                                    <i class="bi bi-exclamation-circle"></i>

                                    {{ $message }}

                                </small>

                            @enderror

                        </div>


                        {{-- =================================================
                             DESCRIPTION
                        ================================================== --}}

                        <div class="sb-seller-field">

                            <label for="description">
                                Store description
                            </label>

                            <div class="sb-seller-textarea">

                                <textarea
                                    id="description"
                                    name="description"
                                    rows="5"
                                    maxlength="1000"
                                    placeholder="Tell readers about your store and the books you plan to sell..."
                                >{{ old('description') }}</textarea>

                                <span>
                                    <i class="bi bi-pencil"></i>
                                </span>

                            </div>


                            @error('description')

                                <small class="sb-seller-field-error">

                                    <i class="bi bi-exclamation-circle"></i>

                                    {{ $message }}

                                </small>

                            @enderror

                        </div>


                        {{-- =================================================
                             PHONE + ADDRESS
                        ================================================== --}}

                        <div class="sb-seller-fields-row">


                            {{-- Phone --}}

                            <div class="sb-seller-field">

                                <label for="phone">
                                    Phone number
                                </label>

                                <div class="sb-seller-input">

                                    <i class="bi bi-telephone"></i>

                                    <input
                                        type="text"
                                        id="phone"
                                        name="phone"
                                        value="{{ old('phone', auth()->user()->phone) }}"
                                        placeholder="+994 XX XXX XX XX"
                                        maxlength="15"
                                        inputmode="numeric"
                                        autocomplete="tel"
                                    >

                                </div>


                                @error('phone')

                                    <small class="sb-seller-field-error">

                                        <i class="bi bi-exclamation-circle"></i>

                                        {{ $message }}

                                    </small>

                                @enderror

                            </div>


                            {{-- Address --}}

                            <div class="sb-seller-field">

                                <label for="address">
                                    Address
                                </label>

                                <div class="sb-seller-input">

                                    <i class="bi bi-geo-alt"></i>

                                    <input
                                        type="text"
                                        id="address"
                                        name="address"
                                        value="{{ old('address', auth()->user()->address) }}"
                                        placeholder="City, street..."
                                        maxlength="255"
                                        autocomplete="street-address"
                                    >

                                </div>


                                @error('address')

                                    <small class="sb-seller-field-error">

                                        <i class="bi bi-exclamation-circle"></i>

                                        {{ $message }}

                                    </small>

                                @enderror

                            </div>

                        </div>


                        {{-- =================================================
                             FORM FOOTER
                        ================================================== --}}

                        <div class="sb-seller-form-footer">

                            <div class="sb-seller-security">

                                <span class="sb-seller-security-icon">
                                    <i class="bi bi-shield-check"></i>
                                </span>

                                <div>

                                    <strong>
                                        Your information is reviewed securely.
                                    </strong>

                                    <span>
                                        Our administration team will review
                                        your application before approval.
                                    </span>

                                </div>

                            </div>


                            <button
                                type="submit"
                                class="sb-seller-submit"
                            >

                                <span>
                                    Submit application
                                </span>

                                <i class="bi bi-arrow-up-right"></i>

                            </button>

                        </div>

                    </form>

                </div>

            </section>

        @endif

    </div>

</main>


{{-- =========================================================
     PHONE INPUT RESTRICTION
========================================================= --}}

@push('js')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const phoneInput = document.getElementById('phone');

    if (!phoneInput) {
        return;
    }


    function cleanPhone(value) {

        /*
         * Allow only:
         * - numbers
         * - + only at the beginning
         */

        value = value.replace(/[^0-9+]/g, '');

        if (value.startsWith('+')) {

            value =
                '+' +
                value.substring(1).replace(/\+/g, '');

        } else {

            value = value.replace(/\+/g, '');

        }


        /*
         * Maximum 15 characters
         */

        return value.substring(0, 15);
    }


    /*
     * Normal typing + paste
     */

    phoneInput.addEventListener('input', function () {

        const cleanedValue =
            cleanPhone(this.value);

        if (this.value !== cleanedValue) {
            this.value = cleanedValue;
        }

    });


    /*
     * Prevent invalid characters before they are entered
     */

    phoneInput.addEventListener('keydown', function (event) {

        const allowedKeys = [

            'Backspace',
            'Delete',

            'ArrowLeft',
            'ArrowRight',
            'ArrowUp',
            'ArrowDown',

            'Home',
            'End',

            'Tab'

        ];


        if (allowedKeys.includes(event.key)) {
            return;
        }


        /*
         * Allow Ctrl/Cmd shortcuts
         */

        if (
            event.ctrlKey ||
            event.metaKey
        ) {
            return;
        }


        /*
         * Allow numbers
         */

        if (/^[0-9]$/.test(event.key)) {
            return;
        }


        /*
         * Allow + only as first character
         */

        if (
            event.key === '+' &&
            this.selectionStart === 0 &&
            !this.value.includes('+')
        ) {
            return;
        }


        event.preventDefault();

    });


    /*
     * Extra protection before form submission
     */

    const form =
        phoneInput.closest('form');

    if (form) {

        form.addEventListener('submit', function () {

            phoneInput.value =
                cleanPhone(phoneInput.value);

        });

    }

});

</script>

@endpush

@endsection