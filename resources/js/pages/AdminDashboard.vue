<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

const props = defineProps({
    dashboard: { type: String, required: true },
});

const data = JSON.parse(props.dashboard);
const chartCanvas = ref(null);
const chartUnavailable = ref(false);
let chart = null;
let themeObserver = null;

const number = (value) => Number(value || 0).toLocaleString('en-US');
const money = (value) => Number(value || 0).toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});

const bookStatus = {
    approved: ['green', 'Approved'],
    pending: ['amber', 'Pending'],
    rejected: ['red', 'Rejected'],
};

const orderStatus = {
    pending: ['amber', 'Pending'],
    processing: ['blue', 'Processing'],
    shipped: ['cyan', 'Shipped'],
    delivered: ['green', 'Delivered'],
    cancelled: ['red', 'Cancelled'],
};

function statusFor(map, status) {
    return map[status] || ['slate', String(status || '').replace(/^./, (letter) => letter.toUpperCase())];
}

function coverUrl(cover) {
    return cover || '';
}

function buildChart() {
    if (!chartCanvas.value) return;
    if (!window.Chart) {
        chartUnavailable.value = true;
        return;
    }

    if (chart) chart.destroy();

    const canvas = chartCanvas.value;
    const scope = canvas.closest('.dashboard-section');
    const token = (name) => getComputedStyle(scope).getPropertyValue(name).trim();
    const ctx = canvas.getContext('2d');
    const orders = token('--dx-chart-orders');
    const revenue = token('--dx-chart-revenue');
    const grid = token('--dx-chart-grid');
    const text = token('--dx-chart-text');
    const gradient = (color) => {
        const fill = ctx.createLinearGradient(0, 0, 0, canvas.clientHeight || 340);
        fill.addColorStop(0, `${color}33`);
        fill.addColorStop(1, `${color}00`);
        return fill;
    };

    window.Chart.defaults.font.family = '"Plus Jakarta Sans", system-ui, sans-serif';
    window.Chart.defaults.color = text;
    chart = new window.Chart(canvas, {
        type: 'line',
        data: {
            labels: data.monthlyLabels,
            datasets: [
                {
                    label: 'Orders', data: data.monthlyOrders, borderColor: orders,
                    backgroundColor: gradient(orders), borderWidth: 3, tension: 0.4,
                    fill: true, pointRadius: 0, pointHoverRadius: 6,
                    pointHoverBorderWidth: 3, pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: orders, yAxisID: 'orders',
                },
                {
                    label: 'Revenue', data: data.monthlyRevenue, borderColor: revenue,
                    backgroundColor: gradient(revenue), borderWidth: 3, tension: 0.4,
                    fill: true, pointRadius: 0, pointHoverRadius: 6,
                    pointHoverBorderWidth: 3, pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: revenue, yAxisID: 'revenue',
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    padding: 12, cornerRadius: 10, boxPadding: 5, usePointStyle: true,
                    backgroundColor: '#101828', titleColor: '#fff', bodyColor: '#d0d5dd',
                    callbacks: {
                        label(item) {
                            return item.dataset.yAxisID === 'revenue'
                                ? ` Revenue: ${money(item.parsed.y)} AZN`
                                : ` Orders: ${item.parsed.y}`;
                        },
                    },
                },
            },
            scales: {
                x: { grid: { display: false }, border: { display: false } },
                orders: {
                    type: 'linear', position: 'left', beginAtZero: true,
                    border: { display: false }, grid: { color: grid },
                    ticks: { precision: 0, padding: 8 },
                },
                revenue: {
                    type: 'linear', position: 'right', beginAtZero: true,
                    border: { display: false }, grid: { drawOnChartArea: false },
                    ticks: { padding: 8 },
                },
            },
        },
    });
}

onMounted(() => {
    buildChart();
    themeObserver = new MutationObserver(buildChart);
    themeObserver.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['data-theme'],
    });
});

onBeforeUnmount(() => {
    themeObserver?.disconnect();
    chart?.destroy();
});
</script>

<template>
    <div class="dashboard-section dx-page">
        <section class="dx-hero">
            <div class="dx-hero-body">
                <div class="dx-hero-crumb"><i class="bi bi-speedometer2"></i> SecondBook Admin</div>
                <h1>Welcome back, Admin</h1>
                <p>Manage your books, users and categories from one place.</p>
                <div class="dx-hero-actions">
                    <a :href="data.links.bookCreate" class="dx-btn dx-btn-primary"><i class="bi bi-plus-lg"></i> Add Book</a>
                    <a :href="data.links.books" class="dx-btn dx-btn-ghost"><i class="bi bi-book"></i> View Books</a>
                </div>
            </div>
            <div class="dx-hero-glance">
                <div class="dx-glance-title"><i class="bi bi-calendar3"></i> {{ data.glanceLabel || 'Latest month' }}</div>
                <div class="dx-glance-row"><span>Orders</span><strong>{{ number(data.glanceOrders) }}</strong></div>
                <div class="dx-glance-row"><span>Revenue</span><strong>{{ money(data.glanceRevenue) }}<small>AZN</small></strong></div>
            </div>
        </section>

        <section class="dx-stats">
            <article v-for="stat in data.stats" :key="stat.label" class="dx-stat">
                <div class="dx-stat-top">
                    <span class="dx-stat-label">{{ stat.label }}</span>
                    <span class="dx-chip" :class="`dx-tone-${stat.tone}`"><i class="bi" :class="stat.icon"></i></span>
                </div>
                <div class="dx-stat-value">{{ number(stat.value) }}</div>
                <a :href="stat.url" class="dx-stat-link">View details <i class="bi bi-arrow-right"></i></a>
            </article>
        </section>

        <section class="dx-split">
            <article class="dx-panel">
                <div class="dx-panel-head">
                    <div><h2>Monthly Overview</h2><p>Orders and revenue by month</p></div>
                    <div class="dx-legend"><span><i></i>Orders</span><span><i></i>Revenue</span></div>
                </div>
                <div class="dx-chart">
                    <canvas v-if="!chartUnavailable" ref="chartCanvas"></canvas>
                    <div v-else class="dx-empty" role="status">Monthly overview chart is unavailable.</div>
                </div>
            </article>
            <article class="dx-panel">
                <div class="dx-panel-head"><div><h2>Recent Activity</h2><p>Latest changes on the platform</p></div></div>
                <ul v-if="data.recentActivity.length" class="dx-activity">
                    <li v-for="(activity, index) in data.recentActivity" :key="`${activity.title}-${index}`">
                        <span class="dx-chip" :class="`dx-tone-${activity.tone}`"><i class="bi" :class="activity.icon"></i></span>
                        <div><strong>{{ activity.title }}</strong><p>{{ activity.detail }}</p><small v-if="activity.time">{{ activity.time }}</small></div>
                    </li>
                </ul>
                <div v-else class="dx-empty"><i class="bi bi-inbox"></i><strong>No activity yet</strong><span>New books, users and categories will show up here.</span></div>
            </article>
        </section>

        <article class="dx-panel">
            <div class="dx-panel-head">
                <div><h2>Recent Books</h2><p>The latest titles added to the marketplace</p></div>
                <a :href="data.links.books" class="dx-btn dx-btn-soft">View all <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="dx-table-wrap">
                <table class="dx-table">
                    <thead><tr><th>Book</th><th class="dx-hide-md">Category</th><th class="dx-hide-lg">Seller</th><th>Price</th><th>Status</th></tr></thead>
                    <tbody>
                        <tr v-for="(book, index) in data.recentBooks" :key="`${book.title}-${index}`">
                            <td><div class="dx-book"><div class="dx-cover">
                                <img v-if="book.cover" :src="coverUrl(book.cover)" :alt="book.title" loading="lazy" @error="$event.target.hidden = true; $event.target.nextElementSibling.hidden = false">
                                <i class="bi bi-book" :hidden="Boolean(book.cover)"></i>
                            </div><div><span class="dx-title">{{ book.title }}</span><span v-if="book.isbn" class="dx-sub">ISBN: {{ book.isbn }}</span></div></div></td>
                            <td class="dx-hide-md">{{ book.category }}</td><td class="dx-hide-lg">{{ book.seller }}</td>
                            <td class="dx-num">{{ money(book.price) }} AZN</td>
                            <td><span class="dx-pill" :class="`dx-pill-${statusFor(bookStatus, book.status)[0]}`">{{ statusFor(bookStatus, book.status)[1] }}</span></td>
                        </tr>
                        <tr v-if="!data.recentBooks.length"><td colspan="5"><div class="dx-empty"><i class="bi bi-book"></i><strong>No books found</strong><span>Add your first book to see it here.</span></div></td></tr>
                    </tbody>
                </table>
            </div>
        </article>

        <section class="dx-split">
            <article class="dx-panel">
                <div class="dx-panel-head"><div><h2>Recent Orders</h2><p>Latest customer purchases</p></div></div>
                <div class="dx-table-wrap"><table class="dx-table">
                    <thead><tr><th class="dx-hide-md">#</th><th>Order</th><th class="dx-hide-md">Customer</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                        <tr v-for="order in data.recentOrders" :key="order.number">
                            <td class="dx-hide-md">{{ order.rowNumber }}</td><td><span class="dx-title">#{{ order.number }}</span><span v-if="order.book" class="dx-sub">{{ order.book }}</span></td>
                            <td class="dx-hide-md">{{ order.customer }}</td><td class="dx-num">{{ money(order.total) }} AZN</td>
                            <td><span class="dx-pill" :class="`dx-pill-${statusFor(orderStatus, order.status)[0]}`">{{ statusFor(orderStatus, order.status)[1] }}</span></td>
                        </tr>
                        <tr v-if="!data.recentOrders.length"><td colspan="5"><div class="dx-empty"><i class="bi bi-bag"></i><strong>No orders found</strong><span>Orders will appear here once customers check out.</span></div></td></tr>
                    </tbody>
                </table></div>
            </article>
            <article class="dx-panel">
                <div class="dx-panel-head"><div><h2>Quick Actions</h2><p>Jump straight to common tasks</p></div></div>
                <div class="dx-actions">
                    <a :href="data.links.bookCreate" class="dx-action"><span class="dx-chip dx-tone-blue"><i class="bi bi-book"></i></span><span><strong>Add Book</strong><small>Publish a new title</small></span><i class="bi bi-chevron-right"></i></a>
                    <a :href="data.links.categoryCreate" class="dx-action"><span class="dx-chip dx-tone-green"><i class="bi bi-grid"></i></span><span><strong>Add Category</strong><small>Organize your catalog</small></span><i class="bi bi-chevron-right"></i></a>
                    <a :href="data.links.authorCreate" class="dx-action"><span class="dx-chip dx-tone-amber"><i class="bi bi-pencil-square"></i></span><span><strong>Add Author</strong><small>Create an author profile</small></span><i class="bi bi-chevron-right"></i></a>
                    <a :href="data.links.users" class="dx-action"><span class="dx-chip dx-tone-slate"><i class="bi bi-people"></i></span><span><strong>View Users</strong><small>Manage customer accounts</small></span><i class="bi bi-chevron-right"></i></a>
                </div>
            </article>
        </section>
    </div>
</template>
