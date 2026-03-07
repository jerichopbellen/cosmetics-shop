@extends('layouts.base')

@section('body')
<div class="container py-5 mt-4">
    <h2 class="fw-bold text-dark mb-4"><i class="fa-solid fa-chart-line me-2 text-pink"></i>Store Analytics</h2>

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

        <div class="tab-pane fade" id="yearly" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold text-muted text-uppercase mb-4">Historical Yearly Revenue</h6>
                <div style="height: 400px;"><canvas id="yearlyLineChart"></canvas></div>
            </div>
        </div>

        <div class="tab-pane fade" id="share" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <h6 class="fw-bold text-muted text-uppercase mb-4">Product Revenue Share</h6>
                <div style="height: 400px;"><canvas id="productPieChart"></canvas></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pink = '#ec4899';
        
        // Options to hide the legend/button for single-dataset charts
        const hideLegend = { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { legend: { display: false } } 
        };

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
            options: hideLegend
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
            options: hideLegend
        });

        // 3. PRODUCT PIE CHART
        new Chart(document.getElementById('productPieChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($pieLabels) !!},
                datasets: [{ 
                    data: {!! json_encode($pieData) !!}, 
                    backgroundColor: [pink, '#db2777', '#be185d', '#9d174d', '#831843'] 
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } } 
            }
        });
    });
</script>
@endpush
@endsection