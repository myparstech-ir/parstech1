@extends('layouts.app')

@section('title', 'لیست خدمات پیشرفته')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.css">
    <style>
        .sortable-link { cursor:pointer; user-select:none; }
        .sortable-link .bi { vertical-align: middle; font-size:1em }
        .card-metric { min-width: 200px; border-radius: 12px; box-shadow: 0 3px 18px #4b22a01a; }
        .card-metric .metric-number { font-size:2rem; font-weight:bold; }
        .carousel .card { min-width: 270px; max-width: 320px; margin: auto; }
        .carousel-item { display: flex; justify-content: center; }
        .service-table th, .service-table td { vertical-align: middle; }
        .service-table tr:nth-child(even) {background: #f6f7fa;}
        .service-table th { background: #e3e6f6; }
        .service-table tr:hover {background: #f1ecfd;}
        .bg-metric1 { background: linear-gradient(90deg,#f7e6ff,#e2c8ff); }
        .bg-metric2 { background: linear-gradient(90deg,#e3f7ec,#b6f0d6); }
        .bg-metric3 { background: linear-gradient(90deg,#f7f0e3,#ffe0b7); }
    </style>
@endsection

@section('content')
<div class="container mt-3">

    {{-- کارت‌های آماری --}}
    <div class="row g-3 mb-4">
        <div class="col-auto">
            <div class="card card-metric bg-metric1 text-center px-4 py-3">
                <div class="metric-number text-primary">{{ number_format($totalServices) }}</div>
                <div class="text-muted">کل خدمات</div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card card-metric bg-metric2 text-center px-4 py-3">
                <div class="metric-number text-success">{{ number_format($totalSells) }}</div>
                <div class="text-muted">کل فروش خدمات</div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card card-metric bg-metric3 text-center px-4 py-3">
                <div class="metric-number text-warning">{{ number_format($totalProfit) }}</div>
                <div class="text-muted">مجموع سود خدمات</div>
            </div>
        </div>
    </div>

    {{-- کاروسل پرفروش‌ترین خدمات --}}
    @if($topServices->count())
    <div id="topServiceCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($topServices as $i => $service)
                <div class="carousel-item @if($i==0) active @endif">
                    <div class="card shadow card-metric bg-light p-3">
                        <h5 class="mb-2 text-info">{{ $service->title }}</h5>
                        <div class="mb-2 text-muted">
                            <i class="bi bi-tag"></i> {{ $service->service_code }}
                            | <i class="bi bi-collection"></i>
                            {{ $service->category?->name ?? '-' }}
                        </div>
                        <div>
                            <span class="badge bg-primary">فروش: {{ $service->sells_count }} مرتبه</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#topServiceCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">قبلی</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#topServiceCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">بعدی</span>
        </button>
    </div>
    @endif

    {{-- چارت روند فروش و سود خدمات --}}
    <div class="card mb-4">
        <div class="card-header bg-light">
            <strong><i class="bi bi-bar-chart-line"></i> چارت روند فروش و سود خدمات</strong>
        </div>
        <div class="card-body">
            <canvas id="serviceChart" height="70"></canvas>
        </div>
    </div>

    {{-- فرم جستجو و فیلتر --}}
    <form method="GET" class="card card-body mb-4">
        <div class="row align-items-end g-2">
            <div class="col-md-3">
                <label class="form-label mb-1">نام خدمت</label>
                <input type="text" class="form-control" name="name" value="{{ request('name') }}" placeholder="جستجو...">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">دسته‌بندی</label>
                <select class="form-select" name="category_id">
                    <option value="">همه</option>
                    @foreach($serviceCategories as $cat)
                        <option value="{{ $cat->id }}" @if(request('category_id') == $cat->id) selected @endif>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">واحد</label>
                <select class="form-select" name="unit">
                    <option value="">همه</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->title }}" @if(request('unit') == $unit->title) selected @endif>{{ $unit->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary"><i class="bi bi-search"></i> جستجو</button>
            </div>
        </div>
    </form>

    {{-- جدول لیست خدمات --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover service-table">
            <thead>
                <tr>
                    <th>
                        <a href="{{ route('services.index', array_merge(request()->except('sort', 'dir'), ['sort'=>'service_code', 'dir'=>$sort=='service_code'&&$dir=='asc'?'desc':'asc'])) }}"
                           class="sortable-link {{ $sort=='service_code'?'text-info fw-bold':'' }}">
                            کد <i class="bi bi-caret-{{ $sort=='service_code'?$dir:'down' }}"></i>
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('services.index', array_merge(request()->except('sort', 'dir'), ['sort'=>'title', 'dir'=>$sort=='title'&&$dir=='asc'?'desc':'asc'])) }}"
                           class="sortable-link {{ $sort=='title'?'text-info fw-bold':'' }}">
                            نام خدمت <i class="bi bi-caret-{{ $sort=='title'?$dir:'down' }}"></i>
                        </a>
                    </th>
                    <th>دسته‌بندی</th>
                    <th>واحد</th>
                    <th>
                        <a href="{{ route('services.index', array_merge(request()->except('sort', 'dir'), ['sort'=>'price', 'dir'=>$sort=='price'&&$dir=='asc'?'desc':'asc'])) }}"
                           class="sortable-link {{ $sort=='price'?'text-info fw-bold':'' }}">
                            قیمت <i class="bi bi-caret-{{ $sort=='price'?$dir:'down' }}"></i>
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('services.index', array_merge(request()->except('sort', 'dir'), ['sort'=>'sells_count', 'dir'=>$sort=='sells_count'&&$dir=='asc'?'desc':'asc'])) }}"
                           class="sortable-link {{ $sort=='sells_count'?'text-info fw-bold':'' }}">
                            تعداد فروش <i class="bi bi-caret-{{ $sort=='sells_count'?$dir:'down' }}"></i>
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('services.index', array_merge(request()->except('sort', 'dir'), ['sort'=>'profit_sum', 'dir'=>$sort=='profit_sum'&&$dir=='asc'?'desc':'asc'])) }}"
                           class="sortable-link {{ $sort=='profit_sum'?'text-info fw-bold':'' }}">
                            مجموع سود <i class="bi bi-caret-{{ $sort=='profit_sum'?$dir:'down' }}"></i>
                        </a>
                    </th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
            @forelse($services as $service)
                <tr>
                    <td>{{ $service->service_code }}</td>
                    <td>{{ $service->title }}</td>
                    <td>{{ $service->category?->name ?? '-' }}</td>
                    <td>{{ $service->unit }}</td>
                    <td class="text-primary">{{ number_format($service->price) }}</td>
                    <td><span class="badge bg-success">{{ $service->sells_count }}</span></td>
                    <td class="text-success">{{ number_format($service->profit_sum) }}</td>
                    <td>
                        <a href="{{ route('services.show', $service->id) }}" class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('services.edit', $service->id) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil"></i></a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">هیچ خدمتی یافت نشد.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            {{ $services->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let ctx = document.getElementById('serviceChart').getContext('2d');
    let chartData = @json($chartData);
    let labels = chartData.map(e => e.yyyymm);
    let sells = chartData.map(e => e.sells_count);
    let profit = chartData.map(e => e.profit_sum);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'تعداد فروش',
                    type: 'bar',
                    data: sells,
                    backgroundColor: '#3b82f6',
                    yAxisID: 'y',
                },
                {
                    label: 'مجموع سود',
                    type: 'line',
                    data: profit,
                    borderColor: '#fbbf24',
                    backgroundColor: '#fde68a55',
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { type: 'linear', position: 'left', beginAtZero:true, title:{display:true,text:'فروش'} },
                y1: { type: 'linear', position: 'right', beginAtZero:true, title:{display:true,text:'سود'} }
            }
        }
    });
});
</script>
@endsection
