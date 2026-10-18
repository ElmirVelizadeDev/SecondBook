@extends('Layout.Admin.master')


@section('title', 'Backup')


@push('css')

    <link
        rel="stylesheet"
        href="{{ asset('admin/css/pages/backup.css') }}"
    >

@endpush


@section('content')

    <div class="dashboard-section backup-page">


        {{-- Page Header --}}

        <div class="dashboard-panel backup-header-panel">

            <div class="backup-header-content">

                <div class="backup-header-text">

                    <span class="backup-eyebrow">

                        <i class="bi bi-shield-check"></i>

                        System Protection

                    </span>


                    <h1 class="backup-page-title">

                        System Backup

                    </h1>


                    <p class="backup-page-description">

                        Create and manage secure database backups of your SecondBook system.

                    </p>

                </div>


                <div class="backup-header-action">

                    <form
                        action="{{ route('admin.backup.create') }}"
                        method="POST"
                    >

                        @csrf


                        <button
                            type="submit"
                            class="btn backup-create-btn"
                        >

                            <i class="bi bi-cloud-arrow-up"></i>

                            <span>Create Backup</span>

                        </button>

                    </form>

                </div>

            </div>

        </div>


        {{-- Alerts --}}

        @if(session('success'))

            <div class="alert alert-success backup-alert">

                <i class="bi bi-check-circle-fill"></i>

                <span>

                    {{ session('success') }}

                </span>

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger backup-alert">

                <i class="bi bi-exclamation-triangle-fill"></i>

                <span>

                    {{ session('error') }}

                </span>

            </div>

        @endif


        {{-- Statistics --}}

        <div class="row g-4 backup-stats-row">


            <div class="col-xl-4 col-md-6">

                <div class="dashboard-panel backup-stat-card">

                    <div class="backup-stat-icon protection">

                        <i class="bi bi-shield-lock"></i>

                    </div>


                    <div class="backup-stat-content">

                        <span class="backup-stat-label">

                            Backup Protection

                        </span>


                        <h3>

                            Active

                        </h3>


                        <p>

                            Your database backup system is ready.

                        </p>

                    </div>

                </div>

            </div>


            <div class="col-xl-4 col-md-6">

                <div class="dashboard-panel backup-stat-card">

                    <div class="backup-stat-icon backups">

                        <i class="bi bi-database-check"></i>

                    </div>


                    <div class="backup-stat-content">

                        <span class="backup-stat-label">

                            Total Backups

                        </span>


                        <h3>

                            {{ $backups->count() }}

                        </h3>


                        <p>

                            Backup archives currently stored.

                        </p>

                    </div>

                </div>

            </div>


            <div class="col-xl-4 col-md-12">

                <div class="dashboard-panel backup-stat-card">

                    <div class="backup-stat-icon latest">

                        <i class="bi bi-clock-history"></i>

                    </div>


                    <div class="backup-stat-content">

                        <span class="backup-stat-label">

                            Last Backup

                        </span>


                        <h3>

                            @if($backups->isNotEmpty())

                                {{ $backups->first()->getMTime() ? date('d M Y', $backups->first()->getMTime()) : '—' }}

                            @else

                                Never

                            @endif

                        </h3>


                        <p>

                            Most recently created backup.

                        </p>

                    </div>

                </div>

            </div>


        </div>


        {{-- Backup History --}}

        <div class="dashboard-panel backup-history-panel">


            <div class="backup-history-header">

                <div>

                    <span class="backup-section-eyebrow">

                        Backup Storage

                    </span>


                    <h2 class="backup-section-title">

                        Backup History

                    </h2>


                    <p class="backup-section-description">

                        View, download and manage your existing backup archives.

                    </p>

                </div>


                <div class="backup-history-count">

                    <i class="bi bi-archive"></i>

                    {{ $backups->count() }} Backups

                </div>

            </div>


            @if($backups->isNotEmpty())

                <div class="table-responsive backup-table-wrapper">

                    <table class="table backup-table align-middle">

                        <thead>

                            <tr>

                                <th>

                                    Backup File

                                </th>


                                <th>

                                    Type

                                </th>


                                <th>

                                    Size

                                </th>


                                <th>

                                    Created

                                </th>


                                <th class="text-end">

                                    Action

                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($backups as $backup)

                                <tr>

                                    <td>

                                        <div class="backup-file-cell">

                                            <div class="backup-file-icon">

                                                <i class="bi bi-file-earmark-zip"></i>

                                            </div>


                                            <div class="backup-file-info">

                                                <span class="backup-file-name">

                                                    {{ $backup->getFilename() }}

                                                </span>


                                                <small>

                                                    Database Backup

                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    <td>

                                        <span class="backup-type-badge">

                                            <i class="bi bi-database"></i>

                                            MySQL

                                        </span>

                                    </td>


                                    <td>

                                        <span class="backup-size">

                                            {{ number_format($backup->getSize() / 1024, 2) }} KB

                                        </span>

                                    </td>


                                    <td>

                                        <div class="backup-date">

                                            <strong>

                                                {{ date('d M Y', $backup->getMTime()) }}

                                            </strong>


                                            <small>

                                                {{ date('H:i', $backup->getMTime()) }}

                                            </small>

                                        </div>

                                    </td>


                                    <td>

                                        <div class="backup-actions">


                                            {{-- Download --}}

                                            <a
                                                href="{{ route('admin.backup.download', $backup->getFilename()) }}"
                                                class="backup-action-btn download"
                                                title="Download Backup"
                                            >

                                                <i class="bi bi-download"></i>

                                            </a>


                                            {{-- Restore --}}

                                            <form
                                                action="{{ route('admin.backup.restore', $backup->getFilename()) }}"
                                                method="POST"
                                                class="backup-action-form restore-backup-form"
                                                data-file="{{ $backup->getFilename() }}"
                                            >

                                                @csrf


                                                <button
                                                    type="submit"
                                                    class="backup-action-btn restore"
                                                    title="Restore Backup"
                                                >

                                                    <i class="bi bi-arrow-counterclockwise"></i>

                                                </button>

                                            </form>


                                            {{-- Delete --}}

                                            <form
                                                action="{{ route('admin.backup.delete', $backup->getFilename()) }}"
                                                method="POST"
                                                class="backup-action-form delete-backup-form"
                                                data-file="{{ $backup->getFilename() }}"
                                            >

                                                @csrf

                                                @method('DELETE')


                                                <button
                                                    type="submit"
                                                    class="backup-action-btn delete"
                                                    title="Delete Backup"
                                                >

                                                    <i class="bi bi-trash3"></i>

                                                </button>

                                            </form>


                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="backup-empty-state">

                    <div class="backup-empty-icon">

                        <i class="bi bi-database-x"></i>

                    </div>


                    <h3>

                        No Backups Yet

                    </h3>


                    <p>

                        Create your first database backup to protect your system data.

                    </p>


                    <form
                        action="{{ route('admin.backup.create') }}"
                        method="POST"
                    >

                        @csrf


                        <button
                            type="submit"
                            class="btn backup-empty-btn"
                        >

                            <i class="bi bi-cloud-arrow-up"></i>

                            Create First Backup

                        </button>

                    </form>

                </div>

            @endif


        </div>


    </div>

@endsection


@push('js')

    <script>

        document.addEventListener('DOMContentLoaded', function () {


            const backupForms = document.querySelectorAll(
                'form[action="{{ route('admin.backup.create') }}"]'
            );


            backupForms.forEach(function (form) {

                form.addEventListener('submit', function () {

                    const button = form.querySelector(
                        'button[type="submit"]'
                    );


                    if (!button) {

                        return;

                    }


                    button.disabled = true;


                    button.innerHTML = `
                        <span
                            class="spinner-border spinner-border-sm"
                            aria-hidden="true"
                        ></span>

                        <span>Creating Backup...</span>
                    `;

                });

            });


            const deleteForms = document.querySelectorAll(
                '.delete-backup-form'
            );


            deleteForms.forEach(function (form) {

                form.addEventListener('submit', function (event) {

                    event.preventDefault();


                    const fileName = form.dataset.file;


                    Swal.fire({

                        title: 'Delete Backup?',

                        html: `
                            <p style="margin: 0;">
                                Are you sure you want to delete
                                <strong>${fileName}</strong>?
                            </p>

                            <p style="margin: 8px 0 0;">
                                This backup file will be permanently deleted.
                            </p>
                        `,

                        icon: 'warning',

                        showCancelButton: true,

                        confirmButtonText: 'Yes, Delete',

                        cancelButtonText: 'Cancel',

                        reverseButtons: true,

                        focusCancel: true,

                        customClass: {

                            confirmButton: 'btn btn-danger',

                            cancelButton: 'btn btn-secondary'

                        },

                        buttonsStyling: false

                    }).then(function (result) {

                        if (result.isConfirmed) {

                            form.submit();

                        }

                    });

                });

            });


            const restoreForms = document.querySelectorAll(
                '.restore-backup-form'
            );


            restoreForms.forEach(function (form) {

                form.addEventListener('submit', function (event) {

                    event.preventDefault();


                    const fileName = form.dataset.file;


                    Swal.fire({

                        title: 'Restore Database?',

                        html: `
                            <p style="margin: 0;">
                                You are about to restore
                                <strong>${fileName}</strong>.
                            </p>

                            <p style="margin: 10px 0 0;">
                                The current database will be replaced
                                with the data stored in this backup.
                            </p>

                            <p style="margin: 10px 0 0; font-weight: 600;">
                                This action should only be performed
                                when you are sure.
                            </p>
                        `,

                        icon: 'warning',

                        showCancelButton: true,

                        confirmButtonText: 'Yes, Restore',

                        cancelButtonText: 'Cancel',

                        reverseButtons: true,

                        focusCancel: true,

                        customClass: {

                            confirmButton: 'btn btn-primary',

                            cancelButton: 'btn btn-secondary'

                        },

                        buttonsStyling: false

                    }).then(function (result) {

                        if (result.isConfirmed) {

                            const button = form.querySelector(
                                'button[type="submit"]'
                            );


                            if (button) {

                                button.disabled = true;


                                button.innerHTML = `
                                    <span
                                        class="spinner-border spinner-border-sm"
                                        aria-hidden="true"
                                    ></span>
                                `;

                            }


                            form.submit();

                        }

                    });

                });

            });


        });

    </script>

@endpush

