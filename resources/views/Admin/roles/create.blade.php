@extends('layout.admin.master')

@section('title', 'Create Role')

@section('content')

<div class="dashboard-section roles-page role-create-page">


{{-- Page Header --}}
<div class="dashboard-panel role-create-header-panel mb-4">

    <div class="role-create-header">

        <div class="role-create-heading">

            <div class="role-create-icon">
                <i class="bi bi-shield-plus"></i>
            </div>

            <div>
                <span class="eyebrow">Access control</span>

                <h1>Create role</h1>

                <p>
                    Create a custom role and define the permissions it can access.
                </p>
            </div>

        </div>

        <a
            href="{{ route('admin.roles.index') }}"
            class="role-create-back"
        >
            <i class="bi bi-arrow-left"></i>
            <span>Back to roles</span>
        </a>

    </div>

</div>


{{-- Role Form --}}
<div class="dashboard-panel role-create-form-panel">

    <div class="role-create-form-header">

        <div>
            <span class="eyebrow">Role configuration</span>

            <h5>Role information</h5>

            <p>
                Enter the role details and select the permissions that should be assigned to it.
            </p>
        </div>

        <div class="role-create-form-badge">
            <i class="bi bi-sliders2"></i>
            <span>Custom role</span>
        </div>

    </div>


    <div class="role-create-form-body">

        <form
            action="{{ route('admin.roles.store') }}"
            method="POST"
            class="role-create-form"
        >

            @csrf

            @include('Admin.roles._form')

        </form>

    </div>

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

    // Select all permissions
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

    // Select all permissions in a group
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

    // Update group button text
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
