@extends('Layout.Admin.master')
@section('title','Reply to Message')
@push('css')@vite('resources/css/admin/pages/messages.css')@endpush
@section('content')<div class="messages-page" data-vue-page="messages-admin" data-mode="reply" data-index-url="{{ route('admin.messages.index') }}" data-csrf="{{ csrf_token() }}" data-submit-url="{{ route('admin.messages.sendReply',$message) }}" data-payload="{{ json_encode(['message'=>['id'=>$message->id,'name'=>$message->name,'email'=>$message->email,'subject'=>$message->subject,'body'=>$message->message],'show_url'=>route('admin.messages.show',$message)]) }}" data-errors="{{ json_encode($errors->toArray()) }}" data-old="{{ json_encode(old()) }}"></div>@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
