<script setup>
import { computed, ref } from 'vue';
const props = defineProps({ payload: { type: String, required: true } });
const state = JSON.parse(props.payload);
const coupons = ref(state.coupons || []);
const stats = ref({ ...(state.stats || {}) });
const coupon = state.coupon || {};
const old = state.old || {};
const errors = state.errors || {};
const message = ref('');
const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
const formUrl = computed(() => state.view === 'create' ? state.urls.store : state.urls.update);
function statusOf(item) {
    if (!item.status) return 'inactive';
    const now = Date.now();
    if (item.expires_at && new Date(item.expires_at).getTime() < now) return 'expired';
    if (item.starts_at && new Date(item.starts_at).getTime() > now) return 'scheduled';
    return 'active';
}
async function toggle(item) {
    item.busy = true;
    try {
        const response = await fetch(item.toggle_url, { method: 'PATCH', credentials: 'same-origin', headers: { 'X-CSRF-TOKEN': csrf, Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await response.json();
        if (!response.ok || !data.success) throw new Error(data.message || 'Could not update coupon status.');
        item.status = data.status;
        if (item.status) stats.value.active += 1; else stats.value.active = Math.max(0, stats.value.active - 1);
        message.value = data.message;
    } catch (error) { message.value = error.message || 'Could not update coupon status.'; }
    finally { item.busy = false; }
}
async function remove(item) {
    const confirmed = window.Swal ? (await window.Swal.fire({ title:'Delete coupon?', text:`Coupon ${item.code} will be permanently deleted.`, icon:'warning', showCancelButton:true, confirmButtonText:'Delete', cancelButtonText:'Cancel', reverseButtons:true })).isConfirmed : window.confirm(`Delete coupon ${item.code}?`);
    if (!confirmed) return;
    item.busy = true;
    try {
        const response = await fetch(item.delete_url, { method:'DELETE', credentials:'same-origin', headers:{ 'X-CSRF-TOKEN':csrf, Accept:'application/json', 'X-Requested-With':'XMLHttpRequest' } });
        const data = await response.json();
        if (!response.ok || !data.success) throw new Error(data.message || 'Could not delete coupon.');
        coupons.value = coupons.value.filter((couponItem)=>couponItem.id!==item.id);
        stats.value.total = Math.max(0, stats.value.total - 1);
        stats.value.used = Math.max(0, stats.value.used - Number(item.used_count || 0));
        message.value = data.message;
    } catch (error) { message.value = error.message || 'Could not delete coupon.'; }
    finally { item.busy = false; }
}
</script>

<template>
<div>
    <template v-if="state.view==='index'">
        <div class="dashboard-section coupons-page"><section class="coupons-hero"><div class="coupons-hero-content"><span class="coupons-hero-badge"><i class="bi bi-ticket-perforated"></i>Promotion Operations</span><h1>Every promotion, under control.</h1><p>Manage discount coupons, promotional offers and usage activity from one organised workspace.</p></div><div class="coupons-hero-mark"><i class="bi bi-ticket-perforated"></i></div></section>
            <div v-if="state.success||message" class="alert" :class="message&&!message.toLowerCase().includes('success')?'alert-danger':'alert-success'">{{message||state.success}}</div>
            <section class="coupons-stats"><div v-for="item in [{label:'Total Coupons',value:stats.total,icon:'bi-ticket-perforated',color:'stat-blue'},{label:'Active',value:stats.active,icon:'bi-check-circle',color:'stat-green'},{label:'Expired',value:stats.expired,icon:'bi-clock-history',color:'stat-orange'},{label:'Total Used',value:stats.used,icon:'bi-people',color:'stat-purple'}]" :key="item.label" class="coupon-stat-card" :class="item.color"><div class="coupon-stat-content"><span>{{item.label}}</span><strong>{{item.value}}</strong></div><div class="coupon-stat-icon"><i class="bi" :class="item.icon"></i></div></div></section>
            <section class="dashboard-panel coupons-panel"><div class="coupons-panel-header"><div class="coupons-heading-content"><span class="eyebrow">Coupon directory</span><h5>All Coupons</h5><p>Search, filter and manage every promotional coupon.</p></div><div class="coupons-header-action"><a :href="state.urls.create" class="coupons-add-btn"><i class="bi bi-plus-lg"></i><span>Add Coupon</span></a></div></div>
                <form method="GET" :action="state.urls.index" class="coupon-filters"><div class="coupon-filter-group coupon-filter-search"><label for="coupon-search">Search</label><div class="coupon-search-field"><i class="bi bi-search"></i><input id="coupon-search" type="text" name="search" :value="state.filters.search" placeholder="Search coupon code..." autocomplete="off"></div></div><div class="coupon-filter-group"><label for="coupon-type">Discount Type</label><select id="coupon-type" name="type" class="coupon-filter-select"><option value="">All Types</option><option value="percentage" :selected="state.filters.type==='percentage'">Percentage</option><option value="fixed" :selected="state.filters.type==='fixed'">Fixed Amount</option></select></div><div class="coupon-filter-group"><label for="coupon-status">Status</label><select id="coupon-status" name="status" class="coupon-filter-select"><option value="">All Status</option><option value="1" :selected="state.filters.status==='1'">Active</option><option value="0" :selected="state.filters.status==='0'">Inactive</option></select></div><div class="coupon-filter-actions"><button type="submit" class="coupon-filter-btn"><i class="bi bi-funnel"></i><span>Filter</span></button><a :href="state.urls.index" class="coupon-clear-filter" :hidden="!state.filters.search&&!state.filters.type&&!state.filters.status"><i class="bi bi-x-lg"></i><span>Reset</span></a></div></form>
                <div class="coupons-table-wrap"><table class="coupons-table"><thead><tr><th class="coupons-col-id">#</th><th class="coupons-col-code">Code</th><th class="coupons-col-discount">Discount</th><th class="coupons-col-minimum">Minimum Order</th><th class="coupons-col-usage">Usage</th><th class="coupons-col-validity">Validity</th><th class="coupons-col-status">Status</th><th class="coupons-col-actions">Actions</th></tr></thead><tbody>
                    <tr v-for="item in coupons" :key="item.id"><td><span class="coupon-id">#{{item.id}}</span></td><td><div class="coupon-code-cell"><div class="coupon-code-icon"><i class="bi bi-ticket-perforated"></i></div><div class="coupon-code-info"><strong :title="item.code">{{item.code}}</strong><small>Discount coupon</small></div></div></td><td><span class="coupon-discount">{{item.type==='percentage'?`${Number(item.value).toFixed(0)}%`:`$${Number(item.value).toFixed(2)}`}}</span><small class="coupon-discount-type">{{item.type==='percentage'?'Percentage':'Fixed Amount'}}</small></td><td><span class="coupon-money">${{Number(item.minimum_order_amount||0).toFixed(2)}}</span></td><td><div class="coupon-usage-block"><strong>{{item.used_count}}</strong><span>/</span><span>{{item.usage_limit??'∞'}}</span></div></td><td><div class="coupon-date-block"><span class="coupon-date">{{item.starts_label}}</span><small>to {{item.expires_label}}</small></div></td><td><span class="coupon-status-pill" :class="`coupon-status-${statusOf(item)}`"><i class="bi bi-circle-fill"></i>{{statusOf(item).charAt(0).toUpperCase()+statusOf(item).slice(1)}}</span></td><td><div class="coupon-actions"><a :href="item.show_url" class="coupon-action-btn coupon-view-btn" title="View coupon"><i class="bi bi-eye"></i></a><a :href="item.edit_url" class="coupon-action-btn coupon-edit-btn" title="Edit coupon"><i class="bi bi-pencil"></i></a><button type="button" class="coupon-action-btn coupon-toggle-btn" :disabled="item.busy" :aria-label="`Toggle status for coupon ${item.id}`" @click="toggle(item)"><i class="bi" :class="item.status?'bi-toggle-on':'bi-toggle-off'"></i></button><button type="button" class="coupon-action-btn coupon-delete-btn" :disabled="item.busy" :aria-label="`Delete coupon ${item.id}`" @click="remove(item)"><i class="bi bi-trash"></i></button></div></td></tr>
                    <tr v-if="!coupons.length"><td colspan="8"><div class="coupons-empty-state"><i class="bi bi-ticket-perforated"></i><h5>No coupons found</h5><p>No coupons match your current filters.</p><a :href="state.urls.create" class="coupons-add-btn">Add Coupon</a></div></td></tr>
                </tbody></table></div>
                <div v-if="state.pagination.last_page>1" class="coupons-pagination"><div>Showing <strong>{{state.pagination.first}}</strong> to <strong>{{state.pagination.last}}</strong> of <strong>{{state.pagination.total}}</strong> coupons</div><nav class="coupons-pagination-pages"><a class="coupon-page" :class="{disabled:!state.pagination.previous}" :href="state.pagination.previous||undefined">‹</a><a v-for="(url,page) in state.pagination.pages" :key="page" class="coupon-page" :class="{active:Number(page)===Number(state.pagination.current)}" :href="url">{{page}}</a><a class="coupon-page" :class="{disabled:!state.pagination.next}" :href="state.pagination.next||undefined">›</a></nav></div>
            </section>
        </div>
    </template>

    <template v-else-if="state.view==='create'||state.view==='edit'">
        <div class="coupons-edit-page"><div class="coupons-edit-header"><div class="coupons-edit-heading"><div class="coupons-edit-eyebrow"><i class="bi bi-ticket-perforated"></i>Coupon Management</div><h1>{{state.view==='create'?'Create Coupon':'Edit Coupon'}}</h1><p>Manage coupon discount, usage and validity details.</p></div><a :href="state.urls.index" class="coupons-edit-back"><i class="bi bi-arrow-left"></i><span>Back to Coupons</span></a></div>
            <div class="coupons-edit-card"><div class="coupons-edit-card-header"><div class="coupons-edit-card-icon"><i class="bi bi-ticket-perforated"></i></div><div class="coupons-edit-card-title"><strong>{{state.view==='create'?'Coupon Information':'Edit Coupon Information'}}</strong><span>Set the discount and configure when this coupon can be used.</span></div></div><div class="coupons-edit-card-body">
                <div v-if="Object.keys(errors).length" class="coupons-edit-errors"><div class="coupons-edit-errors-title"><i class="bi bi-exclamation-triangle"></i>Please correct the following errors</div><ul><template v-for="(messages,key) in errors" :key="key"><li v-for="error in messages" :key="error">{{error}}</li></template></ul></div>
                <form :action="formUrl" method="POST"><input type="hidden" name="_token" :value="csrf"><input v-if="state.view==='edit'" type="hidden" name="_method" value="PUT">
                    <div class="coupons-edit-section"><div class="coupons-edit-section-heading"><i class="bi bi-ticket-perforated"></i><span>Coupon Information</span></div><div class="coupons-edit-grid">
                        <div class="coupons-edit-field span-6"><label for="code" class="coupons-edit-label">Coupon Code <span class="required">*</span></label><input id="code" name="code" class="coupons-edit-input" :class="{'is-invalid':errors.code}" :value="old.code" maxlength="50" placeholder="WELCOME10" required><div v-for="error in errors.code" :key="error" class="coupons-edit-field-error">{{error}}</div></div>
                        <div class="coupons-edit-field span-6"><label for="type" class="coupons-edit-label">Discount Type <span class="required">*</span></label><select id="type" name="type" class="coupons-edit-select" :class="{'is-invalid':errors.type}" required><option value="">Select Type</option><option value="percentage" :selected="old.type==='percentage'">Percentage (%)</option><option value="fixed" :selected="old.type==='fixed'">Fixed Amount ($)</option></select><div v-for="error in errors.type" :key="error" class="coupons-edit-field-error">{{error}}</div></div>
                        <div class="coupons-edit-field span-6"><label for="value" class="coupons-edit-label">Discount Value <span class="required">*</span></label><input id="value" name="value" type="number" class="coupons-edit-input" :class="{'is-invalid':errors.value}" :value="old.value" step="0.01" min="0.01" required><div v-for="error in errors.value" :key="error" class="coupons-edit-field-error">{{error}}</div></div>
                        <div class="coupons-edit-field span-6"><label for="minimum_order_amount" class="coupons-edit-label">Minimum Order Amount</label><input id="minimum_order_amount" name="minimum_order_amount" type="number" class="coupons-edit-input" :class="{'is-invalid':errors.minimum_order_amount}" :value="old.minimum_order_amount" step="0.01" min="0"><div v-for="error in errors.minimum_order_amount" :key="error" class="coupons-edit-field-error">{{error}}</div></div>
                        <div class="coupons-edit-field span-6"><label for="maximum_discount_amount" class="coupons-edit-label">Maximum Discount Amount</label><input id="maximum_discount_amount" name="maximum_discount_amount" type="number" class="coupons-edit-input" :class="{'is-invalid':errors.maximum_discount_amount}" :value="old.maximum_discount_amount" step="0.01" min="0"><div v-for="error in errors.maximum_discount_amount" :key="error" class="coupons-edit-field-error">{{error}}</div></div>
                        <div class="coupons-edit-field span-6"><label for="usage_limit" class="coupons-edit-label">Usage Limit</label><input id="usage_limit" name="usage_limit" type="number" class="coupons-edit-input" :class="{'is-invalid':errors.usage_limit}" :value="old.usage_limit" min="1"><div v-for="error in errors.usage_limit" :key="error" class="coupons-edit-field-error">{{error}}</div></div>
                    </div></div>
                    <div class="coupons-edit-section"><div class="coupons-edit-section-heading"><i class="bi bi-calendar-check"></i><span>Coupon Schedule</span></div><div class="coupons-edit-grid">
                        <div class="coupons-edit-field span-6"><label for="starts_at" class="coupons-edit-label">Starts At</label><input id="starts_at" name="starts_at" type="datetime-local" class="coupons-edit-input" :class="{'is-invalid':errors.starts_at}" :value="old.starts_at" required><div v-for="error in errors.starts_at" :key="error" class="coupons-edit-field-error">{{error}}</div></div>
                        <div class="coupons-edit-field span-6"><label for="expires_at" class="coupons-edit-label">Expires At</label><input id="expires_at" name="expires_at" type="datetime-local" class="coupons-edit-input" :class="{'is-invalid':errors.expires_at}" :value="old.expires_at" required><div v-for="error in errors.expires_at" :key="error" class="coupons-edit-field-error">{{error}}</div></div>
                        <div class="coupons-edit-field span-6"><label for="status" class="coupons-edit-label">Status</label><select id="status" name="status" class="coupons-edit-select" :class="{'is-invalid':errors.status}" required><option value="1" :selected="String(old.status)==='1'">Active</option><option value="0" :selected="String(old.status)==='0'">Inactive</option></select><div v-for="error in errors.status" :key="error" class="coupons-edit-field-error">{{error}}</div></div>
                    </div></div>
                    <div v-if="state.view==='edit'" class="coupons-edit-section"><div class="coupons-edit-section-heading"><i class="bi bi-bar-chart"></i><span>Usage Information</span></div><div class="coupons-edit-grid"><div class="coupons-edit-field span-6"><label class="coupons-edit-label">Used Count</label><div class="coupons-edit-static">{{coupon.used_count}}</div></div><div class="coupons-edit-field span-6"><label class="coupons-edit-label">Remaining Uses</label><div class="coupons-edit-static">{{coupon.usage_limit===null?'Unlimited':Math.max(0,coupon.usage_limit-coupon.used_count)}}</div></div></div></div>
                    <div class="coupons-edit-actions"><a :href="state.urls.index" class="coupons-edit-back">Cancel</a><button type="submit" class="coupons-edit-submit"><i class="bi bi-check2-circle"></i>{{state.view==='create'?'Create Coupon':'Update Coupon'}}</button></div>
                </form>
            </div></div>
        </div>
    </template>

    <template v-else>
        <div class="coupons-show-page"><div class="coupons-edit-header"><div class="coupons-edit-heading"><div class="coupons-edit-eyebrow"><i class="bi bi-ticket-perforated"></i>Coupon Management</div><h1>Coupon Details</h1><p>View complete coupon information.</p></div><div class="coupons-show-header-actions"><a :href="state.urls.index" class="coupons-edit-back"><i class="bi bi-arrow-left"></i><span>Back to Coupons</span></a><a :href="state.urls.edit" class="coupons-edit-submit"><i class="bi bi-pencil"></i><span>Edit Coupon</span></a></div></div>
            <div class="coupons-show-summary"><div class="coupons-show-summary-item"><span>Coupon Code</span><strong>{{coupon.code}}</strong></div><div class="coupons-show-summary-item"><span>Discount</span><strong>{{coupon.type==='percentage'?`${Number(coupon.value).toFixed(0)}%`:`$${Number(coupon.value).toFixed(2)}`}}</strong></div><div class="coupons-show-summary-item"><span>Usage</span><strong>{{coupon.used_count}} / {{coupon.usage_limit??'Unlimited'}}</strong></div><div class="coupons-show-summary-item"><span>Status</span><em class="coupon-status-pill" :class="`coupon-status-${statusOf(coupon)}`"><i class="bi bi-circle-fill"></i>{{statusOf(coupon)}}</em></div></div>
            <div class="coupons-show-grid"><div v-for="card in [{title:'Coupon Information',subtitle:'Code, discount and usage details',icon:'bi-ticket-perforated',rows:[['Coupon Code',coupon.code],['Discount Type',coupon.type],['Discount Value',coupon.type==='percentage'?`${Number(coupon.value).toFixed(0)}%`:`$${Number(coupon.value).toFixed(2)}`],['Minimum Order',`$${Number(coupon.minimum_order_amount||0).toFixed(2)}`],['Maximum Discount',coupon.maximum_discount_amount===null?'No Limit':`$${Number(coupon.maximum_discount_amount).toFixed(2)}`]]},{title:'Usage',subtitle:'Coupon usage limits and activity',icon:'bi-bar-chart',rows:[['Used Count',coupon.used_count],['Usage Limit',coupon.usage_limit??'Unlimited'],['Remaining',coupon.usage_limit===null?'Unlimited':Math.max(0,coupon.usage_limit-coupon.used_count)],['Status',statusOf(coupon)]]},{title:'Schedule',subtitle:'Coupon availability period',icon:'bi-calendar-event',rows:[['Starts At',coupon.starts_at],['Expires At',coupon.expires_at],['Current Status',statusOf(coupon)]]},{title:'Record Information',subtitle:'Coupon creation and update dates',icon:'bi-clock-history',rows:[['Created',coupon.created_at],['Last Updated',coupon.updated_at]]}]" :key="card.title" class="coupons-edit-card"><div class="coupons-edit-card-header"><div class="coupons-edit-card-icon"><i class="bi" :class="card.icon"></i></div><div class="coupons-edit-card-title"><strong>{{card.title}}</strong><span>{{card.subtitle}}</span></div></div><div class="coupons-show-body"><div v-for="row in card.rows" :key="row[0]" class="coupons-show-row"><span>{{row[0]}}</span><strong>{{row[1]??'—'}}</strong></div></div></div></div>
        </div>
    </template>
</div>
</template>
