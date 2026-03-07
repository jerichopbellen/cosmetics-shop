@extends('layouts.base')

@section('body')
<div class="container py-5 mt-4">
    <h2 class="fw-bold text-dark mb-4"><i class="fa-solid fa-chart-line me-2 text-pink"></i>Store Analytics</h2>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 info-card">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-soft-pink text-pink"><i class="fa-solid fa-coins"></i></div>
                    <div class="ms-3">
                        <small class="text-muted fw-bold">TOTAL SALES</small>
                        <h4 class="fw-bold mb-0">₱{{ number_format($totalSales, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 info-card">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-soft-blue text-blue"><i class="fa-solid fa-shopping-cart"></i></div>
                    <div class="ms-3">
                        <small class="text-muted fw-bold">ORDERS</small>
                        <h4 class="fw-bold mb-0">{{ number_format($totalOrders) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 info-card">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-soft-green text-green"><i class="fa-solid fa-users"></i></div>
                    <div class="ms-3">
                        <small class="text-muted fw-bold">CUSTOMERS</small>
                        <h4 class="fw-bold mb-0">{{ number_format($totalCustomers) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 info-card">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-soft-orange text-orange"><i class="fa-solid fa-box"></i></div>
                    <div class="ms-3">
                        <small class="text-muted fw-bold">PRODUCTS</small>
                        <h4 class="fw-bold mb-0">{{ number_format($totalProducts) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs border-0 mb-4" id="analyticsTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="performance-tab" data-bs-toggle="tab" data-bs-target="#performance" type="button" role="tab">Sales Performance</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="yearly-tab" data-bs-toggle="tab" data-bs-target="#yearly" type="button" role="tab">Yearly Revenue</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="share-tab" data-bs-toggle="tab" data-bs-target="#share" type="button" role="tab">Product Share</button>
        </li>
    </ul>

    <div class="tab-content" id="analyticsTabsContent">
        {{-- 1. Sales Performance --}}
        <div class="tab-pane fade show active" id="performance" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-bold text-muted text-uppercase mb-0">
                        Sales Performance @if($start && $end) ({{ $start }} to {{ $end }}) @else (All Time) @endif
                    </h6>
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex align-items-center gap-2">
                        <input type="date" name="start_date" class="form-control form-control-sm border-pink" value="{{ $start }}">
                        <input type="date" name="end_date" class="form-control form-control-sm border-pink" value="{{ $end }}">
                        <button type="submit" class="btn btn-pink btn-sm">Filter</button>
                        @if($start || $end)
                            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted small ms-2">Clear</a>
                        @endif
                    </form>
                </div>
                <div style="height: 400px;"><canvas id="rangeBarChart"></canvas></div>
            </div>
        </div>

        {{-- 2. Yearly Revenue --}}
        <div class="tab-pane fade" id="yearly" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold text-muted text-uppercase mb-4">Historical Yearly Revenue</h6>
                <div style="height: 400px;"><canvas id="yearlyLineChart"></canvas></div>
            </div>
        </div>

        {{-- 3. Product Share --}}
        <div class="tab-pane fade" id="share" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold text-muted text-uppercase mb-4">Product Revenue Share</h6>
                <div class="row align-items-center">
                    <div class="col-lg-7 border-end">
                        <div style="height: 400px;"><canvas id="productPieChart"></canvas></div>
                    </div>
                    <div class="col-lg-5">
                        <div class="ps-lg-3">
                            <div id="chart-legend" class="pe-2" style="max-height: 400px; overflow-y: auto; scrollbar-width: thin;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pink = '#ec4899';
        
        // 1. RANGE BAR CHART
        new Chart(document.getElementById('rangeBarChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($barLabels) !!},
                datasets: [{ 
                    label: 'Revenue',
                    data: {!! json_encode($barData) !!}, 
                    backgroundColor: pink, 
                    borderRadius: 4 
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } } 
            }
        });

        // 2. YEARLY LINE CHART
        new Chart(document.getElementById('yearlyLineChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($lineLabels) !!},
                datasets: [{ 
                    data: {!! json_encode($lineData) !!}, 
                    borderColor: pink, 
                    backgroundColor: 'rgba(236, 72, 153, 0.1)', 
                    fill: true, 
                    tension: 0.3 
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } } 
            }
        });

        // 3. PRODUCT PIE CHART
        const pieData = {!! json_encode($pieData) !!};
        const pieLabels = {!! json_encode($pieLabels) !!};
        const totalRevenue = pieData.reduce((a, b) => a + Number(b), 0);
        const colors = pieData.map((_, i) => `hsl(330, 75%, ${Math.max(25, 85 - (i * 3.5))}%)`);

        const pieChart = new Chart(document.getElementById('productPieChart'), {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{ data: pieData, backgroundColor: colors, borderWidth: 1, borderColor: '#fff' }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const val = context.parsed;
                                const pct = ((val / totalRevenue) * 100).toFixed(2) + '%';
                                return ` ${context.label}: ₱${val.toLocaleString()} (${pct})`;
                            }
                        }
                    }
                }
            }
        });

        const legendContainer = document.getElementById('chart-legend');
        pieLabels.forEach((label, i) => {
            const val = pieData[i];
            const pct = ((val / totalRevenue) * 100).toFixed(1) + '%';
            const item = document.createElement('div');
            item.className = 'd-flex align-items-center justify-content-between p-2 mb-1 rounded border-bottom shadow-sm-hover';
            item.style.cursor = 'pointer';
            item.innerHTML = `
                <div class="d-flex align-items-center overflow-hidden">
                    <span style="width:12px; height:12px; background:${colors[i]}; border-radius:3px; flex-shrink:0; margin-right:10px;"></span>
                    <span class="text-dark small fw-bold text-truncate">${label}</span>
                </div>
                <div class="text-end flex-shrink-0 ms-2">
                    <div class="small fw-bold text-pink">${pct}</div>
                    <div class="text-muted" style="font-size: 0.7rem;">₱${Number(val).toLocaleString()}</div>
                </div>`;
            item.onclick = () => {
                const meta = pieChart.getDatasetMeta(0);
                meta.data[i].hidden = !meta.data[i].hidden;
                item.style.opacity = meta.data[i].hidden ? '0.3' : '1';
                pieChart.update();
            };
            legendContainer.appendChild(item);
        });
    });
</script>

<style>
    .info-card { transition: transform 0.2s; }
    .info-card:hover { transform: translateY(-5px); }
    .icon-box { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-size: 1.25rem; }
    .bg-soft-pink { background: #fce7f3; color: #ec4899; }
    .bg-soft-blue { background: #e0f2fe; color: #0ea5e9; }
    .bg-soft-green { background: #dcfce7; color: #22c55e; }
    .bg-soft-orange { background: #ffedd5; color: #f97316; }
    #chart-legend::-webkit-scrollbar { width: 5px; }
    #chart-legend::-webkit-scrollbar-thumb { background: #ec4899; border-radius: 10px; }
</style>
@endpush
@endsection