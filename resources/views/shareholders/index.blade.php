@extends('layouts.app')
@section('title', 'لیست سهامداران')

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        .shareholder-block {
            background: #fff;
            box-shadow: 0 6px 24px 0 rgba(0,0,0,.08);
            border-radius: 16px;
            margin-bottom: 36px;
            padding: 24px 18px 16px 18px;
            overflow: hidden;
        }
        .shareholder-header {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }
        .shareholder-avatar {
            width: 64px;
            height:64px;
            border-radius: 50%;
            background: #f4f4f4;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.6rem;
            color: #666;
            margin-left: 22px;
        }
        .shareholder-name {
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 0;
        }
        .shareholder-section {
            margin-bottom: 16px;
        }
        .product-table th, .product-table td {
            font-size: 0.95rem;
            padding: 0.25rem 0.5rem;
        }
        .chart-container {
            margin-bottom: 10px;
            background: #fafdff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 8px 0 8px;
        }
        .activities-list, .losses-list {
            padding-right: 1rem;
        }
        .activities-list li, .losses-list li {
            font-size: 0.95rem;
        }
        @media (max-width: 900px) {
            .shareholder-block {
                padding: 12px 5px 10px 5px;
            }
            .shareholder-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .shareholder-avatar {
                margin-left: 0;
                margin-bottom: 8px;
            }
        }
    </style>
@endsection

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">لیست سهامداران</h2>
    @foreach($shareholders as $shareholder)
        <div class="shareholder-block" id="shareholder-{{ $shareholder->id }}">
            <div class="shareholder-header">
                <div class="shareholder-avatar">
                    {{ mb_substr($shareholder->full_name,0,1) }}
                </div>
                <div>
                    <div class="shareholder-name">{{ $shareholder->full_name }}</div>
                    <div>
                        <span class="badge bg-info text-dark">درصد کل سهام: {{ $summary[$shareholder->id]['share_percent'] ?? '-' }}%</span>
                    </div>
                </div>
            </div>
            <div class="row shareholder-section">
                <div class="col-sm-6 col-lg-4 mb-2">
                    <span class="text-secondary">تعداد محصولات:</span>
                    <span>{{ $summary[$shareholder->id]['products_count'] ?? '-' }}</span>
                </div>
                <div class="col-sm-6 col-lg-4 mb-2">
                    <span class="text-secondary">حجم فروش (تعداد):</span>
                    <span>{{ $summary[$shareholder->id]['sell_quantity'] ?? '-' }}</span>
                </div>
                <div class="col-sm-6 col-lg-4 mb-2">
                    <span class="text-secondary">تعداد تراکنش فروش:</span>
                    <span>{{ $summary[$shareholder->id]['sell_transactions'] ?? '-' }}</span>
                </div>
                <div class="col-sm-6 col-lg-4 mb-2">
                    <span class="text-secondary">مجموع فروش:</span>
                    <span class="text-success">{{ number_format($summary[$shareholder->id]['totalSell'] ?? 0) }}</span> <small>تومان</small>
                </div>
                <div class="col-sm-6 col-lg-4 mb-2">
                    <span class="text-secondary">سود کل:</span>
                    <span style="color:{{ ($summary[$shareholder->id]['profit'] ?? 0) >= 0 ? 'green':'red' }}">
                        {{ number_format($summary[$shareholder->id]['profit'] ?? 0) }}
                    </span>
                    <small>تومان</small>
                </div>
            </div>

            <div class="row shareholder-section">
                <div class="col-md-6 mb-2">
                    <div class="chart-container">
                        <div class="text-center mb-2"><b>نمودار فروش ماهانه</b></div>
                        <div id="monthly-sales-chart-{{ $shareholder->id }}" style="height:180px;direction:ltr"></div>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="chart-container">
                        <div class="text-center mb-2"><b>نمودار محصولات اضافه شده ماهانه</b></div>
                        <div id="monthly-products-chart-{{ $shareholder->id }}" style="height:180px;direction:ltr"></div>
                    </div>
                </div>
            </div>
            <div class="row shareholder-section">
                <div class="col-md-6 mb-2">
                    <div class="chart-container">
                        <div class="text-center mb-2"><b>نمودار فروش سالانه</b></div>
                        <div id="yearly-sales-chart-{{ $shareholder->id }}" style="height:180px;direction:ltr"></div>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="chart-container">
                        <div class="text-center mb-2"><b>نمودار محصولات اضافه شده سالانه</b></div>
                        <div id="yearly-products-chart-{{ $shareholder->id }}" style="height:180px;direction:ltr"></div>
                    </div>
                </div>
            </div>

            @if(!empty($summary[$shareholder->id]['products']))
            <div class="mb-2 shareholder-section">
                <b>محصولات سهامدار:</b>
                <div class="table-responsive">
                    <table class="table table-sm table-striped product-table">
                        <thead>
                            <tr>
                                <th>محصول</th>
                                <th>درصد مالکیت</th>
                                <th>قیمت خرید</th>
                                <th>قیمت فروش</th>
                                <th>سود/زیان</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($summary[$shareholder->id]['products'] as $product)
                            <tr>
                                <td>{{ $product['name'] }}</td>
                                <td>{{ $product['percent'] }}%</td>
                                <td>{{ number_format($product['buy_price']) }}</td>
                                <td>{{ number_format($product['sell_price']) }}</td>
                                <td style="color: {{ $product['profit'] >= 0 ? 'green':'red' }};">
                                    {{ number_format($product['profit']) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if(!empty($summary[$shareholder->id]['losses']))
            <div class="mb-2 shareholder-section">
                <b class="text-danger">ضررها:</b>
                <ul class="losses-list">
                    @foreach($summary[$shareholder->id]['losses'] as $loss)
                        <li>{{ $loss }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty($summary[$shareholder->id]['activities']))
            <div class="mb-2 shareholder-section">
                <b class="text-primary">کارهای مرتبط:</b>
                <ul class="activities-list">
                    @foreach($summary[$shareholder->id]['activities'] as $activity)
                        <li>{{ $activity }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <a href="{{ route('shareholders.show', $shareholder->id) }}" class="btn btn-primary btn-sm mt-2">نمایش اطلاعات مالی و جزئیات</a>
        </div>
    @endforeach
</div>
@endsection

@section('scripts')
<script>
@foreach($shareholders as $shareholder)
    // داده‌ها باید از کنترلر آماده شود
    let monthlySalesData_{{ $shareholder->id }} = {!! json_encode($summary[$shareholder->id]['monthly_sales'] ?? []) !!};
    let monthlySalesLabels_{{ $shareholder->id }} = {!! json_encode($summary[$shareholder->id]['monthly_sales_labels'] ?? []) !!};
    let monthlyProductsData_{{ $shareholder->id }} = {!! json_encode($summary[$shareholder->id]['monthly_products'] ?? []) !!};
    let monthlyProductsLabels_{{ $shareholder->id }} = {!! json_encode($summary[$shareholder->id]['monthly_products_labels'] ?? []) !!};
    let yearlySalesData_{{ $shareholder->id }} = {!! json_encode($summary[$shareholder->id]['yearly_sales'] ?? []) !!};
    let yearlySalesLabels_{{ $shareholder->id }} = {!! json_encode($summary[$shareholder->id]['yearly_sales_labels'] ?? []) !!};
    let yearlyProductsData_{{ $shareholder->id }} = {!! json_encode($summary[$shareholder->id]['yearly_products'] ?? []) !!};
    let yearlyProductsLabels_{{ $shareholder->id }} = {!! json_encode($summary[$shareholder->id]['yearly_products_labels'] ?? []) !!};

    new ApexCharts(document.querySelector("#monthly-sales-chart-{{ $shareholder->id }}"), {
        chart: { type: 'bar', height: 180, fontFamily: 'Vazirmatn, Tahoma, Arial' },
        series: [{ name: 'فروش (تومان)', data: monthlySalesData_{{ $shareholder->id }} }],
        xaxis: { categories: monthlySalesLabels_{{ $shareholder->id }}, labels: { style: { fontFamily: 'Vazirmatn' } } },
        colors: ['#2563eb'],
        dataLabels: { enabled: false }
    }).render();

    new ApexCharts(document.querySelector("#monthly-products-chart-{{ $shareholder->id }}"), {
        chart: { type: 'line', height: 180, fontFamily: 'Vazirmatn, Tahoma, Arial' },
        series: [{ name: 'تعداد محصول جدید', data: monthlyProductsData_{{ $shareholder->id }} }],
        xaxis: { categories: monthlyProductsLabels_{{ $shareholder->id }}, labels: { style: { fontFamily: 'Vazirmatn' } } },
        colors: ['#16a34a'],
        dataLabels: { enabled: false }
    }).render();

    new ApexCharts(document.querySelector("#yearly-sales-chart-{{ $shareholder->id }}"), {
        chart: { type: 'bar', height: 180, fontFamily: 'Vazirmatn, Tahoma, Arial' },
        series: [{ name: 'فروش سالانه (تومان)', data: yearlySalesData_{{ $shareholder->id }} }],
        xaxis: { categories: yearlySalesLabels_{{ $shareholder->id }}, labels: { style: { fontFamily: 'Vazirmatn' } } },
        colors: ['#f59e42'],
        dataLabels: { enabled: false }
    }).render();

    new ApexCharts(document.querySelector("#yearly-products-chart-{{ $shareholder->id }}"), {
        chart: { type: 'area', height: 180, fontFamily: 'Vazirmatn, Tahoma, Arial' },
        series: [{ name: 'تعداد محصول جدید سالانه', data: yearlyProductsData_{{ $shareholder->id }} }],
        xaxis: { categories: yearlyProductsLabels_{{ $shareholder->id }}, labels: { style: { fontFamily: 'Vazirmatn' } } },
        colors: ['#3b82f6'],
        dataLabels: { enabled: false }
    }).render();
@endforeach
</script>
@endsection
