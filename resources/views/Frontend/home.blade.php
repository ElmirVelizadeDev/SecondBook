@extends('Layout.Frontend.master')



@section('content')

    @include('Layout.Frontend.billboard')

    @include('Layout.Frontend.popular-books')

    @include('Layout.Frontend.categories')

    @include('Layout.Frontend.featured-books')

    @include('Layout.Frontend.why-choose')

    @include('Layout.Frontend.special-offer')

    @include('Layout.Frontend.quotation')

    @include('Layout.Frontend.latest-articles')

    @include('Layout.Frontend.subscribe')

@endsection

@push('js')
<script>
    document.addEventListener('submit', async function (event) {
        const form = event.target.closest('.add-to-cart-form');

        if (!form) {
            return;
        }

        event.preventDefault();

        const button = form.querySelector('.add-to-cart');

        if (!button || button.disabled) {
            return;
        }

        const originalHtml = button.innerHTML;

        const csrfInput = form.querySelector('input[name="_token"]');

        const csrfToken = csrfInput
            ? csrfInput.value
            : document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute('content');

        if (!csrfToken) {
            console.error('CSRF token not found.');
            return;
        }

        try {
            button.disabled = true;

            button.innerHTML = `
                <span class="cart-loading-spinner"></span>
                <span>Adding...</span>
            `;

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            });

            const data = await response.json();

            if (!response.ok || data.success !== true) {
                throw new Error(
                    data.message || 'Unable to add the book to cart.'
                );
            }

            const cartCount = document.getElementById('header-cart-count');

            if (cartCount && data.cart_count !== undefined) {
                const count = Number(data.cart_count);

                cartCount.textContent = count > 99 ? '99+' : count;

                cartCount.style.display =
                    count > 0 ? 'inline-flex' : 'none';

                cartCount.classList.remove('cart-count-bump');

                void cartCount.offsetWidth;

                cartCount.classList.add('cart-count-bump');
            }

            button.innerHTML = `
                <i class="bi bi-check-lg"></i>
                <span>Added</span>
            `;

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Added to Cart',
                    text: data.message || 'Book added to cart successfully!',
                    timer: 1600,
                    showConfirmButton: false,
                    customClass: {
                        icon: 'sb-cart-success-icon'
                    }
                });
            }

            setTimeout(function () {
                button.innerHTML = originalHtml;
                button.disabled = false;
            }, 1200);

        } catch (error) {
            console.error('Add to cart error:', error);

            button.innerHTML = originalHtml;
            button.disabled = false;

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Something went wrong',
                    text: error.message || 'Unable to add the book to cart.',
                    confirmButtonText: 'OK'
                });
            } else {
                alert(
                    error.message ||
                    'Unable to add the book to cart.'
                );
            }
        }
    });
</script>
@endpush
