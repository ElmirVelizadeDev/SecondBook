<script setup>
import { computed, onMounted, ref } from 'vue';
import { decodeSellerPageData } from '../utils/sellerPageData';
const props=defineProps({pageData:{type:String,default:''}});const d=decodeSellerPageData(props.pageData);const status=ref(d.status||'');const hasFilters=computed(()=>!!status.value);
function confirmSubmit(event, message) {
    event.preventDefault();
    const form = event.currentTarget;

    if (!window.Swal) {
        if (window.confirm(message)) form.submit();
        return;
    }

    window.Swal.fire({
        icon: 'warning',
        title: message,
        showCancelButton: true,
        confirmButtonText: 'Continue',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) form.submit();
    });
}

onMounted(() => {
    if (d.success && window.Swal) {
        window.Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: d.success,
            showConfirmButton: false,
            timer: 2200,
        });
    }
});
</script>
<template><div class="seller-notifications-page"><div class="seller-page-heading"><div><h1>Notifications</h1><p>Stay updated with activity and important events from your store.</p></div><div v-if="d.unread>0" class="seller-notifications-heading-pill"><i></i>{{d.unread}} unread {{d.unread===1?'notification':'notifications'}}</div></div>
<div class="row g-4 mb-4"><div v-for="stat in d.stats" :key="stat.label" class="col-xl-3 col-md-6"><div class="seller-notification-stat-card"><div class="seller-notification-stat-icon" :class="stat.type"><i class="bi" :class="stat.icon"></i></div><div class="seller-notification-stat-content"><span>{{stat.label}}</span><h3>{{stat.count}}</h3></div></div></div></div>
<div class="seller-notifications-panel"><div class="seller-notifications-filter"><div class="seller-notification-tabs"><a :href="d.indexUrl" class="seller-notification-tab" :class="{active:!status}">All <em>{{d.total}}</em></a><a :href="d.unreadUrl" class="seller-notification-tab" :class="{active:status==='unread'}">Unread <em>{{d.unread}}</em></a><a :href="d.readUrl" class="seller-notification-tab" :class="{active:status==='read'}">Read <em>{{d.read}}</em></a></div><div class="seller-notification-header-actions"><form v-if="d.unread>0" :action="d.readAllUrl" method="POST" class="seller-notification-read-all-form" @submit="confirmSubmit($event,'Mark all notifications as read?')"><input type="hidden" name="_token" :value="d.csrfToken"><button type="submit" class="seller-notification-read-all"><i class="bi bi-check2-all"></i><span>Mark all as read</span></button></form><form v-if="d.total>0" :action="d.deleteAllUrl" method="POST" class="seller-notification-delete-all-form" @submit="confirmSubmit($event,'Delete all notifications? This cannot be undone.')"><input type="hidden" name="_token" :value="d.csrfToken"><input type="hidden" name="_method" value="DELETE"><button type="submit" class="seller-notification-delete-all"><i class="bi bi-trash3"></i><span>Delete all</span></button></form></div></div>
<div v-if="d.notifications.length" class="seller-notification-list"><div v-for="n in d.notifications" :key="n.id" class="seller-notification-item" :class="{'is-unread':n.unread}" :data-notification-id="n.id" :data-status="n.unread?'unread':'read'"><div class="seller-notification-icon"><i class="bi" :class="n.icon"></i><span v-if="n.unread" class="seller-notification-unread-dot"></span></div><div class="seller-notification-content"><div class="seller-notification-top"><div class="seller-notification-title-wrap"><strong>{{n.title}}</strong><span v-if="n.unread" class="seller-notification-new-badge">New</span></div><time><i class="bi bi-clock"></i>{{n.relativeDate}}</time></div><p>{{n.message}}</p><div class="seller-notification-meta"><span><i class="bi bi-calendar3"></i>{{n.date}}</span><span v-if="n.type" class="seller-notification-type"><i class="bi bi-tag"></i>{{n.typeLabel}}</span></div></div><div class="seller-notification-actions"><a v-if="n.url" :href="n.url" class="seller-notification-action view" title="Open notification"><i class="bi bi-arrow-up-right"></i></a><form :action="n.statusUrl" method="POST" class="seller-notification-status-form"><input type="hidden" name="_token" :value="d.csrfToken"><button type="submit" class="seller-notification-action" :title="n.unread?'Mark as read':'Mark as unread'"><i class="bi" :class="n.unread?'bi-check2':'bi-envelope'"></i></button></form><form :action="n.deleteUrl" method="POST" class="seller-notification-delete-form" @submit="confirmSubmit($event,'Delete this notification?')"><input type="hidden" name="_token" :value="d.csrfToken"><input type="hidden" name="_method" value="DELETE"><button type="submit" class="seller-notification-action delete" title="Delete notification"><i class="bi bi-trash3"></i></button></form></div></div></div>
<div v-if="d.notifications.length&&d.links.length>3" class="seller-notifications-pagination"><nav aria-label="Pagination"><a v-for="(link,i) in d.links" :key="i" :href="link.url||undefined" :class="{active:link.active,disabled:!link.url}" v-html="link.label"></a></nav></div>
<div v-if="!d.notifications.length" class="seller-notifications-empty"><div class="seller-notifications-empty-icon"><i class="bi bi-bell-slash"></i></div><h5>No Notifications Found</h5><p>You don't have any notifications matching your current filter.</p><a v-if="hasFilters" :href="d.indexUrl" class="seller-notification-empty-button"><i class="bi bi-arrow-counterclockwise"></i><span>Clear Filter</span></a></div>
</div></div></template>
