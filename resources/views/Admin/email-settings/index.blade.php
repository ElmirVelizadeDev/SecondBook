@extends('Layout.Admin.master')
@section('title', 'Email Settings')
@push('css')@vite('resources/css/admin/pages/email-settings.css')@endpush
@section('content')
<div data-vue-page="email-settings-admin" data-payload="{{ json_encode([
    'settings' => [
        'mail_enabled' => filter_var(old('mail_enabled', data_get($settings->get('mail_enabled'), 'value', '1')), FILTER_VALIDATE_BOOLEAN),
        'mail_mailer' => old('mail_mailer', data_get($settings->get('mail_mailer'), 'value', 'smtp')),
        'mail_host' => old('mail_host', data_get($settings->get('mail_host'), 'value', '')),
        'mail_port' => old('mail_port', data_get($settings->get('mail_port'), 'value', 587)),
        'mail_encryption' => old('mail_encryption', data_get($settings->get('mail_encryption'), 'value', 'tls')),
        'mail_username' => old('mail_username', data_get($settings->get('mail_username'), 'value', '')),
        'mail_from_address' => old('mail_from_address', data_get($settings->get('mail_from_address'), 'value', '')),
        'mail_from_name' => old('mail_from_name', data_get($settings->get('mail_from_name'), 'value', 'SecondBook')),
        'mail_reply_to_address' => old('mail_reply_to_address', data_get($settings->get('mail_reply_to_address'), 'value', '')),
        'mail_reply_to_name' => old('mail_reply_to_name', data_get($settings->get('mail_reply_to_name'), 'value', '')),
    ],
    'testEmail' => old('test_email', ''),
    'errors' => $errors->getMessages(),
    'success' => session('success'),
    'error' => session('error'),
    'urls' => ['update' => route('admin.email-settings.update'), 'test' => route('admin.email-settings.test')],
]) }}"></div>
@endsection
@push('js')@vite('resources/js/app.js')@endpush
