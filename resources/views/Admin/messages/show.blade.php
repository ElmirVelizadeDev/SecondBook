@extends('Layout.Admin.master')
@section('title','Message Details')
@push('css')@vite('resources/css/admin/pages/messages.css')@endpush
@section('content')<div class="messages-page" data-vue-page="messages-admin" data-mode="show" data-index-url="{{ route('admin.messages.index') }}" data-csrf="{{ csrf_token() }}" data-payload="{{ json_encode(['message'=>['id'=>$message->id,'name'=>$message->name,'email'=>$message->email,'subject'=>$message->subject,'body'=>$message->message,'status'=>$message->status,'created_at'=>$message->created_at?->format('d M Y H:i')],'message_user'=>$messageUser?->only(['id','name','email']),'reply_url'=>route('admin.messages.reply',$message),'site_reply_url'=>$messageUser ? route('admin.messages.site-reply',$message) : null,'unread_url'=>route('admin.messages.unread',$message),'delete_url'=>route('admin.messages.destroy',$message)]) }}"></div>@endsection
@push('scripts')@vite('resources/js/app.js')@endpush
