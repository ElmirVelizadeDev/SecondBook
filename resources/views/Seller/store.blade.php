@extends('Layout.Seller.master')

@section('title', 'My Store')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/store.css') }}">
@endpush

@section('content')

    <div class="seller-store-page">

        {{-- =====================================================
             PAGE HEADER
             ===================================================== --}}
        <div class="seller-page-heading">

            <div>
                <h1>My Store</h1>
                <p>Manage your store information and profile.</p>
            </div>

            <a href="{{ route('frontend.home') }}" class="seller-outline-button">
                <i class="bi bi-shop"></i>
                <span>View Store</span>
            </a>

        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="seller-alert seller-alert-success" data-alert>

                <div class="seller-alert-icon">
                    <i class="bi bi-check-lg"></i>
                </div>

                <div class="seller-alert-text">
                    <strong>Success</strong>
                    <span>{{ session('success') }}</span>
                </div>

                <button
                    type="button"
                    class="seller-alert-close"
                    data-alert-close
                    aria-label="Close"
                >
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="seller-alert seller-alert-danger" data-alert>

                <div class="seller-alert-icon">
                    <i class="bi bi-exclamation-lg"></i>
                </div>

                <div class="seller-alert-text">
                    <strong>Please fix the following</strong>

                    @foreach($errors->all() as $error)
                        <span>{{ $error }}</span>
                    @endforeach
                </div>

                <button
                    type="button"
                    class="seller-alert-close"
                    data-alert-close
                    aria-label="Close"
                >
                    <i class="bi bi-x-lg"></i>
                </button>

            </div>
        @endif

        <form
            action="{{ route('seller.store.update') }}"
            method="POST"
            enctype="multipart/form-data"
            id="sellerStoreForm"
        >
            @csrf
            @method('PUT')

            <div class="row g-4">

                {{-- =================================================
                     STORE PREVIEW
                     ================================================= --}}
                <div class="col-xl-4">

                    <aside class="seller-store-card">

                        <div class="seller-store-cover"></div>

                        <div class="seller-store-profile">

                            <div class="seller-store-logo" id="storeCardLogo">

                                @if($store->logo)
                                    <img
                                        src="{{ asset('storage/' . $store->logo) }}"
                                        alt="{{ $store->name }}"
                                    >
                                @else
                                    <i class="bi bi-shop"></i>
                                @endif

                            </div>

                            <div class="seller-store-info">

                                <h4 id="storeCardName">
                                    {{ $store->name }}
                                </h4>

                                <span class="seller-store-slug">
                                    <i class="bi bi-link-45deg"></i>
                                    /{{ $store->slug }}
                                </span>

                            </div>

                            @if($store->description)
                                <p class="seller-store-about">
                                    {{ \Illuminate\Support\Str::limit($store->description, 140) }}
                                </p>
                            @endif

                        </div>

                        {{-- Meta --}}
                        <div class="seller-store-meta">

                            <div>
                                <span>Status</span>

                                <strong class="{{ $store->isActive() ? 'active' : 'inactive' }}">
                                    <i class="bi bi-circle-fill"></i>
                                    {{ ucfirst($store->status) }}
                                </strong>
                            </div>

                            <div>
                                <span>Created</span>

                                <strong>
                                    {{ $store->created_at?->format('M d, Y') }}
                                </strong>
                            </div>

                        </div>

                        {{-- Contact --}}
                        @if($store->phone || $store->address)
                            <div class="seller-store-contact">

                                @if($store->phone)
                                    <div class="seller-store-contact-item">

                                        <div class="seller-store-contact-icon">
                                            <i class="bi bi-telephone"></i>
                                        </div>

                                        <div>
                                            <span>Phone</span>
                                            <strong>{{ $store->phone }}</strong>
                                        </div>

                                    </div>
                                @endif

                                @if($store->address)
                                    <div class="seller-store-contact-item">

                                        <div class="seller-store-contact-icon">
                                            <i class="bi bi-geo-alt"></i>
                                        </div>

                                        <div>
                                            <span>Address</span>
                                            <strong>{{ $store->address }}</strong>
                                        </div>

                                    </div>
                                @endif

                            </div>
                        @endif

                    </aside>

                </div>

                {{-- =================================================
                     STORE SETTINGS
                     ================================================= --}}
                <div class="col-xl-8">

                    <div class="seller-store-form-card">

                        <div class="seller-form-header">

                            <div class="seller-form-header-icon">
                                <i class="bi bi-sliders"></i>
                            </div>

                            <div>
                                <h5>Store Information</h5>
                                <p>Update your public store information.</p>
                            </div>

                        </div>

                        <div class="seller-form-body">

                            {{-- Store Name --}}
                            <div class="seller-form-group @error('name') has-error @enderror">

                                <label for="name">
                                    Store Name
                                </label>

                                <div class="seller-input-wrap">

                                    <i class="bi bi-shop"></i>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $store->name) }}"
                                        placeholder="Enter store name"
                                        required
                                    >

                                </div>

                                @error('name')
                                    <div class="seller-field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Description --}}
                            <div class="seller-form-group @error('description') has-error @enderror">

                                <label for="description">
                                    Description
                                    <em id="descriptionCount">0</em>
                                </label>

                                <textarea
                                    id="description"
                                    name="description"
                                    rows="5"
                                    placeholder="Tell customers about your store..."
                                >{{ old('description', $store->description) }}</textarea>

                                @error('description')
                                    <div class="seller-field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="row g-3">

                                {{-- Phone --}}
                                <div class="col-md-6">

                                    <div class="seller-form-group @error('phone') has-error @enderror">

                                        <label for="phone">
                                            Phone
                                        </label>

                                        <div class="seller-input-wrap">

                                            <i class="bi bi-telephone"></i>

                                            <input
                                                type="text"
                                                id="phone"
                                                name="phone"
                                                value="{{ old('phone', $store->phone) }}"
                                                placeholder="Enter phone number"
                                            >

                                        </div>

                                        @error('phone')
                                            <div class="seller-field-error">
                                                <i class="bi bi-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                </div>

                                {{-- Address --}}
                                <div class="col-md-6">

                                    <div class="seller-form-group @error('address') has-error @enderror">

                                        <label for="address">
                                            Address
                                        </label>

                                        <div class="seller-input-wrap">

                                            <i class="bi bi-geo-alt"></i>

                                            <input
                                                type="text"
                                                id="address"
                                                name="address"
                                                value="{{ old('address', $store->address) }}"
                                                placeholder="Enter store address"
                                            >

                                        </div>

                                        @error('address')
                                            <div class="seller-field-error">
                                                <i class="bi bi-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                </div>

                            </div>

                            {{-- Logo --}}
                            <div class="seller-form-group @error('logo') has-error @enderror">

                                <label for="logo">
                                    Store Logo
                                </label>

                                <div class="seller-logo-upload" id="logoDropzone">

                                    <div class="seller-logo-preview" id="logoPreview">

                                        @if($store->logo)
                                            <img
                                                src="{{ asset('storage/' . $store->logo) }}"
                                                alt="Store Logo"
                                            >
                                        @else
                                            <i class="bi bi-image"></i>
                                        @endif

                                    </div>

                                    <div class="seller-logo-content">

                                        <label
                                            for="logo"
                                            class="seller-upload-button"
                                        >
                                            <i class="bi bi-upload"></i>
                                            Choose Logo
                                        </label>

                                        <input
                                            type="file"
                                            id="logo"
                                            name="logo"
                                            accept=".jpg,.jpeg,.png,.webp"
                                        >

                                        <p id="logoHint">
                                            JPG, JPEG, PNG or WEBP. Maximum 2MB.
                                        </p>

                                    </div>

                                </div>

                                @error('logo')
                                    <div class="seller-field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div
                                    class="seller-field-error"
                                    id="logoClientError"
                                    hidden
                                >
                                    <i class="bi bi-exclamation-circle"></i>
                                    <span>The selected file is larger than 2MB.</span>
                                </div>

                            </div>

                            {{-- Actions --}}
                            <div class="seller-form-actions">

                                <a
                                    href="{{ route('seller.dashboard') }}"
                                    class="seller-cancel-button"
                                >
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    class="seller-save-button"
                                >
                                    <i class="bi bi-check2"></i>
                                    <span>Save Changes</span>
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

    @push('js')
        <script>
            (function () {
                var form = document.getElementById('sellerStoreForm');
                var nameInput = document.getElementById('name');
                var cardName = document.getElementById('storeCardName');
                var desc = document.getElementById('description');
                var descCount = document.getElementById('descriptionCount');
                var logoInput = document.getElementById('logo');
                var logoPreview = document.getElementById('logoPreview');
                var cardLogo = document.getElementById('storeCardLogo');
                var logoHint = document.getElementById('logoHint');
                var logoError = document.getElementById('logoClientError');
                var dropzone = document.getElementById('logoDropzone');
                var MAX_SIZE = 2 * 1024 * 1024;

                // Dismissible alerts
                document.querySelectorAll('[data-alert]').forEach(function (alert) {
                    var close = alert.querySelector('[data-alert-close]');

                    if (close) {
                        close.addEventListener('click', function () {
                            alert.classList.add('is-hiding');

                            setTimeout(function () {
                                alert.remove();
                            }, 300);
                        });
                    }
                });

                // Live store name in preview card
                if (nameInput && cardName) {
                    nameInput.addEventListener('input', function () {
                        cardName.textContent =
                            nameInput.value.trim() || 'Your Store';
                    });
                }

                // Description counter
                if (desc && descCount) {
                    var updateCount = function () {
                        descCount.textContent =
                            desc.value.length + ' characters';
                    };

                    desc.addEventListener('input', updateCount);
                    updateCount();
                }

                // Logo preview
                function setImage(container, src) {
                    container.innerHTML = '';

                    var img = document.createElement('img');

                    img.src = src;
                    img.alt = 'Store Logo';

                    container.appendChild(img);
                }

                if (logoInput) {
                    logoInput.addEventListener('change', function () {
                        var file =
                            logoInput.files && logoInput.files[0];

                        if (logoError) {
                            logoError.hidden = true;
                        }

                        if (!file) {
                            return;
                        }

                        if (file.size > MAX_SIZE) {
                            logoInput.value = '';

                            if (logoError) {
                                logoError.hidden = false;
                            }

                            return;
                        }

                        var reader = new FileReader();

                        reader.onload = function (e) {
                            setImage(
                                logoPreview,
                                e.target.result
                            );

                            if (cardLogo) {
                                setImage(
                                    cardLogo,
                                    e.target.result
                                );
                            }
                        };

                        reader.readAsDataURL(file);

                        if (logoHint) {
                            logoHint.textContent =
                                file.name +
                                ' · ' +
                                (file.size / 1024).toFixed(0) +
                                ' KB';
                        }
                    });
                }

                // Drag & drop highlight
                if (dropzone && logoInput) {
                    ['dragenter', 'dragover'].forEach(function (evt) {
                        dropzone.addEventListener(evt, function (e) {
                            e.preventDefault();
                            dropzone.classList.add('is-dragging');
                        });
                    });

                    ['dragleave', 'drop'].forEach(function (evt) {
                        dropzone.addEventListener(evt, function (e) {
                            e.preventDefault();
                            dropzone.classList.remove('is-dragging');
                        });
                    });

                    dropzone.addEventListener('drop', function (e) {
                        if (
                            e.dataTransfer &&
                            e.dataTransfer.files.length
                        ) {
                            logoInput.files = e.dataTransfer.files;
                            logoInput.dispatchEvent(new Event('change'));
                        }
                    });
                }

                // Prevent double submit
                if (form) {
                    form.addEventListener('submit', function () {
                        var button =
                            form.querySelector('.seller-save-button');

                        if (button) {
                            setTimeout(function () {
                                button.disabled = true;
                                button.classList.add('is-loading');
                            }, 0);
                        }
                    });
                }
            })();
        </script>
    @endpush

@endsection

