@extends('layouts.base')

@section('body')
<div class="container py-5 mt-4">
    <div class="d-flex align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="fa-solid fa-chart-line me-2 text-pink"></i>Store Analytics</h2>
        <span class="ms-3 badge rounded-pill bg-soft-pink text-pink border border-pink d-flex align-items-center px-3 py-2">
            <span class="pulse-dot me-2"></span> LIVE DATA
        </span>
    </div>

    {{-- 1. INFINITE + MANUAL SCROLLABLE STAT CARDS --}}
    <div class="stat-scroller-container mb-5" id="scrollerContainer">
        <div class="scroller-inner" id="scrollerInner">
            @php
                $cards = [
                    ['route' => route('admin.dashboard'), 'icon' => 'fa-coins', 'bg' => 'soft-pink', 'txt' => 'pink', 'label' => 'SALES', 'val' => '&#8369;' . number_format($totalSales, 0)],
                    ['route' => route('admin.orders.index'), 'icon' => 'fa-shopping-cart', 'bg' => 'soft-blue', 'txt' => 'blue', 'label' => 'ORDERS', 'val' => number_format($totalOrders)],
                    ['route' => route('products.index'), 'icon' => 'fa-box', 'bg' => 'soft-orange', 'txt' => 'orange', 'label' => 'PRODUCTS', 'val' => number_format($totalProducts)],
                    ['route' => route('brands.index'), 'icon' => 'fa-copyright', 'bg' => 'soft-purple', 'txt' => 'purple', 'label' => 'BRANDS', 'val' => number_format($totalBrands)],
                    ['route' => route('categories.index'), 'icon' => 'fa-layer-group', 'bg' => 'soft-teal', 'txt' => 'teal', 'label' => 'CATEGORIES', 'val' => number_format($totalCategories)],
                    ['route' => route('admin.users.index'), 'icon' => 'fa-users', 'bg' => 'soft-green', 'txt' => 'green', 'label' => 'CUSTOMERS', 'val' => number_format($totalCustomers)],
                    ['route' => route('reviews.index'), 'icon' => 'fa-star', 'bg' => 'soft-yellow', 'txt' => 'yellow', 'label' => 'RATINGS', 'val' => $averageRating . ' <span class="small text-muted" style="font-size: 0.7rem;">/ 5</span>']
                ];
            @endphp

            {{-- Set 1 --}}
            @foreach($cards as $card)
                <div class="stat-card-item">
                    <a href="{{ $card['route'] }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm p-3 info-card h-100">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-{{ $card['bg'] }} text-{{ $card['txt'] }}">
                                    <i class="fa-solid {{ $card['icon'] }}"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted fw-bold">{{ $card['label'] }}</small>
                                    <h5 class="fw-bold mb-0 text-dark text-nowrap">{!! $card['val'] !!}</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach

            {{-- Set 2 (Duplicate for Seamless Loop) --}}
            @foreach($cards as $card)
                <div class="stat-card-item" aria-hidden="true">
                    <a href="{{ $card['route'] }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm p-3 info-card h-100">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-{{ $card['bg'] }} text-{{ $card['txt'] }}">
                                    <i class="fa-solid {{ $card['icon'] }}"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted fw-bold">{{ $card['label'] }}</small>
                                    <h5 class="fw-bold mb-0 text-dark text-nowrap">{!! $card['val'] !!}</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    {{-- 2. TABS NAVIGATION --}}
    <ul class="nav nav-tabs border-0 mb-4" id="analyticsTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active fw-bold" id="performance-tab" data-bs-toggle="tab" data-bs-target="#performance" type="button" role="tab">Sales Performance</button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" id="yearly-tab" data-bs-toggle="tab" data-bs-target="#yearly" type="button" role="tab">Yearly Revenue</button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" id="share-tab" data-bs-toggle="tab" data-bs-target="#share" type="button" role="tab">Product Share</button>
        </li>
    </ul>

    {{-- 3. TAB CONTENT --}}
    <div class="tab-content" id="analyticsTabsContent">
        <div class="tab-pane fade show active" id="performance" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-bold text-muted text-uppercase mb-0">Sales Activity</h6>
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex align-items-center gap-2">
                        <input type="date" name="start_date" class="form-control form-control-sm border-pink" value="{{ $start }}">
                        <input type="date" name="end_date" class="form-control form-control-sm border-pink" value="{{ $end }}">
                        <button type="submit" class="btn btn-pink btn-sm">Filter</button>
                    </form>
                </div>
                <div style="height: 400px;">{!! $salesChart->container() !!}</div>
            </div>
        </div>

        <div class="tab-pane fade" id="yearly" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold text-muted text-uppercase mb-4">Year-over-Year Revenue</h6>
                <div style="height: 400px;">{!! $yearlyChart->container() !!}</div>
            </div>
        </div>

        <div class="tab-pane fade" id="share" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold text-muted text-uppercase mb-4">Product Revenue Breakdown</h6>
                <div class="row align-items-center">
                    <div class="col-lg-7 border-end">
                        <div style="height: 400px;">{!! $pieChart->container() !!}</div>
                    </div>
                    <div class="col-lg-5">
                        <div id="chart-legend" class="ps-lg-3" style="max-height: 400px; overflow-y: auto;">
                            @php $totalRevenue = $pieData->sum(); @endphp
                            @foreach($pieLabels as $i => $label)
                                @php 
                                    $val = $pieData[$i];
                                    $pct = $totalRevenue > 0 ? number_format(($val / $totalRevenue) * 100, 1) : 0;
                                @endphp
                                <div class="d-flex align-items-center justify-content-between p-2 mb-1 rounded border-bottom legend-item" data-index="{{ $i }}" style="cursor: pointer;">
                                    <div class="d-flex align-items-center overflow-hidden">
                                        <span style="width:12px; height:12px; background:{{ $pieColors[$i] }}; border-radius:3px; flex-shrink:0; margin-right:10px;"></span>
                                        <span class="text-dark small fw-bold text-truncate">{{ $label }}</span>
                                    </div>
                                    <div class="text-end ms-2">
                                        <div class="small fw-bold text-pink">{{ $pct }}%</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">&#8369;{{ number_format($val) }}</div>
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

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js" charset="utf-8"></script>
{!! $salesChart->script() !!}
{!! $yearlyChart->script() !!}
{!! $pieChart->script() !!}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slider = document.getElementById('scrollerContainer');
        const inner = document.getElementById('scrollerInner');
        
        let isPaused = false;
        let isDown = false;
        let startX;
        let scrollLeft;

        // 1. AUTO-SCROLL LOGIC
        function step() {
            if (!isPaused && !isDown) {
                slider.scrollLeft += 1; // Adjust speed here (1 = slow, 2 = fast)
                
                // If we've scrolled past the first set, reset to start
                if (slider.scrollLeft >= inner.scrollWidth / 2) {
                    slider.scrollLeft = 0;
                }
            }
            requestAnimationFrame(step);
        }
        requestAnimationFrame(step);

        // Pause on Hover
        slider.addEventListener('mouseenter', () => isPaused = true);
        slider.addEventListener('mouseleave', () => isPaused = false);

        // 2. MANUAL DRAG-TO-SCROLL
        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.style.cursor = 'grabbing';
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        
        window.addEventListener('mouseup', () => {
            isDown = false;
            slider.style.cursor = 'grab';
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2; 
            slider.scrollLeft = scrollLeft - walk;

            // Manual scroll boundary check
            if (slider.scrollLeft >= inner.scrollWidth / 2) {
                slider.scrollLeft = 1;
            } else if (slider.scrollLeft <= 0) {
                slider.scrollLeft = (inner.scrollWidth / 2) - 1;
            }
        });

        // 3. PIE CHART LEGEND TOGGLE
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
    /* 1. SCROLLER UI */
    .stat-scroller-container {
        width: 100%;
        overflow-x: auto; /* Re-enabled for manual scroll */
        white-space: nowrap;
        cursor: grab;
        scrollbar-width: none;
        -ms-overflow-style: none;
        mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
        -webkit-mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
    }
    .stat-scroller-container::-webkit-scrollbar { display: none; }

    .scroller-inner {
        display: flex;
        width: max-content;
        gap: 1.5rem; 
        padding: 20px 0;
    }

    .stat-card-item {
        width: 260px; 
        flex-shrink: 0;
    }

    /* 2. UI POLISHING */
    .icon-box { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-size: 1.2rem; }
    .bg-soft-pink { background: #fce7f3; color: #ec4899; }
    .bg-soft-blue { background: #e0f2fe; color: #0ea5e9; }
    .bg-soft-green { background: #dcfce7; color: #22c55e; }
    .bg-soft-orange { background: #ffedd5; color: #f97316; }
    .bg-soft-purple { background: #f3e8ff; color: #a855f7; }
    .bg-soft-teal { background: #f0fdfa; color: #14b8a6; }
    .bg-soft-yellow { background: #fefce8; color: #eab308; }
    
    .info-card { 
        transition: all 0.3s ease; 
        border: 1px solid transparent !important;
    }
    .info-card:hover {
        transform: translateY(-8px);
        border-color: #ec4899 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }

    .btn-pink { background-color: #ec4899; color: white; border: none; }
    .nav-tabs .nav-link { color: #6b7280; border: none; padding: 10px 20px; }
    .nav-tabs .nav-link.active { color: #ec4899; border-bottom: 3px solid #ec4899; background: none; }

    /* 3. PULSE INDICATOR */
    .pulse-dot {
        width: 8px; height: 8px;
        background-color: #ec4899;
        border-radius: 50%;
        display: inline-block;
        animation: pulse-animation 2s infinite;
    }
    @keyframes pulse-animation {
        0% { box-shadow: 0 0 0 0px rgba(236, 72, 153, 0.7); }
        100% { box-shadow: 0 0 0 10px rgba(236, 72, 153, 0); }
    }
</style>
@endpush
@endsection