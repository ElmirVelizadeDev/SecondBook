@extends('layout.admin.master')

@section('title', 'Edit Role')

@section('content')

<div class="dashboard-section roles-page role-edit-page">


<div class="dashboard-panel role-edit-header-panel">
    <div class="role-edit-header">

        <div class="role-edit-heading">
            <span class="eyebrow">
                <i class="bi bi-shield-lock"></i>
                Access control
            </span>

            <h5>Edit role</h5>

            <p>
                Update {{ $role->display_name }} access permissions.
            </p>
        </div>

        <div class="role-edit-header-action">
            <a
                href="{{ route('admin.roles.show', $role) }}"
                class="btn role-back-btn"
            >
                <i class="bi bi-arrow-left"></i>
                <span>Back to role</span>
            </a>
        </div>

    </div>
</div>

<div class="dashboard-panel role-form-panel">

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