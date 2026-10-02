@extends('layout.admin.master')

@section('title', 'Edit Role')

@section('content')

<div class="dashboard-section roles-page role-edit-page">

    {{-- =====================================================
         PAGE HEADER
         ===================================================== --}}
    <div class="dashboard-panel role-edit-header-panel">

        <div class="role-edit-header">

            <div class="role-edit-heading">

                <div class="role-edit-icon">
                    <i class="bi bi-shield-check"></i>
                </div>

                <div class="role-edit-heading-content">

                    <span class="eyebrow">
                        Access control
                    </span>

                    <h1>Edit Role</h1>

                    <p>
                        Update permissions and access settings for
                        <strong>{{ $role->display_name }}</strong>.
                    </p>

                </div>

            </div>

            <div class="role-edit-header-action">

                <a
                    href="{{ route('admin.roles.index') }}"
                    class="btn role-back-btn"
                >
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to Role</span>
                </a>

            </div>

        </div>

    </div>


    {{-- =====================================================
         FORM
         ===================================================== --}}
    <div class="dashboard-panel role-form-panel">

        <div class="role-edit-form-header">

            <div>
                <span class="eyebrow">
                    Role configuration
                </span>

                <h5>
                    Permissions & Access
                </h5>

                <p>
                    Configure what this role can access and manage.
                </p>
            </div>

            <div class="role-edit-current">

                <i class="bi bi-shield-check"></i>

                <div>
                    <span>Editing</span>
                    <strong>{{ $role->display_name }}</strong>
                </div>

            </div>

        </div>


        <form
            action="{{ route('admin.roles.update', $role) }}"
            method="POST"
            class="role-edit-form"
        >

            @csrf
            @method('PUT')

            @include('Admin.roles._form')

        </form>

    </div>

</div>

@endsection


@push('js')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const checkboxes = document.querySelectorAll(
        '[data-permission-checkbox]'
    );

    const selectAllButton = document.querySelector(
        '[data-select-all-permissions]'
    );

    const groupButtons = document.querySelectorAll(
        '[data-select-group]'
    );


    if (!checkboxes.length) {
        return;
    }


    /* =====================================================
       SELECT / CLEAR ALL PERMISSIONS
       ===================================================== */

    if (selectAllButton) {

        selectAllButton.addEventListener('click', function () {

            const allChecked = Array.from(checkboxes).every(
                checkbox => checkbox.checked
            );

            checkboxes.forEach(function (checkbox) {
                checkbox.checked = !allChecked;
            });

            updateGroupButtons();
        });
    }


    /* =====================================================
       SELECT / CLEAR PERMISSION GROUP
       ===================================================== */

    groupButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const group = button.closest(
                '[data-permission-group]'
            );

            if (!group) {
                return;
            }

            const groupCheckboxes = group.querySelectorAll(
                '[data-permission-checkbox]'
            );

            const allChecked = Array.from(groupCheckboxes).every(
                checkbox => checkbox.checked
            );

            groupCheckboxes.forEach(function (checkbox) {
                checkbox.checked = !allChecked;
            });

            updateGroupButtons();
        });

    });


    /* =====================================================
       UPDATE GROUP BUTTON LABELS
       ===================================================== */

    function updateGroupButtons() {

        document
            .querySelectorAll('[data-permission-group]')
            .forEach(function (group) {

                const groupCheckboxes = group.querySelectorAll(
                    '[data-permission-checkbox]'
                );

                const button = group.querySelector(
                    '[data-select-group]'
                );

                if (!button || !groupCheckboxes.length) {
                    return;
                }

                const allChecked = Array.from(groupCheckboxes).every(
                    checkbox => checkbox.checked
                );

                button.textContent = allChecked
                    ? 'Clear'
                    : 'All';
            });
    }


    updateGroupButtons();

});
</script>

@endpush