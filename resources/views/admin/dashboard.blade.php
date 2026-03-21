@extends('layouts.base')

@section('body')
<div class="container py-5 mt-4">
    <h2 class="fw-bold text-dark mb-4"><i class="fa-solid fa-chart-line me-2 text-pink"></i>Store Analytics</h2>

    {{-- 1. INFORMATION CARDS --}}
    <div class="row g-3 mb-4">
        {{-- Sales Card --}}
        <div class="col-md-4 col-lg-2">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none h-100">
                <div class="card border-0 shadow-sm p-3 info-card h-100">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-soft-pink text-pink"><i class="fa-solid fa-coins"></i></div>
                        <div class="ms-3">
                            <small class="text-muted fw-bold">SALES</small>
                            <h5 class="fw-bold mb-0">₱{{ number_format($totalSales, 0) }}</h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Orders Card --}}
        <div class="col-md-4 col-lg-2">
            <a href="{{ route('admin.orders.index') }}" class="text-decoration-none h-100">
                <div class="card border-0 shadow-sm p-3 info-card h-100">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-soft-blue text-blue"><i class="fa-solid fa-shopping-cart"></i></div>
                        <div class="ms-3">
                            <small class="text-muted fw-bold">ORDERS</small>
                            <h5 class="fw-bold mb-0">{{ number_format($totalOrders) }}</h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Products Card --}}
        <div class="col-md-4 col-lg-2">
            <a href="{{ route('products.index') }}" class="text-decoration-none h-100">
                <div class="card border-0 shadow-sm p-3 info-card h-100">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-soft-orange text-orange"><i class="fa-solid fa-box"></i></div>
                        <div class="ms-3">
                            <small class="text-muted fw-bold">PRODUCTS</small>
                            <h5 class="fw-bold mb-0">{{ number_format($totalProducts) }}</h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Brands Card --}}
        <div class="col-md-4 col-lg-2">
            <a href="{{ route('brands.index') }}" class="text-decoration-none h-100">
                <div class="card border-0 shadow-sm p-3 info-card h-100">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-soft-purple text-purple"><i class="fa-solid fa-copyright"></i></div>
                        <div class="ms-3">
                            <small class="text-muted fw-bold">BRANDS</small>
                            <h5 class="fw-bold mb-0">{{ number_format($totalBrands) }}</h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Categories Card --}}
        <div class="col-md-4 col-lg-2">
            <a href="{{ route('categories.index') }}" class="text-decoration-none h-100">
                <div class="card border-0 shadow-sm p-3 info-card h-100">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-soft-teal text-teal"><i class="fa-solid fa-layer-group"></i></div>
                        <div class="ms-3">
                            <small class="text-muted fw-bold">CATEGORIES</small>
                            <h5 class="fw-bold mb-0">{{ number_format($totalCategories) }}</h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Customers Card --}}
        <div class="col-md-4 col-lg-2">
            <a href="{{ route('admin.users.index') }}" class="text-decoration-none h-100">
                <div class="card border-0 shadow-sm p-3 info-card h-100">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-soft-green text-green"><i class="fa-solid fa-users"></i></div>
                        <div class="ms-3">
                            <small class="text-muted fw-bold">CUSTOMERS</small>
                            <h5 class="fw-bold mb-0">{{ number_format($totalCustomers) }}</h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- 2. TABS NAVIGATION --}}
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

    {{-- 3. TAB CONTENT --}}
    <div class="tab-content" id="analyticsTabsContent">
        
        {{-- Tab 1: Sales Performance (Bar) --}}
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
                <div style="height: 400px;">
                    {!! $salesChart->container() !!}
                </div>
            </div>
        </div>

        {{-- Tab 2: Yearly Revenue (Line) --}}
        <div class="tab-pane fade" id="yearly" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold text-muted text-uppercase mb-4">Historical Yearly Revenue</h6>
                <div style="height: 400px;">
                    {!! $yearlyChart->container() !!}
                </div>
            </div>
        </div>

        {{-- Tab 3: Product Share (Pie) --}}
        <div class="tab-pane fade" id="share" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold text-muted text-uppercase mb-4">Product Revenue Share</h6>
                <div class="row align-items-center">
                    <div class="col-lg-7 border-end">
                        <div style="height: 400px;">
                            {!! $pieChart->container() !!}
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="ps-lg-3">
                            <div id="chart-legend" class="pe-2" style="max-height: 400px; overflow-y: auto; scrollbar-width: thin;">
                                @php $totalRevenue = $pieData->sum(); @endphp
                                @foreach($pieLabels as $i => $label)
                                    @php 
                                        $val = $pieData[$i];
                                        $pct = $totalRevenue > 0 ? number_format(($val / $totalRevenue) * 100, 1) : 0;
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-between p-2 mb-1 rounded border-bottom shadow-sm-hover legend-item" 
                                         data-index="{{ $i }}" style="cursor: pointer;">
                                        <div class="d-flex align-items-center overflow-hidden">
                                            <span style="width:12px; height:12px; background:{{ $pieColors[$i] }}; border-radius:3px; flex-shrink:0; margin-right:10px;"></span>
                                            <span class="text-dark small fw-bold text-truncate">{{ $label }}</span>
                                        </div>
                                        <div class="text-end flex-shrink-0 ms-2">
                                            <div class="small fw-bold text-pink">{{ $pct }}%</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">₱{{ number_format($val) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- Load Chart.js 2.x (required by ConsoleTVs/Charts v6) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js" charset="utf-8"></script>

{{-- Render the specific Chart JS --}}
{!! $salesChart->script() !!}
{!! $yearlyChart->script() !!}
{!! $pieChart->script() !!}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle custom legend interaction for Pie Chart
        const legendItems = document.querySelectorAll('.legend-item');
        legendItems.forEach(item => {
            item.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                const chartName = "{{ $pieChart->id }}";
                const chartInstance = window[chartName];
                
                const meta = chartInstance.getDatasetMeta(0);
                meta.data[index].hidden = !meta.data[index].hidden;
                this.style.opacity = meta.data[index].hidden ? '0.3' : '1';
                chartInstance.update();
            });
        });
    });
</script>

<style>
    .btn-pink { background-color: #ec4899; color: white; border: none; }
    .btn-pink:hover { background-color: #db2777; color: white; }
    .border-pink { border-color: #fce7f3 !important; }
    .info-card:hover { transform: translateY(-5px); }
    .icon-box { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-size: 1.25rem; }
    .bg-soft-pink { background: #fce7f3; color: #ec4899; }
    .bg-soft-blue { background: #e0f2fe; color: #0ea5e9; }
    .bg-soft-green { background: #dcfce7; color: #22c55e; }
    .bg-soft-orange { background: #ffedd5; color: #f97316; }
    .bg-soft-purple { background: #f3e8ff; color: #a855f7; }
    .bg-soft-teal { background: #f0fdfa; color: #14b8a6; }
    .info-card { transition: all 0.3s cubic-bezier(.25,.8,.25,1); border: 1px solid transparent !important;}
    a:hover .info-card { 
        background-color: #fff !important;
        border-color: #ec4899 !important;
        box-shadow: 0 10px 15px -3px rgba(236, 72, 153, 0.1) !important;
    }
    #chart-legend::-webkit-scrollbar { width: 5px; }
    #chart-legend::-webkit-scrollbar-thumb { background: #ec4899; border-radius: 10px; }
</style>
@endpush
@endsection