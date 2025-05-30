@extends('layouts.app')

@section('title', 'لیست محصولات پیشرفته')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .product-inventory-low {
            background: #fff1f2 !important;
            border-left: 5px solid #f43f5e !important;
        }
        .product-inventory-warning {
            background: #fffbe7 !important;
            border-left: 5px solid #fbbf24 !important;
        }
        .product-inventory-ok {
            background: #f4fff9 !important;
            border-left: 5px solid #10b981 !important;
        }
        .card-product {
            min-width: 280px;
            box-shadow: 0 2px 14px #0b29431a;
            border-radius: 14px;
            margin-bottom: 18px;
            overflow: hidden;
        }
        .card-product .badge {
            font-size: 0.88rem;
        }
        .stats-cards .card {
            min-width: 210px;
        }
        .search-tools input, .search-tools select {
            border-radius: 8px;
            min-width: 120px;
        }
        .stock-badge {
            font-size: 1.03em;
            padding: 0.36em 0.7em;
        }
        .stock-badge.low { background: #f43f5e22; color: #b91c1c; border: 1px solid #f43f5e77; }
        .stock-badge.warn { background: #fbbf2442; color: #b28900; border: 1px solid #eab30877; }
        .stock-badge.ok { background: #10b98122; color: #065f46; border: 1px solid #10b98177; }
        .sortable-link { text-decoration: none; color: inherit; }
        .sortable-link .bi { font-size: 1em; vertical-align: middle; }
        th { white-space: nowrap; }
    </style>
@endsection

@section('content')
<div class="container-fluid mt-3">

    {{-- کارت‌های آماری بالا --}}
    <div class="row mb-4 stats-cards">
        <div class="col-auto mb-2">
            <div class="card bg-light border-0 shadow-sm text-center px-4 py-3">
                <div class="fw-bold fs-4 text-success"><i class="bi bi-box-seam"></i> {{ number_format($products->total()) }}</div>
                <div class="text-muted">کل محصولات</div>
            </div>
        </div>
        <div class="col-auto mb-2">
            <div class="card bg-light border-0 shadow-sm text-center px-4 py-3">
                <div class="fw-bold fs-4 text-danger"><i class="bi bi-exclamation-triangle"></i>
                    {{ number_format($products->where('stock', '<=', \App\Models\Product::STOCK_ALERT_DEFAULT ?? 1)->count()) }}
                </div>
                <div class="text-muted">کمبود موجودی</div>
            </div>
        </div>
        <div class="col-auto mb-2">
            <div class="card bg-light border-0 shadow-sm text-center px-4 py-3">
                <div class="fw-bold fs-4"><i class="bi bi-tags"></i> {{ number_format($categories_count ?? 0) }}</div>
                <div class="text-muted">دسته‌بندی‌ها</div>
            </div>
        </div>
        <div class="col-auto mb-2">
            <div class="card bg-light border-0 shadow-sm text-center px-4 py-3">
                <div class="fw-bold fs-4"><i class="bi bi-building"></i> {{ number_format($brands_count ?? 0) }}</div>
                <div class="text-muted">برندها</div>
            </div>
        </div>
    </div>

    {{-- جستجو و فیلتر --}}
    <form method="GET" class="card card-body mb-4 search-tools shadow-sm">
        <div class="row align-items-end g-2">
            <div class="col-md-3">
                <label class="form-label mb-1">نام محصول</label>
                <input type="text" class="form-control" name="name" value="{{ request('name') }}" placeholder="جستجو بر اساس نام...">
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1">کد کالا</label>
                <input type="text" class="form-control" name="code" value="{{ request('code') }}" placeholder="کد...">
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1">دسته‌بندی</label>
                <select class="form-select" name="category_id">
                    <option value="">همه</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @if(request('category_id') == $cat->id) selected @endif>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1">برند</label>
                <select class="form-select" name="brand_id">
                    <option value="">همه</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" @if(request('brand_id') == $brand->id) selected @endif>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1">وضعیت موجودی</label>
                <select class="form-select" name="inventory">
                    <option value="">همه</option>
                    <option value="low" @if(request('inventory') == 'low') selected @endif>موجودی کم</option>
                    <option value="zero" @if(request('inventory') == 'zero') selected @endif>اتمام موجودی</option>
                    <option value="ok" @if(request('inventory') == 'ok') selected @endif>وضعیت مناسب</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </form>

    {{-- لیست محصولات به صورت کارت و جدول --}}
    <div class="row g-3 mb-4">
        @forelse($products as $product)
            @php
                $stock_alert = $product->stock_alert ?? ($product::STOCK_ALERT_DEFAULT ?? 1);
                $stock_status = $product->stock <= 0
                    ? 'low'
                    : ($product->stock <= $stock_alert ? 'warn' : 'ok');
            @endphp
            <div class="col-12 col-lg-6 col-xxl-4">
                <div class="card card-product shadow-sm
                    @if($stock_status=='low') product-inventory-low
                    @elseif($stock_status=='warn') product-inventory-warning
                    @else product-inventory-ok @endif
                ">
                    <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                        <div class="flex-grow-1">
                            <h5 class="mb-1">{{ $product->name }}</h5>
                            <div class="mb-2 text-muted fs-7">
                                <span class="me-2"><i class="bi bi-upc-scan"></i> {{ $product->code }}</span>
                                <span class="me-2"><i class="bi bi-list-task"></i> {{ $product->category?->name ?? '-' }}</span>
                                <span class="me-2"><i class="bi bi-building"></i> {{ $product->brand?->name ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="badge bg-info me-2">قیمت: {{ number_format($product->sell_price) }} تومان</span>
                                <span class="badge stock-badge {{ $stock_status }}">
                                    <i class="bi bi-archive"></i>
                                    @if($stock_status=='low')
                                        اتمام موجودی
                                    @elseif($stock_status=='warn')
                                        موجودی کم ({{ $product->stock }})
                                    @else
                                        موجودی: {{ $product->stock }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-2 mt-3 mt-md-0 ms-md-3">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" onclick="return confirm('آیا مطمئن هستید؟')"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 d-flex flex-wrap gap-2 justify-content-between">
                        <small class="text-muted">ثبت: {{ jdate($product->created_at)->format('Y/m/d') }}</small>
                        @if(isset($product->expire_date))
                            <small class="text-danger">انقضا: {{ $product->expire_date }}</small>
                        @endif
                        <small>واحد: {{ $product->unit ?? '-' }}</small>
                        <small>کمترین سفارش: {{ $product->min_order_qty ?? '-' }}</small>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-danger text-center">محصولی یافت نشد.</div>
            </div>
        @endforelse
    </div>

    {{-- جدول ساده (برای دسترسی سریع) --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <b>جدول محصولات</b>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            @php
                                function sortable($col, $label, $sort, $direction) {
                                    $newDir = ($sort == $col && $direction == 'asc') ? 'desc' : 'asc';
                                    $icon = '';
                                    if ($sort == $col) {
                                        $icon = $direction == 'asc' ? 'bi bi-caret-up-fill' : 'bi bi-caret-down-fill';
                                    }
                                    $params = array_merge(request()->except('page'), ['sort' => $col, 'direction' => $newDir]);
                                    $url = url()->current() . '?' . http_build_query($params);
                                    return '<a href="'.$url.'" class="sortable-link">'.$label.($icon ? ' <i class="'.$icon.'"></i>' : '').'</a>';
                                }
                            @endphp
                            <th>{!! sortable('id', '#', $sort, $direction) !!}</th>
                            <th>{!! sortable('name', 'نام', $sort, $direction) !!}</th>
                            <th>{!! sortable('code', 'کد', $sort, $direction) !!}</th>
                            <th>{!! sortable('category_id', 'دسته‌بندی', $sort, $direction) !!}</th>
                            <th>{!! sortable('brand_id', 'برند', $sort, $direction) !!}</th>
                            <th>{!! sortable('sell_price', 'قیمت فروش', $sort, $direction) !!}</th>
                            <th>{!! sortable('stock', 'موجودی', $sort, $direction) !!}</th>
                            <th>{!! sortable('stock_alert', 'هشدار موجودی', $sort, $direction) !!}</th>
                            <th>{!! sortable('min_order_qty', 'حداقل سفارش', $sort, $direction) !!}</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $row=($products->currentPage()-1)*$products->perPage(); @endphp
                        @forelse($products as $product)
                            @php
                                $row++;
                                $stock_alert = $product->stock_alert ?? ($product::STOCK_ALERT_DEFAULT ?? 1);
                                $stock_status = $product->stock <= 0
                                    ? 'اتمام موجودی'
                                    : ($product->stock <= $stock_alert ? 'کم' : 'مناسب');
                            @endphp
                            <tr @if($stock_status=='اتمام موجودی') class="table-danger"
                                @elseif($stock_status=='کم') class="table-warning"
                                @endif>
                                <td>{{ $row }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->code }}</td>
                                <td>{{ $product->category?->name ?? '-' }}</td>
                                <td>{{ $product->brand?->name ?? '-' }}</td>
                                <td>{{ number_format($product->sell_price) }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>{{ $stock_alert }}</td>
                                <td>{{ $product->min_order_qty }}</td>
                                <td>
                                    @if($stock_status=='اتمام موجودی')
                                        <span class="badge bg-danger">اتمام موجودی</span>
                                    @elseif($stock_status=='کم')
                                        <span class="badge bg-warning text-dark">کم</span>
                                    @else
                                        <span class="badge bg-success">مناسب</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" onclick="return confirm('آیا مطمئن هستید؟')"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center text-danger">محصولی یافت نشد.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        // اسکرول خودکار به محصولات کم‌موجودی
        document.addEventListener('DOMContentLoaded', function() {
            let lowCard = document.querySelector('.product-inventory-low');
            if(lowCard){
                lowCard.scrollIntoView({behavior: "smooth", block: "center"});
            }
        });
    </script>
@endsection
