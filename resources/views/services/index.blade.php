@extends('layouts.app')

@section('title', 'لیست خدمات')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #e3f0ff 0%, #f9fcff 100%);
    }
    .service-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 32px 0 #a4dbf7a8;
        transition: 0.3s;
        border: none;
    }
    .service-card:hover {
        transform: translateY(-5px) scale(1.03);
        box-shadow: 0 8px 32px 0 #3ec6ec27;
    }
    .stat-card {
        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        border-radius: 15px;
        color: #234;
        box-shadow: 0 2px 8px 0 #a4dbf7a8;
        border: none;
    }
    .stat-card .card-title {
        color: #065e7c;
        font-weight: 600;
    }
    .stat-card .display-6 {
        color: #065e7c;
        font-weight: bold;
    }
    .btn-main {
        background: linear-gradient(90deg,#007cf0 0,#00dfd8 100%);
        color: #fff !important;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.2s;
    }
    .btn-main:hover {
        background: linear-gradient(90deg,#00dfd8 0,#007cf0 100%);
        color: #fff !important;
        transform: scale(1.04);
    }
    .table thead th {
        background: #f3fafe;
        color: #0984e3;
        font-weight: bold;
        border-bottom: 2px solid #8fd3f4;
    }
    .table-striped > tbody > tr:nth-of-type(odd) {
        background: #f7fcff;
    }
    .table-striped > tbody > tr:nth-of-type(even) {
        background: #e3f0ff;
    }
    .badge-category {
        background: linear-gradient(90deg, #ffecd2 0%, #fcb69f 100%);
        color: #b15f00;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 0.85rem;
    }
    .carousel-item {
        padding: 10px 0 30px 0;
    }
    .chart-container {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 2px 12px 0 #a4dbf777;
        padding: 25px 18px;
        margin-bottom: 28px;
    }
    .filter-form {
        background: linear-gradient(90deg, #e3ffe7 0%, #d9e7ff 100%);
        padding: 18px 18px 0 18px;
        border-radius: 13px;
        margin-bottom: 28px;
        box-shadow: 0 2px 8px #bbf0ff1a;
    }
</style>

<div class="container py-4">

    <h1 class="mb-4 text-center" style="color: #065e7c; font-weight: 800;">
        <i class="bi bi-stars" style="color:#00dfd8;"></i> لیست خدمات
    </h1>

    {{-- آمار کلی --}}
    <div class="row mb-3 g-3">
        <div class="col-md-3">
            <div class="card stat-card text-center">
                <div class="card-title">تعداد کل خدمات</div>
                <div class="display-6">{{ $totalServices }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card text-center">
                <div class="card-title">تعداد کل فروش‌ها</div>
                <div class="display-6">{{ $totalSells }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card text-center">
                <div class="card-title">مجموع سود کل</div>
                <div class="display-6">{{ number_format($totalProfit) }} <span style="font-size:0.8rem;">ریال</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card text-center">
                <div class="card-title">افزودن خدمت جدید</div>
                <a href="{{ route('services.create') }}" class="btn btn-main mt-2">
                    <i class="bi bi-plus-circle"></i> افزودن خدمت
                </a>
            </div>
        </div>
    </div>

    {{-- کاروسل خدمات پرفروش --}}
    <div class="row">
        <div class="col-12">
            <div class="card service-card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3" style="color:#0984e3;"><i class="bi bi-fire"></i> خدمات پرفروش</h5>
                    <div id="topServicesCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($topServices as $index => $service)
                                <div class="carousel-item {{ $index==0 ? 'active':'' }}">
                                    <div class="d-flex align-items-center justify-content-center flex-column">
                                        <h5 style="color:#007cf0;">{{ $service->title }}</h5>
                                        <span class="badge badge-category mb-2">
                                            {{ optional($service->category)->title ?: 'بدون دسته‌بندی' }}
                                        </span>
                                        <span class="fw-bold" style="color:#00b894;">{{ $service->sells_count }} فروش</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#topServicesCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#topServicesCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- چارت فروش ماهانه --}}
    <div class="row">
        <div class="col-12">
            <div class="chart-container mb-4">
                <h5 style="color:#00b894; font-weight:700;">نمودار سود و فروش ماهانه</h5>
                <canvas id="profitChart" height="110"></canvas>
            </div>
        </div>
    </div>

    {{-- فیلترها --}}
    <form method="get" class="filter-form mb-4">
        <div class="row g-2">
            <div class="col-md-3">
                <input name="name" type="text" class="form-control" placeholder="عنوان خدمت..." value="{{ request('name') }}">
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">دسته‌بندی (همه)</option>
                    @foreach($serviceCategories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id ? 'selected' : '' }}>
                            {{ $cat->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="unit" class="form-select">
                    <option value="">واحد (همه)</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit')==$unit->id ? 'selected' : '' }}>
                            {{ $unit->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-select">
                    <option value="id" {{ request('sort')=='id' ? 'selected' : '' }}>جدیدترین</option>
                    <option value="sells_count" {{ request('sort')=='sells_count' ? 'selected' : '' }}>پرفروش‌ترین</option>
                    <option value="profit_sum" {{ request('sort')=='profit_sum' ? 'selected' : '' }}>بیشترین سود</option>
                    <option value="price" {{ request('sort')=='price' ? 'selected' : '' }}>قیمت</option>
                </select>
            </div>
            <div class="col-md-1 d-grid">
                <button class="btn btn-main" type="submit"><i class="bi bi-search"></i> جستجو</button>
            </div>
        </div>
    </form>

    {{-- جدول خدمات --}}
    <div class="card service-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>کد</th>
                            <th>عنوان خدمت</th>
                            <th>دسته‌بندی</th>
                            <th>قیمت</th>
                            <th>تعداد فروش</th>
                            <th>سود کل</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr>
                                <td>{{ $service->service_code }}</td>
                                <td>
                                    <span class="fw-bold" style="color:#0984e3">{{ $service->title }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-category">
                                        {{ optional($service->category)->title ?: 'بدون دسته‌بندی' }}
                                    </span>
                                </td>
                                <td>{{ number_format($service->price) }} ریال</td>
                                <td><span class="badge bg-info text-dark">{{ $service->sells_count }}</span></td>
                                <td><span class="badge bg-success text-white">{{ number_format($service->profit_sum) }}</span></td>
                                <td>
                                    @if($service->is_active)
                                        <span class="badge bg-success">فعال</span>
                                    @else
                                        <span class="badge bg-danger">غیرفعال</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-outline-primary">ویرایش</a>
                                    <a href="{{ route('services.show', $service->id) }}" class="btn btn-sm btn-outline-secondary">مشاهده</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">خدمتی یافت نشد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pt-3 d-flex justify-content-center">
                {!! $services->withQueryString()->links() !!}
            </div>
        </div>
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = @json($chartData);
    const months = chartData.map(item => item.yyyymm);
    const profits = chartData.map(item => item.profit_sum);
    const sells = chartData.map(item => item.sells_count);

    const ctx = document.getElementById('profitChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'سود ماهانه (ریال)',
                    data: profits,
                    backgroundColor: 'rgba(0, 223, 216, 0.65)',
                    borderColor: '#00dfd8',
                    borderWidth: 2,
                    yAxisID: 'y',
                    borderRadius: 8,
                },
                {
                    label: 'تعداد فروش',
                    data: sells,
                    type: 'line',
                    borderColor: '#0984e3',
                    backgroundColor: 'rgba(9,132,227,0.15)',
                    borderWidth: 3,
                    yAxisID: 'y1',
                    pointStyle: 'circle',
                    pointRadius: 5,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: { display: true, text: 'سود (ریال)' }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    title: { display: true, text: 'تعداد فروش' }
                }
            },
            plugins: {
                legend: { display: true, position: 'top' }
            }
        }
    });
</script>
@endsection
