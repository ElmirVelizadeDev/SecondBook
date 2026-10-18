<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { decodeSellerPageData } from '../utils/sellerPageData';
const props = defineProps({ pageData: { type: String, default: '' } });
const d = decodeSellerPageData(props.pageData);
const canvas = ref(null);
const chartLoading = ref(true);
const chartError = ref('');
let chart = null;
let chartLoadPromise = null;

function loadChart() {
    if (window.Chart) return Promise.resolve(window.Chart);
    if (chartLoadPromise) return chartLoadPromise;

    chartLoadPromise = new Promise((resolve, reject) => {
        let script = document.querySelector('script[data-seller-chart]');
        const shouldAppend = !script;

        if (shouldAppend) {
            script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            script.dataset.sellerChart = 'true';
        }

        script.addEventListener('load', () => {
            if (window.Chart) resolve(window.Chart);
            else reject(new Error('Chart.js loaded without exposing its API.'));
        }, { once: true });
        script.addEventListener('error', () => {
            script.remove();
            reject(new Error('Chart.js could not be downloaded.'));
        }, { once: true });

        if (shouldAppend) document.head.append(script);
    });

    return chartLoadPromise;
}

onMounted(async () => {
    try {
        const Chart = await loadChart();
        if (!Chart || !canvas.value) return;

        const context = canvas.value.getContext('2d');
        const gradient = context.createLinearGradient(0, 0, 0, 320);
        gradient.addColorStop(0, 'rgba(139, 94, 60, .28)');
        gradient.addColorStop(1, 'rgba(139, 94, 60, 0)');

        chart = new Chart(context, {
            type: 'line',
            data: {
                labels: d.chartLabels,
                datasets: [{
                    label: 'Revenue',
                    data: d.chartRevenue,
                    borderColor: '#8b5e3c',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#8b5e3c',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    fill: true,
                    tension: .4,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#131b2c',
                        titleColor: '#fff',
                        bodyColor: '#d5dbea',
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            label: (item) => '$' + Number(item.raw).toLocaleString(undefined, {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2,
                            }),
                        },
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: '#8b95a7', font: { size: 10 }, maxTicksLimit: 10 },
                    },
                    y: {
                        beginAtZero: true,
                        border: { display: false },
                        grid: { color: 'rgba(139, 149, 167, .12)' },
                        ticks: { color: '#8b95a7', font: { size: 10 }, callback: (value) => '$' + value },
                    },
                },
                animation: { duration: 1100, easing: 'easeOutQuart' },
            },
        });
    } catch {
        chartError.value = 'The revenue chart could not be loaded. Please reload the page to try again.';
    } finally {
        chartLoading.value = false;
    }
});

onBeforeUnmount(() => chart?.destroy());
</script>
<template><div class="seller-analytics-page">
<div class="seller-page-heading"><div><h1>Analytics</h1><p>Track your store performance, sales and inventory.</p></div><form :action="d.analyticsUrl" method="GET" class="seller-analytics-range-form"><i class="bi bi-calendar3"></i><select name="range" :value="d.range" @change="$event.target.form.submit()"><option value="7">Last 7 Days</option><option value="30">Last 30 Days</option><option value="90">Last 90 Days</option><option value="365">Last Year</option></select></form></div>
<div class="seller-analytics-stats">
<div class="seller-analytics-stat-card"><div class="seller-analytics-stat-icon revenue"><i class="bi bi-currency-dollar"></i></div><div class="seller-analytics-stat-content"><span>Total Revenue</span><h3>{{ '$'+d.totalRevenue }}</h3><small :class="d.revenueGrowth>=0?'positive':'negative'"><i class="bi" :class="d.revenueGrowth>=0?'bi-arrow-up':'bi-arrow-down'"></i>{{ Math.abs(d.revenueGrowth).toFixed(1) }}% vs previous period</small></div></div>
<div class="seller-analytics-stat-card"><div class="seller-analytics-stat-icon orders"><i class="bi bi-bag-check"></i></div><div class="seller-analytics-stat-content"><span>Total Orders</span><h3>{{ d.totalOrders }}</h3><small>{{ d.deliveredOrders }} delivered</small></div></div>
<div class="seller-analytics-stat-card"><div class="seller-analytics-stat-icon items"><i class="bi bi-box-seam"></i></div><div class="seller-analytics-stat-content"><span>Items Sold</span><h3>{{ d.itemsSold }}</h3><small>Completed orders</small></div></div>
<div class="seller-analytics-stat-card"><div class="seller-analytics-stat-icon average"><i class="bi bi-graph-up-arrow"></i></div><div class="seller-analytics-stat-content"><span>Average Order</span><h3>{{ '$'+d.averageOrderValue }}</h3><small>Per delivered order</small></div></div></div>
<div class="seller-analytics-panel seller-analytics-revenue-panel"><div class="seller-analytics-panel-header"><div class="seller-analytics-panel-title"><div class="seller-analytics-panel-icon"><i class="bi bi-graph-up"></i></div><div><h5>Revenue Overview</h5><p>Your daily revenue and sales performance.</p></div></div><div class="seller-analytics-period">Last {{ d.range }} days</div></div><div class="seller-analytics-chart-area"><div class="seller-analytics-chart-summary"><span>Total Revenue</span><strong>{{ '$'+d.totalRevenue }}</strong></div><div class="seller-analytics-chart"><canvas ref="canvas" id="sellerRevenueChart"></canvas><div v-if="chartLoading" class="seller-analytics-mini-empty seller-analytics-chart-message" role="status">Loading revenue chart…</div><div v-else-if="chartError" class="seller-analytics-mini-empty seller-analytics-chart-message" role="alert">{{ chartError }}</div></div></div></div>
<div class="seller-analytics-grid"><div class="seller-analytics-panel"><div class="seller-analytics-panel-header"><div class="seller-analytics-panel-title"><div class="seller-analytics-panel-icon small"><i class="bi bi-pie-chart"></i></div><div><h5>Order Status</h5><p>Current order distribution.</p></div></div></div><div class="seller-analytics-status-content"><template v-if="d.statuses.length"><template v-for="s in d.statuses" :key="s.key"><div class="seller-analytics-status-row"><div class="seller-analytics-status-label"><span class="status-dot" :class="s.color"></span><strong>{{ s.label }}</strong></div><div class="seller-analytics-status-value"><span>{{ s.count }}</span><small>{{ s.percentage }}%</small></div></div><div class="seller-analytics-progress"><span :class="s.color" :style="{width:s.percentage+'%'}"></span></div></template></template><div v-else class="seller-analytics-mini-empty">No order data available.</div></div></div>
<div class="seller-analytics-panel"><div class="seller-analytics-panel-header"><div class="seller-analytics-panel-title"><div class="seller-analytics-panel-icon small"><i class="bi bi-boxes"></i></div><div><h5>Inventory Overview</h5><p>Monitor your current book inventory.</p></div></div></div><div class="seller-analytics-inventory"><div v-for="item in d.inventory" :key="item.label" class="seller-analytics-inventory-item"><div class="inventory-icon" :class="item.type"><i class="bi" :class="item.icon"></i></div><div><span>{{ item.label }}</span><strong>{{ item.count }}</strong></div></div></div></div></div>
<div class="seller-analytics-panel seller-analytics-top-books"><div class="seller-analytics-panel-header"><div class="seller-analytics-panel-title"><div class="seller-analytics-panel-icon"><i class="bi bi-trophy"></i></div><div><h5>Top Selling Books</h5><p>Your best performing books in this period.</p></div></div></div><div v-if="d.topBooks.length" class="seller-analytics-books-list"><div v-for="(book,index) in d.topBooks" :key="book.id" class="seller-analytics-book-row"><div class="seller-analytics-book-rank">{{ index+1 }}</div><div class="seller-analytics-book-cover"><img v-if="book.coverUrl" :src="book.coverUrl" :alt="book.title"><i v-else class="bi bi-book"></i></div><div class="seller-analytics-book-info"><strong>{{ book.title }}</strong><span v-if="book.author">{{ book.author }}</span></div><div class="seller-analytics-book-sales"><strong>{{ book.quantity }}</strong><span>{{ book.quantity===1?'item':'items' }} sold</span></div><div class="seller-analytics-book-revenue">{{ '$'+book.revenue }}</div></div></div><div v-else class="seller-analytics-mini-empty"><i class="bi bi-bar-chart"></i><span>No sales data available for this period.</span></div></div>
<div class="seller-analytics-panel seller-analytics-recent-orders"><div class="seller-analytics-panel-header"><div class="seller-analytics-panel-title"><div class="seller-analytics-panel-icon"><i class="bi bi-clock-history"></i></div><div><h5>Recent Orders</h5><p>Your latest seller orders.</p></div></div><a :href="d.ordersUrl" class="seller-analytics-view-all">View All <i class="bi bi-arrow-right"></i></a></div><div v-if="d.recentOrders.length" class="table-responsive"><table class="seller-analytics-orders-table"><thead><tr><th>Order</th><th>Book</th><th>Buyer</th><th>Status</th><th>Total</th><th>Date</th></tr></thead><tbody><tr v-for="o in d.recentOrders" :key="o.id"><td><strong class="seller-analytics-order-number">#{{ o.number }}</strong></td><td><div class="seller-analytics-order-book"><div class="seller-analytics-order-cover"><img v-if="o.coverUrl" :src="o.coverUrl" :alt="o.book"><i v-else class="bi bi-book"></i></div><strong>{{ o.book }}</strong></div></td><td><span class="seller-analytics-buyer">{{ o.buyer }}</span></td><td><span class="seller-analytics-status-pill" :class="o.statusClass">{{ o.statusLabel }}</span></td><td><strong class="seller-analytics-order-total">{{ '$'+o.total }}</strong></td><td><div class="seller-analytics-order-date"><strong>{{ o.date }}</strong><span>{{ o.time }}</span></div></td></tr></tbody></table></div><div v-else class="seller-analytics-mini-empty"><i class="bi bi-receipt"></i><span>No recent orders found.</span></div></div>
</div></template>
