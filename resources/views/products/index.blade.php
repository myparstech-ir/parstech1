@extends('layouts.app')

@section('title', 'لیست محصولات')

@section('head')
    <link href="https://cdn.jsdelivr.net/npm/@tailwindcss/ui@0.7.2/dist/tailwind-ui.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .tw-carousel-indicator {
            width: 0.8rem;
            height: 0.8rem;
            border-radius: 50%;
            margin: 0 0.18rem;
            background: #d1d5db;
            display: inline-block;
            transition: background 0.2s;
        }
        .tw-carousel-indicator-active {
            background: #2563eb !important;
        }
        .carousel-bg-fade {
            background: linear-gradient(120deg,#f3f4f6 70%,rgba(255,255,255,0.75) 100%);
            border-radius: 1.2rem;
            position: absolute;
            inset: 0;
            z-index: 2;
        }
        .carousel-product-img {
            max-width: 110px;
            max-height: 110px;
            border-radius: 0.8rem;
            box-shadow: 0 2px 16px #0002;
            background: #fff;
            object-fit: contain;
        }
        .carousel-badge {
            display: inline-block;
            padding: 0.21em 1em;
            border-radius: 999px;
            font-size: 0.95em;
            margin-left: 0.5em;
            margin-bottom: 0.2em;
        }
        .carousel-badge.low { background: #fef08a; color: #a16207; }
        .carousel-badge.price { background: #e0e7ff; color: #3730a3; }
        .carousel-badge.discount { background: #fee2e2; color: #b91c1c; }
        .carousel-badge.expired { background: #fca5a5; color: #991b1b; }
        .carousel-badge.unit { background: #d1fae5; color: #065f46; }
        .carousel-badge.ok { background: #dcfce7; color: #166534; }
        .carousel-badge.brand { background: #f3e8ff; color: #6d28d9; }
        .carousel-btn {
            background: #fff;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 8px #0001;
            color: #2563eb;
            border-radius: 999px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        .carousel-btn:hover {
            background: #eff6ff;
            color: #1d4ed8;
        }
        [dir="rtl"] .carousel-btn-left { right: 1rem; left: auto;}
        [dir="rtl"] .carousel-btn-right { left: 1rem; right: auto;}
        .carousel-btn-left { left: 1rem; }
        .carousel-btn-right { right: 1rem; }
        .carousel-content {
            position: relative;
            z-index: 3;
        }
        .carousel-bg-img {
            position: absolute;
            inset: 0;
            z-index: 1;
            background-size: cover;
            background-position: center;
            border-radius: 1.2rem;
            opacity: 0.25;
            filter: blur(0.6px);
        }
        .carousel-slide {
            min-width: 100%;
            position: relative;
            display: flex;
            align-items: stretch;
            background: linear-gradient(120deg,#e0e7ff 60%,#f1f5f9 100%);
            border-radius: 1.2rem;
        }
        .carousel-inner-rtl {
            flex-direction: row-reverse;
        }

        .products-table {
            width: 100%;
            min-width: 900px;
            font-size: 0.97rem;
            border-radius: 0.75rem;
            overflow: hidden;
        }
        .products-table th, .products-table td {
            white-space: nowrap;
            padding: 0.58rem 0.3rem !important;
            text-align: center;
            vertical-align: middle;
        }
        .products-table th {
            background: #f1f5f9;
            color: #374151;
            font-weight: bold;
            border-bottom: 2px solid #cbd5e1;
            cursor: pointer;
            user-select: none;
            transition: background 0.17s;
        }
        .products-table th:hover {
            background: #dbeafe;
            color: #2563eb;
        }
        .products-table td {
            background: #fff;
            color: #334155;
            text-align: center;
            vertical-align: middle;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .products-table tr {
            transition: box-shadow 0.17s, background 0.17s;
        }
        .products-table tr:hover {
            background: #f3f4f6;
            box-shadow: 0 2px 8px #2563eb0d;
        }
        .products-table tr.bg-yellow-50 {
            background: #fef9c3 !important;
        }
        .products-table tr.bg-red-50 {
            background: #fee2e2 !important;
        }
        .products-table tr.bg-green-50 {
            background: #dcfce7 !important;
        }
        .badge-status {
            display: inline-block;
            border-radius: 12px;
            font-size: 0.97em;
            font-weight: 500;
            padding: 0.12em 0.6em;
        }
        .badge-status.low { background: #fde68a; color: #a16207; }
        .badge-status.ok { background: #bbf7d0; color: #166534; }
        .badge-status.out { background: #fecaca; color: #991b1b; }
        .btn-actions {
            display: flex;
            align-items: center;
            gap: 0.12em;
            justify-content: center;
        }
        .products-table .btn {
            padding: 0.23em 0.55em;
            font-size: 0.93em;
            border-radius: 7px;
            font-weight: 500;
        }
        .products-table .btn-outline-info {
            border: 1px solid #60a5fa; color: #2563eb; background: #eff6ff;
        }
        .products-table .btn-outline-info:hover {
            background: #2563eb; color: #fff; border-color: #2563eb;
        }
        .products-table .btn-outline-warning {
            border: 1px solid #f59e42; color: #b45309; background: #fef3c7;
        }
        .products-table .btn-outline-warning:hover {
            background: #f59e42; color: #fff; border-color: #f59e42;
        }
        .products-table .btn-outline-danger {
            border: 1px solid #f87171; color: #b91c1c; background: #fee2e2;
        }
        .products-table .btn-outline-danger:hover {
            background: #f87171; color: #fff; border-color: #f87171;
        }
        @media (max-width: 1200px) { .products-table { min-width: 700px; } }
        @media (max-width: 900px) {
            .products-table th, .products-table td { font-size: 0.89rem !important; padding: 0.38rem 0.19rem !important; }
        }
        @media (max-width: 700px) { .products-table { min-width: 520px; font-size: 0.81rem; } }
        @media (max-width: 600px) {
            .products-table th, .products-table td { font-size: 0.71rem !important; padding: 0.17rem 0.04rem !important; }
        }
    </style>
@endsection

@section('content')
<div class="container mx-auto py-6">
    {{-- کاروسل محصولات کم موجودی --}}
    @if($lowStockProducts->count())
        <div
            x-data="{
                activeSlide: 0,
                slides: {{ $lowStockProducts->count() }},
                next() { this.activeSlide = (this.activeSlide + 1) % this.slides },
                prev() { this.activeSlide = (this.activeSlide - 1 + this.slides) % this.slides }
            }"
            dir="rtl"
            class="relative w-full mb-10"
        >
            <div class="flex items-center mb-3">
                <h5 class="font-bold text-blue-700 text-lg flex items-center">
                    <i class="bi bi-exclamation-triangle text-yellow-600 text-2xl ml-2"></i>
                    محصولات با موجودی کم
                </h5>
                <div class="mr-auto flex items-center gap-1">
                    <button @click="next" class="carousel-btn carousel-btn-right ml-2" :aria-label="'بعدی'" type="button"><i class="bi bi-chevron-left"></i></button>
                    <button @click="prev" class="carousel-btn carousel-btn-left" :aria-label="'قبلی'" type="button"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
            <div class="overflow-hidden rounded-xl shadow-lg bg-white relative" style="min-height:320px;">
                <div class="flex transition-all duration-500 carousel-inner-rtl"
                     :style="'transform: translateX(-'+(activeSlide*100)+'%); width: ' + (slides*100) + '%'">
                    @foreach($lowStockProducts as $product)
                        @php
                            $img = $product->image ?? (is_array($product->gallery ?? null) && count($product->gallery) ? $product->gallery[0] : null);
                            $imgUrl = $img
                                ? (str_starts_with($img, 'http')
                                    ? $img
                                    : (file_exists(public_path('storage/products/'.$img))
                                        ? asset('storage/products/'.$img)
                                        : asset('images/no-image.png')))
                                : asset('images/no-image.png');
                            $discount = $product->discount ? intval($product->discount) : 0;
                            $expired = isset($product->expire_date) && strtotime($product->expire_date) < time();
                        @endphp
                        <div class="carousel-slide py-4 px-2 md:px-5">
                            <div class="carousel-bg-img" style="background-image: url('{{ $imgUrl }}');"></div>
                            <div class="carousel-bg-fade"></div>
                            <div class="carousel-content flex flex-col md:flex-row items-center gap-5 p-5 w-full">
                                <div class="flex flex-col items-center justify-center w-full md:w-auto">
                                    <img src="{{ $imgUrl }}" alt="تصویر محصول" class="carousel-product-img mb-3 shadow" onerror="this.src='{{ asset('images/no-image.png') }}'">
                                    <span class="carousel-badge brand">{{ $product->brand?->name ?? '-' }}</span>
                                    <span class="carousel-badge unit">{{ $product->unit ?? '-' }}</span>
                                </div>
                                <div class="flex-1 w-full">
                                    <h2 class="text-2xl font-bold mb-2 flex items-center gap-2">
                                        {{ $product->name }}
                                        @if($expired)
                                            <span class="carousel-badge expired"><i class="bi bi-clock"></i> منقضی</span>
                                        @endif
                                    </h2>
                                    <div class="mb-2 text-gray-600 text-sm flex flex-wrap gap-3">
                                        <span><i class="bi bi-upc-scan"></i> {{ $product->code }}</span>
                                        <span><i class="bi bi-list-task"></i> {{ $product->category?->name ?? '-' }}</span>
                                        <span><i class="bi bi-calendar3"></i> {{ $product->expire_date ?: '-' }}</span>
                                    </div>
                                    <div class="flex items-center gap-3 mb-2 flex-wrap">
                                        <span class="carousel-badge price">
                                            <i class="bi bi-cash-coin"></i>
                                            {{ number_format($product->sell_price) }}
                                            <span class="text-xs">تومان</span>
                                        </span>
                                        @if($discount)
                                            <span class="carousel-badge discount">
                                                <i class="bi bi-percent"></i>
                                                تخفیف: {{ $discount }}٪
                                            </span>
                                        @endif
                                        <span class="carousel-badge low">
                                            <i class="bi bi-archive"></i>
                                            موجودی: {{ $product->stock }}
                                        </span>
                                        <span class="carousel-badge ok">
                                            هشدار: {{ $product->stock_alert ?? ($product::STOCK_ALERT_DEFAULT ?? 1) }}
                                        </span>
                                        <span class="carousel-badge unit">
                                            حداقل سفارش: {{ $product->min_order_qty ?? '-' }}
                                        </span>
                                    </div>
                                    <div class="flex gap-2 mt-2">
                                        <a href="@if(Route::has('products.show')){{ route('products.show', $product->id) }}@else # @endif" class="btn btn-outline-info flex items-center gap-1">
                                            <i class="bi bi-eye"></i><span>نمایش</span>
                                        </a>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-warning flex items-center gap-1">
                                            <i class="bi bi-pencil"></i><span>ویرایش</span>
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('آیا مطمئن هستید؟')" class="m-0 d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger flex items-center gap-1">
                                                <i class="bi bi-trash"></i><span>حذف</span>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="flex flex-wrap gap-4 text-xs text-gray-600 mt-3">
                                        <span>ثبت: {{ jdate($product->created_at)->format('Y/m/d') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="flex justify-center mt-4">
                <template x-for="i in slides">
                    <div :class="activeSlide === (i-1) ? 'tw-carousel-indicator tw-carousel-indicator-active' : 'tw-carousel-indicator'"
                        @click="activeSlide = i-1"
                        style="cursor:pointer"></div>
                </template>
            </div>
        </div>
    @endif

    {{-- کارت‌های آماری بالا --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 stats-cards">
        <div class="bg-green-50 border border-green-200 rounded shadow p-5 text-center">
            <div class="font-bold text-2xl text-green-700 flex items-center justify-center gap-1">
                <i class="bi bi-box-seam"></i> {{ number_format($products->total()) }}
            </div>
            <div class="text-gray-500 mt-2">کل محصولات</div>
        </div>
        <div class="bg-red-50 border border-red-200 rounded shadow p-5 text-center">
            <div class="font-bold text-2xl text-red-700 flex items-center justify-center gap-1">
                <i class="bi bi-exclamation-triangle"></i>
                {{ number_format($products->where('stock', '<=', \App\Models\Product::STOCK_ALERT_DEFAULT ?? 1)->count()) }}
            </div>
            <div class="text-gray-500 mt-2">کمبود موجودی</div>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded shadow p-5 text-center">
            <div class="font-bold text-2xl text-blue-700 flex items-center justify-center gap-1">
                <i class="bi bi-tags"></i> {{ number_format($categories_count ?? 0) }}
            </div>
            <div class="text-gray-500 mt-2">دسته‌بندی‌ها</div>
        </div>
        <div class="bg-yellow-50 border border-yellow-200 rounded shadow p-5 text-center">
            <div class="font-bold text-2xl text-yellow-700 flex items-center justify-center gap-1">
                <i class="bi bi-collection"></i> {{ number_format($brands_count ?? 0) }}
            </div>
            <div class="text-gray-500 mt-2">برندها</div>
        </div>
    </div>

    {{-- ابزارهای جستجو --}}
    <div class="bg-white border rounded-lg shadow mb-8">
        <div class="p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end search-tools">
                <div>
                    <label class="block mb-1 text-sm text-gray-700">نام یا کد</label>
                    <input type="text" name="q" class="form-input rounded w-full border-gray-300" value="{{ request('q') }}" placeholder="نام یا کد...">
                </div>
                <div>
                    <label class="block mb-1 text-sm text-gray-700">دسته‌بندی</label>
                    <select name="category_id" class="form-select rounded w-full border-gray-300">
                        <option value="">همه</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @if(request('category_id') == $cat->id) selected @endif>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-sm text-gray-700">برند</label>
                    <select name="brand_id" class="form-select rounded w-full border-gray-300">
                        <option value="">همه</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" @if(request('brand_id') == $brand->id) selected @endif>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-sm text-gray-700">وضعیت موجودی</label>
                    <select class="form-select rounded w-full border-gray-300" name="inventory">
                        <option value="">همه</option>
                        <option value="low" @if(request('inventory') == 'low') selected @endif>موجودی کم</option>
                        <option value="zero" @if(request('inventory') == 'zero') selected @endif>اتمام موجودی</option>
                        <option value="ok" @if(request('inventory') == 'ok') selected @endif>وضعیت مناسب</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded text-sm flex items-center justify-center gap-2"><i class="bi bi-search"></i> جستجو</button>
                </div>
            </form>
        </div>
    </div>

    {{-- لیست محصولات به صورت جدول --}}
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex justify-between items-center mb-4">
            <span class="text-lg font-semibold">لیست محصولات</span>
            <a href="{{ route('products.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">افزودن محصول جدید</a>
        </div>
        <div class="overflow-x-auto">
            <table class="products-table table-auto text-sm shadow-lg rounded-lg overflow-hidden">
                <thead>
                    <tr>
                        <th onclick="filterColumn('name')">نام</th>
                        <th onclick="filterColumn('code')">کد</th>
                        <th onclick="filterColumn('category_id')">دسته‌بندی</th>
                        <th onclick="filterColumn('brand_id')">برند</th>
                        <th onclick="filterColumn('sell_price')">قیمت فروش</th>
                        <th onclick="filterColumn('stock')">موجودی</th>
                        <th onclick="filterColumn('stock_alert')">هشدار موجودی</th>
                        <th onclick="filterColumn('min_order_qty')">حداقل سفارش</th>
                        <th onclick="filterColumn('status')">وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    @php
                        $stock_alert = $product->stock_alert ?? ($product::STOCK_ALERT_DEFAULT ?? 1);
                        $stock_status = $product->stock <= 0
                            ? 'اتمام موجودی'
                            : ($product->stock <= $stock_alert ? 'کم' : 'مناسب');
                    @endphp
                    <tr class="@if($stock_status=='اتمام موجودی') bg-red-50 @elseif($stock_status=='کم') bg-yellow-50 @elseif($stock_status=='مناسب') bg-green-50 @endif">
                        <td>
                            <a href="#" class="text-blue-700 hover:underline" onclick="filterByValue('name', '{{ $product->name }}');return false;">{{ $product->name }}</a>
                        </td>
                        <td>
                            <a href="#" class="text-blue-700 hover:underline" onclick="filterByValue('code', '{{ $product->code }}');return false;">{{ $product->code }}</a>
                        </td>
                        <td>
                            <a href="#" class="text-blue-700 hover:underline" onclick="filterByValue('category_id', '{{ $product->category_id }}');return false;">
                                {{ $product->category?->name ?? '-' }}
                            </a>
                        </td>
                        <td>
                            <a href="#" class="text-blue-700 hover:underline" onclick="filterByValue('brand_id', '{{ $product->brand_id }}');return false;">
                                {{ $product->brand?->name ?? '-' }}
                            </a>
                        </td>
                        <td>
                            <a href="#" class="text-blue-700 hover:underline" onclick="filterByValue('sell_price', '{{ $product->sell_price }}');return false;">
                                {{ number_format($product->sell_price) }}
                            </a>
                        </td>
                        <td>
                            <a href="#" class="text-blue-700 hover:underline" onclick="filterByValue('stock', '{{ $product->stock }}');return false;">
                                {{ $product->stock }}
                            </a>
                        </td>
                        <td>{{ $stock_alert }}</td>
                        <td>{{ $product->min_order_qty }}</td>
                        <td>
                            @if($stock_status=='اتمام موجودی')
                                <span class="badge-status out">اتمام موجودی</span>
                            @elseif($stock_status=='کم')
                                <span class="badge-status low">کم</span>
                            @else
                                <span class="badge-status ok">مناسب</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-actions">
                                <a href="@if(Route::has('products.show')){{ route('products.show', $product->id) }}@else # @endif" class="btn btn-outline-info flex items-center gap-1" title="نمایش">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-warning flex items-center gap-1" title="ویرایش">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('آیا مطمئن هستید؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger flex items-center gap-1" type="submit" title="حذف"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-red-700 py-4">موردی یافت نشد.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-center">
            {{ $products->withQueryString()->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @if(!class_exists(\Livewire\Livewire::class))
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endif
    <script>
        // مرتب سازی یا فیلتر روی جدول با کلیک روی عنوان یا مقدار
        function filterColumn(column) {
            let url = new URL(window.location.href);
            let params = url.searchParams;
            let lastSort = params.get('sort_by');
            let lastDir = params.get('sort_dir');
            let dir = (lastSort === column && lastDir === 'asc') ? 'desc' : 'asc';
            params.set('sort_by', column);
            params.set('sort_dir', dir);
            window.location = url.pathname + '?' + params.toString();
        }
        function filterByValue(column, value) {
            let url = new URL(window.location.href);
            let params = url.searchParams;
            params.set(column, value);
            window.location = url.pathname + '?' + params.toString();
        }
    </script>
@endsection
