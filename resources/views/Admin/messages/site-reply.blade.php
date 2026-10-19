@extends('Layout.Admin.master')
@section('title','Reply in Site')
@push('css')@vite('resources/css/admin/pages/messages.css')@endpush
@section('content')<div class="messages-page" data-vue-page="messages-admin" data-mode="site-reply" data-index-url="{{ route('admin.messages.index') }}" data-csrf="{{ csrf_token() }}" data-submit-url="{{ route('admin.messages.send-site-reply',$message) }}" data-payload="{{ json_encode(['message'=>['id'=>$message->id,'name'=>$message->name,'email'=>$message->email,'subject'=>$message->subject,'body'=>$message->message],'recipient'=>$messageUser->only(['id','name','email']),'show_url'=>route('admin.messages.show',$message)]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
