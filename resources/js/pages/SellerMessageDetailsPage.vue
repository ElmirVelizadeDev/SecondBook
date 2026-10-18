<script setup>
import { computed, ref } from 'vue';
import { decodeSellerPageData } from '../utils/sellerPageData';
import sellerMessageService from '../services/sellerMessageService';
const props = defineProps({ pageData: { type: String, default: '' } });
const d = decodeSellerPageData(props.pageData);
const reply = ref(d.oldReply || '');
const replies = ref(d.replies || []);
const error = ref(d.replyError || '');
const count = computed(() => reply.value.length);
async function deleteReply(item) {
    const confirmation = window.Swal
        ? await window.Swal.fire({
            icon: 'warning',
            title: 'Delete this reply?',
            text: 'The reply and its related customer notification will be deleted.',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
        })
        : { isConfirmed: window.confirm('Delete this reply?') };

    if (!confirmation.isConfirmed) return;

    try {
        const result = await sellerMessageService.deleteReply(item.deleteUrl);
        replies.value = replies.value.filter(row => row.id !== item.id);
        if (window.Swal) {
            await window.Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: result.message || 'Reply deleted successfully.',
                showConfirmButton: false,
                timer: 1800,
            });
        }
    } catch (exception) {
        error.value = exception.response?.data?.message || 'Unable to delete the reply.';
        if (window.Swal) {
            window.Swal.fire({ icon: 'error', title: 'Unable to delete reply', text: error.value });
        }
    }
}
function sendShortcut(e) { if (e.ctrlKey && e.key === 'Enter') { e.preventDefault(); e.target.form?.requestSubmit(); } }
</script>
<template><div class="seller-message-show-page">
<div class="seller-page-heading"><div><h1>Message Details</h1><p>View the conversation and reply to your customer.</p></div><a :href="d.indexUrl" class="seller-message-show-back"><i class="bi bi-arrow-left"></i><span>Back to Messages</span></a></div>
<div class="row g-4"><div class="col-xl-4"><aside class="seller-message-info-card"><div class="seller-message-info-cover"></div><div class="seller-message-customer"><div class="seller-message-customer-avatar"><img v-if="d.message.photoUrl" :src="d.message.photoUrl" :alt="d.message.name"><span v-else>{{d.message.initial}}</span></div><div class="seller-message-customer-content"><span>Customer</span><strong>{{d.message.name}}</strong><span class="seller-message-status" :class="d.message.unread?'unread':'read'"><i></i>{{d.message.unread?'Unread':'Read'}}</span></div></div><div class="seller-message-info-list"><div v-for="item in d.info" :key="item.label" class="seller-message-info-item"><div class="seller-message-info-icon"><i class="bi" :class="item.icon"></i></div><div class="seller-message-info-text"><span>{{item.label}}</span><strong>{{item.value||'—'}}</strong></div></div></div><a href="#seller-reply-form" class="seller-message-quick-reply"><i class="bi bi-send"></i> Reply now</a></aside></div>
<div class="col-xl-8"><div class="seller-message-conversation-card"><div class="seller-message-conversation-header"><div class="seller-message-conversation-title"><div class="seller-message-conversation-icon"><i class="bi bi-chat-left-text"></i></div><div><h5>Conversation</h5><p>Communication with {{d.message.name||'customer'}}</p></div></div><span class="seller-message-count">{{replies.length+1}} {{replies.length===0?'message':'messages'}}</span></div><div class="seller-message-conversation-body" id="sellerConversationBody">
<div class="seller-message-bubble customer"><div class="seller-message-mini-avatar">{{d.message.initial}}</div><div class="seller-message-bubble-card"><div class="seller-message-bubble-header"><strong>{{d.message.name||'Customer'}}</strong><span>{{d.message.createdAt}}</span></div><div class="seller-message-bubble-subject"><i class="bi bi-tag"></i>{{d.message.subject||'No Subject'}}</div><div class="seller-message-bubble-text">{{d.message.body}}</div></div></div>
<div v-for="item in replies" :key="item.id" class="seller-message-bubble" :class="item.seller?'seller':'customer'"><div class="seller-message-mini-avatar">{{item.initial}}</div><div class="seller-message-bubble-card"><div class="seller-message-bubble-header"><strong>{{item.seller?'You':(item.name||'Customer')}}</strong><div class="seller-message-bubble-meta"><span>{{item.createdAt}}</span><button v-if="item.seller" type="button" class="seller-message-delete-reply" @click="deleteReply(item)" title="Delete reply" aria-label="Delete reply"><i class="bi bi-trash3"></i></button></div></div><div class="seller-message-bubble-text">{{item.body}}</div></div></div>
<div v-if="!replies.length" class="seller-message-no-replies"><div class="seller-message-no-replies-icon"><i class="bi bi-chat-dots"></i></div><h6>No Replies Yet</h6><p>This conversation has not received a reply yet.</p></div></div>
<div class="seller-message-reply-section" id="seller-reply-form"><div class="seller-message-reply-header"><div class="seller-message-reply-header-icon"><i class="bi bi-reply-fill"></i></div><div><h6>Reply to Customer</h6><p>Send a message directly to this customer.</p></div></div><form :action="d.replyUrl" method="POST" class="seller-message-reply-form"><input type="hidden" name="_token" :value="d.csrfToken"><div class="seller-message-reply-field"><textarea v-model="reply" name="reply" rows="5" placeholder="Write your reply..." required maxlength="10000" @keydown="sendShortcut"></textarea><div v-if="error" class="seller-message-reply-error"><i class="bi bi-exclamation-circle"></i><span>{{error}}</span></div></div><div class="seller-message-reply-actions"><span class="seller-message-reply-hint"><kbd>Ctrl</kbd> + <kbd>Enter</kbd> to send <em>{{count}}</em> characters</span><div class="seller-message-reply-buttons"><a :href="d.indexUrl" class="seller-message-cancel-button">Cancel</a><button type="submit" class="seller-message-send-button"><i class="bi bi-send"></i><span>Send Reply</span></button></div></div></form></div></div></div></div></div>
</template>
