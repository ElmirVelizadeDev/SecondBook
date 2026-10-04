@extends('Layout.Seller.master')

@section('title', 'Orders')

@push('css')
    <link rel="stylesheet" href="{{ asset('seller/css/orders.css') }}">
@endpush

@section('content')

@php
    $hasFilters = request()->hasAny([
        'search',
        'order_status',
        'payment_status',
    ]);
@endphp

<div class="seller-orders-page">

    {{-- =====================================================
        PAGE HEADER
        ===================================================== --}}

    <div class="seller-page-heading">
        <div>
            <h2>Orders</h2>
            <p>Manage orders for your books</p>
        </div>
    </div>

    {{-- =====================================================
        STATISTICS
        ===================================================== --}}

    <div class="seller-orders-stats">

        <div class="seller-order-stat-card">
            <div class="seller-order-stat-icon">
                <i class="bi bi-bag-check"></i>
            </div>

            <div>
                <span>Total Orders</span>
                <strong id="totalOrdersCount">
                    {{ $totalOrders }}
                </strong>
            </div>
        </div>

        <div class="seller-order-stat-card">
            <div class="seller-order-stat-icon pending">
                <i class="bi bi-clock-history"></i>
            </div>

            <div>
                <span>Pending</span>
                <strong>{{ $pendingOrders }}</strong>
            </div>
        </div>

        <div class="seller-order-stat-card">
            <div class="seller-order-stat-icon processing">
                <i class="bi bi-arrow-repeat"></i>
            </div>

            <div>
                <span>Processing</span>
                <strong>{{ $processingOrders }}</strong>
            </div>
        </div>

        <div class="seller-order-stat-card">
            <div class="seller-order-stat-icon delivered">
                <i class="bi bi-check-circle"></i>
            </div>

            <div>
                <span>Delivered</span>
                <strong>{{ $deliveredOrders }}</strong>
            </div>
        </div>

    </div>

    {{-- =====================================================
        FILTERS
        ===================================================== --}}

    <div class="seller-orders-filter-card">

        <form
            action="{{ route('seller.orders.index') }}"
            method="GET"
            class="seller-orders-filter-form"
        >

            {{-- Search --}}

            <div class="seller-order-search">

                <div class="seller-order-search-input">
                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search order, customer or book..."
                    >
                </div>

                <button
                    type="submit"
                    class="seller-order-search-button"
                >
                    <i class="bi bi-search"></i>
                    <span>Search</span>
                </button>

            </div>

            {{-- Order Status --}}

            <div class="seller-order-filter-select">

                <label for="order_status">
                    Order Status
                </label>

                <select
                    name="order_status"
                    id="order_status"
                >
                    <option value="">
                        All Statuses
                    </option>

                    <option
                        value="pending"
                        {{ request('order_status') === 'pending' ? 'selected' : '' }}
                    >
                        Pending
                    </option>

                    <option
                        value="processing"
                        {{ request('order_status') === 'processing' ? 'selected' : '' }}
                    >
                        Processing
                    </option>

                    <option
                        value="shipped"
                        {{ request('order_status') === 'shipped' ? 'selected' : '' }}
                    >
                        Shipped
                    </option>

                    <option
                        value="delivered"
                        {{ request('order_status') === 'delivered' ? 'selected' : '' }}
                    >
                        Delivered
                    </option>

                    <option
                        value="cancelled"
                        {{ request('order_status') === 'cancelled' ? 'selected' : '' }}
                    >
                        Cancelled
                    </option>
                </select>

            </div>

            {{-- Payment Status --}}

            <div class="seller-order-filter-select">

                <label for="payment_status">
                    Payment Status
                </label>

                <select
                    name="payment_status"
                    id="payment_status"
                >
                    <option value="">
                        All Payments
                    </option>

                    <option
                        value="pending"
                        {{ request('payment_status') === 'pending' ? 'selected' : '' }}
                    >
                        Pending
                    </option>

                    <option
                        value="paid"
                        {{ request('payment_status') === 'paid' ? 'selected' : '' }}
                    >
                        Paid
                    </option>

                    <option
                        value="failed"
                        {{ request('payment_status') === 'failed' ? 'selected' : '' }}
                    >
                        Failed
                    </option>

                    <option
                        value="refunded"
                        {{ request('payment_status') === 'refunded' ? 'selected' : '' }}
                    >
                        Refunded
                    </option>
                </select>

            </div>

            {{-- Filter --}}

            <button
                type="submit"
                class="seller-order-filter-button"
            >
                <i class="bi bi-funnel"></i>
                <span>Filter</span>
            </button>

            {{-- Reset --}}

            @if($hasFilters)
                <a
                    href="{{ route('seller.orders.index') }}"
                    class="seller-order-reset-button"
                >
                    <i class="bi bi-arrow-counterclockwise"></i>
                    <span>Reset</span>
                </a>
            @endif

        </form>

    </div>

    {{-- =====================================================
        AJAX ORDERS CONTENT
        ===================================================== --}}

    <div id="sellerOrdersContent">

        <div class="seller-orders-card">

            {{-- =================================================
                ORDERS HEADER
                ================================================== --}}

            <div class="seller-orders-card-header">

                <div class="seller-orders-card-title">

                    <div class="seller-orders-card-icon">
                        <i class="bi bi-bag-check"></i>
                    </div>

                    <div>
                        <h5>Recent Orders</h5>

                        <p id="ordersFoundText">
                            {{ $orders->total() }}
                            {{ Str::plural('order', $orders->total()) }}
                            found
                        </p>
                    </div>

                </div>

            </div>

            {{-- =================================================
                ORDERS
                ================================================== --}}

            @if($orders->count())

                <div class="seller-orders-table-wrapper">

                    <table class="seller-orders-table">

                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Book</th>
                                <th>Customer</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($orders as $order)

                                @php
                                    $paymentStatusClass = match($order->payment_status) {
                                        'paid' => 'paid',
                                        'failed' => 'failed',
                                        'refunded' => 'refunded',
                                        default => 'pending',
                                    };

                                    $orderStatusClass = match($order->order_status) {
                                        'processing' => 'processing',
                                        'shipped' => 'shipped',
                                        'delivered' => 'delivered',
                                        'cancelled' => 'cancelled',
                                        default => 'pending',
                                    };
                                @endphp

                                <tr id="order-row-{{ $order->id }}">

                                    {{-- Order --}}

                                    <td>
                                        <div class="seller-order-number">
                                            <span>
                                                #{{ $order->order_number }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Book --}}

                                    <td>

                                        <div class="seller-order-book">

                                            <div class="seller-order-book-cover">

                                                @if($order->book?->cover)

                                                    <img
                                                        src="{{ asset('storage/' . $order->book->cover) }}"
                                                        alt="{{ $order->book->title }}"
                                                    >

                                                @else

                                                    <div class="seller-order-no-cover">
                                                        <i class="bi bi-book"></i>
                                                    </div>

                                                @endif

                                            </div>

                                            <div class="seller-order-book-info">

                                                <strong>
                                                    {{ Str::limit(
                                                        $order->book->title ?? 'Deleted Book',
                                                        30
                                                    ) }}
                                                </strong>

                                                @if($order->book?->isbn)

                                                    <span>
                                                        ISBN: {{ $order->book->isbn }}
                                                    </span>

                                                @endif

                                            </div>

                                        </div>

                                    </td>

                                    {{-- Customer --}}

                                    <td>

                                        <div class="seller-order-customer">

                                            <div class="seller-order-customer-avatar">

                                                @if($order->user?->profile_photo)

                                                    <img
                                                        src="{{ asset('storage/' . $order->user->profile_photo) }}"
                                                        alt="{{ $order->user->name }}"
                                                    >

                                                @else

                                                    <span>
                                                        {{ strtoupper(
                                                            substr(
                                                                $order->user->name ?? 'U',
                                                                0,
                                                                1
                                                            )
                                                        ) }}
                                                    </span>

                                                @endif

                                            </div>

                                            <div class="seller-order-customer-info">

                                                <strong>
                                                    {{ $order->user->name ?? 'Unknown Customer' }}
                                                </strong>

                                                @if($order->user?->email)

                                                    <span>
                                                        {{ $order->user->email }}
                                                    </span>

                                                @endif

                                            </div>

                                        </div>

                                    </td>

                                    {{-- Quantity --}}

                                    <td>
                                        <span class="seller-order-quantity">
                                            {{ $order->quantity }}
                                        </span>
                                    </td>

                                    {{-- Total --}}

                                    <td>
                                        <strong class="seller-order-total">
                                            ${{ number_format($order->total_price, 2) }}
                                        </strong>
                                    </td>

                                    {{-- Payment --}}

                                    <td>

                                        <span class="seller-payment-badge {{ $paymentStatusClass }}">
                                            <i></i>
                                            {{ ucfirst($order->payment_status ?? 'pending') }}
                                        </span>

                                    </td>

                                    {{-- Status --}}

                                    <td>

                                        <span class="seller-order-status-badge {{ $orderStatusClass }}">
                                            <i></i>
                                            {{ ucfirst($order->order_status ?? 'pending') }}
                                        </span>

                                    </td>

                                    {{-- Date --}}

                                    <td>

                                        <div class="seller-order-date">

                                            <strong>
                                                {{ $order->created_at->format('M d, Y') }}
                                            </strong>

                                            <span>
                                                {{ $order->created_at->format('H:i') }}
                                            </span>

                                        </div>

                                    </td>

                                    {{-- Actions --}}

                                    <td>

                                        <div class="seller-order-actions">

                                            <a
                                                href="{{ route('seller.orders.show', $order) }}"
                                                class="seller-order-view-button"
                                                title="View Order"
                                            >
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            @if(in_array($order->order_status, [
                                                'pending',
                                                'cancelled'
                                            ]))

                                                <button
                                                    type="button"
                                                    class="seller-order-delete-button"
                                                    title="Delete Order"
                                                    data-delete-order="{{ $order->id }}"
                                                    data-order-number="{{ $order->order_number }}"
                                                    data-delete-url="{{ url('seller/orders/' . $order->id) }}"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- Pagination --}}

                @if($orders->hasPages())

                    <div class="seller-orders-pagination">
                        {{ $orders->links() }}
                    </div>

                @endif

            @else

                {{-- Empty State --}}

                <div class="seller-orders-empty">

                    <div class="seller-orders-empty-icon">
                        <i class="bi bi-bag-x"></i>
                    </div>

                    <h4>No orders found</h4>

                    <p>

                        @if($hasFilters)

                            No orders match your current filters.

                        @else

                            You don't have any orders for your books yet.

                        @endif

                    </p>

                    @if($hasFilters)

                        <a
                            href="{{ route('seller.orders.index') }}"
                            class="seller-order-empty-button"
                        >
                            <i class="bi bi-arrow-counterclockwise"></i>
                            <span>Clear Filters</span>
                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>

    {{-- =====================================================
        DELETE FORM
        ===================================================== --}}

    <form
        id="deleteOrderForm"
        method="POST"
        hidden
    >
        @csrf
        @method('DELETE')
    </form>

    {{-- =====================================================
        DELETE CONFIRMATION MODAL
        ===================================================== --}}

    <div
        class="seller-modal-overlay"
        id="deleteOrderModal"
    >

        <div class="seller-delete-modal">

            <button
                type="button"
                class="seller-modal-close"
                id="closeDeleteModal"
                aria-label="Close"
            >
                <i class="bi bi-x-lg"></i>
            </button>

            <div class="seller-delete-icon">
                <i class="bi bi-trash3"></i>
            </div>

            <h4>Delete Order?</h4>

            <p>
                Are you sure you want to delete
                <strong id="deleteOrderNumber"></strong>?
                <br>
                This action cannot be undone.
            </p>

            <div class="seller-delete-actions">

                <button
                    type="button"
                    class="seller-modal-cancel"
                    id="cancelDelete"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="seller-modal-delete"
                    id="confirmDelete"
                >
                    <i class="bi bi-trash3"></i>
                    <span>Delete Order</span>
                </button>

            </div>

        </div>

    </div>

    {{-- =====================================================
        TOAST
        ===================================================== --}}

    <div
        class="seller-success-alert"
        id="sellerSuccessAlert"
        hidden
    >

        <div class="seller-success-icon">
            <i
                class="bi bi-check-lg"
                id="sellerAlertIcon"
            ></i>
        </div>

        <div class="seller-success-content">

            <strong id="sellerAlertTitle">
                Success
            </strong>

            <span id="sellerSuccessMessage">
                Order deleted successfully.
            </span>

        </div>

        <button
            type="button"
            class="seller-success-close"
            id="closeSuccessAlert"
            aria-label="Close"
        >
            <i class="bi bi-x-lg"></i>
        </button>

    </div>

</div>

@endsection


@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const $ = id => document.getElementById(id);

    const modal = $('deleteOrderModal');
    const deleteForm = $('deleteOrderForm');
    const deleteLabel = $('deleteOrderNumber');
    const confirmBtn = $('confirmDelete');
    const toast = $('sellerSuccessAlert');
    const ordersContent = $('sellerOrdersContent');

    let selected = null;
    let paginationLoading = false;


    /* =====================================================
       TOAST
       ===================================================== */

    function showToast(message, type = 'success') {

        if (!toast) {
            return;
        }

        const isError = type === 'error';

        toast.classList.toggle('is-error', isError);

        $('sellerAlertTitle').textContent =
            isError ? 'Error' : 'Success';

        $('sellerAlertIcon').className =
            isError
                ? 'bi bi-exclamation-lg'
                : 'bi bi-check-lg';

        $('sellerSuccessMessage').textContent = message;

        toast.hidden = false;

        clearTimeout(toast.hideTimer);

        toast.hideTimer = setTimeout(function () {
            toast.hidden = true;
        }, 4000);
    }


    /* =====================================================
       SERVER SUCCESS MESSAGE
       ===================================================== */

    @if(session('success'))

        showToast(
            @json(session('success')),
            'success'
        );

    @endif


    /* =====================================================
       CLOSE TOAST
       ===================================================== */

    $('closeSuccessAlert').addEventListener('click', function () {
        toast.hidden = true;
    });


    /* =====================================================
       MODAL
       ===================================================== */

    function hideModal() {
        modal.classList.remove('show');
        selected = null;
    }


    /* =====================================================
       DELETE BUTTON (EVENT DELEGATION)
       ===================================================== */

    document.addEventListener('click', function (event) {

        const button = event.target.closest('[data-delete-order]');

        if (!button) {
            return;
        }

        const id = button.dataset.deleteOrder;

        selected = {
            id: id,
            row: $('order-row-' + id),
            url: button.dataset.deleteUrl
        };

        deleteLabel.textContent = '#' + button.dataset.orderNumber;

        deleteForm.action = selected.url;

        modal.classList.add('show');
    });


    $('closeDeleteModal').addEventListener('click', hideModal);

    $('cancelDelete').addEventListener('click', hideModal);

    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            hideModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            hideModal();
        }
    });


    /* =====================================================
       DELETE - AJAX
       ===================================================== */

    confirmBtn.addEventListener('click', async function () {

        if (!selected || !selected.row) {
            return;
        }

        const row = selected.row;
        const url = selected.url;

        const original = confirmBtn.innerHTML;

        confirmBtn.disabled = true;

        confirmBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';

        try {

            const response = await fetch(url, {
                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN':
                        deleteForm.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },

                body: new FormData(deleteForm)
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(
                    data.message || 'Unable to delete the order.'
                );
            }

            hideModal();

            row.classList.add('is-removing');

            setTimeout(function () {

                row.remove();

                const total = $('totalOrdersCount');

                if (total) {
                    total.textContent = Math.max(
                        0,
                        (parseInt(total.textContent) || 0) - 1
                    );
                }

                const found = $('ordersFoundText');

                if (found) {

                    const match = found.textContent.match(/\d+/);

                    if (match) {

                        const count = Math.max(0, parseInt(match[0]) - 1);

                        found.textContent =
                            count + ' ' +
                            (count === 1 ? 'order' : 'orders') +
                            ' found';
                    }
                }

            }, 280);

            showToast(
                data.message || 'Order deleted successfully.',
                'success'
            );

        } catch (error) {

            console.error('Delete order error:', error);

            hideModal();

            showToast(
                error.message ||
                'Something went wrong while deleting the order.',
                'error'
            );

        } finally {

            confirmBtn.disabled = false;
            confirmBtn.innerHTML = original;
        }
    });


    /* =====================================================
       AJAX PAGINATION
       ===================================================== */

    async function loadOrdersPage(url, pushState = true) {

        if (!ordersContent || paginationLoading) {
            return;
        }

        paginationLoading = true;

        try {

            const response = await fetch(url, {
                method: 'GET',

                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Unable to load the selected page.');
            }

            const html = await response.text();

            const documentPage = new DOMParser()
                .parseFromString(html, 'text/html');

            const newContent = documentPage
                .querySelector('#sellerOrdersContent');

            if (!newContent) {
                throw new Error('Orders content could not be loaded.');
            }

            /* Pagination-dan sonra animasiya olmasın.
               Səhifə yenilənəndə (F5) bu class olmur,
               ona görə animasiya normal işləyir. */
            ordersContent.classList.add('is-pagination-loaded');

            ordersContent.innerHTML = newContent.innerHTML;

            if (pushState) {
                window.history.pushState({ url: url }, '', url);
            }

            window.scrollTo(
                0,
                ordersContent.getBoundingClientRect().top +
                window.scrollY - 120
            );

        } catch (error) {

            console.error('Orders pagination error:', error);

            showToast(
                error.message || 'Unable to load orders.',
                'error'
            );

        } finally {

            paginationLoading = false;
        }
    }


    /* =====================================================
       PAGINATION CLICK
       ===================================================== */

    document.addEventListener('click', function (event) {

        const link = event.target.closest('.seller-orders-pagination a');

        if (!link || !link.href) {
            return;
        }

        event.preventDefault();

        loadOrdersPage(link.href);
    });


    /* =====================================================
       BROWSER BACK / FORWARD
       ===================================================== */

    window.addEventListener('popstate', function () {
        loadOrdersPage(window.location.href, false);
    });

});
</script>

@endpush