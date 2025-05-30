@extends('layouts.app')
@section('title', 'نمایش محصول')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .product-header {
            background: linear-gradient(90deg,#2563eb 60%,#06b6d4 100%);
            color: #fff;
            border-radius: 14px 14px 0 0;
        }
        .info-list li {
            border: none;
            border-bottom: 1px solid #e0e7ef;
            padding: 0.7em 0.5em;
            font-size: 1.07em;
        }
        .info-list li:last-child { border-bottom: none; }
        .gallery-img { height:100px; object-fit:cover; border-radius:8px; box-shadow:0 2px 10px #5552; }
        .tree-list { list-style: none; margin: 0 0 0 1.5em; padding: 0; }
        .tree-list ul { margin-right: 2em; }
        .tree-list b { color: #0ea5e9; }
        .product-barcode { font-family: monospace; letter-spacing: 2px; }
        .product-status { font-size:1.1em; font-weight:bold; }
        .product-status.active { color:#059669 }
        .product-status.inactive { color:#ef4444 }
        .meta-label { min-width: 110px; display: inline-block;}
        .product-video { border-radius: 12px; border: 1px solid #ddd; }
        .product-actions a { min-width: 120px;}
    </style>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow-lg">
        <div class="card-header product-header d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-box2-heart fs-3 me-2"></i>
                <span class="fs-4 fw-bold">{{ $product->name }}</span>
                <span class="badge bg-secondary mx-2">{{ $product->code }}</span>
            </div>
            <div>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning me-2"><i class="bi bi-pencil"></i> ویرایش</a>
                <a href="{{ route('products.index') }}" class="btn btn-light"><i class="bi bi-arrow-right-square"></i> بازگشت به لیست</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <!-- تصویر شاخص و ویدیو -->
                <div class="col-12 col-md-4 text-center mb-3">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" alt="تصویر محصول" class="img-fluid rounded shadow mb-3" style="max-height:220px;">
                    @else
                        <div class="alert alert-secondary">تصویر ندارد</div>
                    @endif
                    @if($product->video)
                        <video src="{{ asset('storage/'.$product->video) }}" controls class="product-video mt-3" style="width:100%; max-width:320px;"></video>
                    @endif
                    @if($product->barcode)
                        <div class="mt-3">
                            <span class="meta-label"><i class="bi bi-upc-scan"></i> بارکد:</span>
                            <span class="product-barcode">{{ $product->barcode }}</span>
                        </div>
                    @endif
                    @if($product->store_barcode)
                        <div class="mt-1">
                            <span class="meta-label"><i class="bi bi-upc"></i> بارکد فروشگاهی:</span>
                            <span class="product-barcode">{{ $product->store_barcode }}</span>
                        </div>
                    @endif
                </div>
                <!-- اطلاعات کلی -->
                <div class="col-12 col-md-8">
                    <ul class="list-group mb-3 info-list">
                        <li><span class="meta-label">نام:</span> {{ $product->name }}</li>
                        <li><span class="meta-label">کد:</span> {{ $product->code }}</li>
                        <li><span class="meta-label">دسته‌بندی:</span> {{ $product->category?->name ?? '-' }}</li>
                        <li><span class="meta-label">برند:</span> {{ $product->brand?->name ?? '-' }}</li>
                        <li><span class="meta-label">قیمت خرید:</span> {{ number_format($product->buy_price) }} <span class="text-muted">تومان</span></li>
                        <li><span class="meta-label">قیمت فروش:</span> {{ number_format($product->sell_price) }} <span class="text-muted">تومان</span></li>
                        <li><span class="meta-label">تخفیف:</span> {{ $product->discount }}%</li>
                        <li><span class="meta-label">موجودی:</span>
                            {{ $product->stock }}
                            @php
                                $stock_alert = $product->stock_alert ?? (\App\Models\Product::STOCK_ALERT_DEFAULT ?? 1);
                            @endphp
                            @if($product->stock <= 0)
                                <span class="badge bg-danger">اتمام موجودی</span>
                            @elseif($product->stock <= $stock_alert)
                                <span class="badge bg-warning text-dark">کم</span>
                            @else
                                <span class="badge bg-success">مناسب</span>
                            @endif
                        </li>
                        <li><span class="meta-label">هشدار موجودی:</span> {{ $stock_alert }}</li>
                        <li><span class="meta-label">حداقل سفارش:</span> {{ $product->min_order_qty ?? '-' }}</li>
                        <li><span class="meta-label">واحد:</span> {{ $product->unit ?? '-' }}</li>
                        <li><span class="meta-label">وزن:</span> {{ $product->weight ?? '-' }} گرم</li>
                        <li><span class="meta-label">فعال:</span>
                            @if($product->is_active)
                                <span class="product-status active"><i class="bi bi-check-circle"></i> فعال</span>
                            @else
                                <span class="product-status inactive"><i class="bi bi-x-circle"></i> غیرفعال</span>
                            @endif
                        </li>
                        @if($product->expire_date)
                        <li><span class="meta-label">تاریخ انقضا:</span> {{ $product->expire_date }}</li>
                        @endif
                        @if($product->added_at)
                        <li><span class="meta-label">تاریخ ثبت:</span> {{ $product->added_at }}</li>
                        @endif
                        <li><span class="meta-label">تاریخ ایجاد:</span> {{ jdate($product->created_at)->format('Y/m/d H:i') }}</li>
                        <li><span class="meta-label">تاریخ ویرایش:</span> {{ jdate($product->updated_at)->format('Y/m/d H:i') }}</li>
                    </ul>

                    <!-- دکمه‌های عملیات -->
                    <div class="product-actions d-flex flex-wrap gap-2 mt-2">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-warning"><i class="bi bi-pencil"></i> ویرایش محصول</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('آیا مطمئن هستید؟')" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger"><i class="bi bi-trash"></i> حذف محصول</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- توضیحات و ویژگی‌ها -->
            <div class="row mt-4">
                <div class="col-12 col-lg-6">
                    <div class="mb-3">
                        <b>توضیحات کوتاه:</b>
                        <div class="border rounded p-2">{{ $product->short_desc ?? '-' }}</div>
                    </div>
                    <div>
                        <b>توضیحات کامل:</b>
                        <div class="border rounded p-2" style="min-height:90px">{!! nl2br(e($product->description)) !!}</div>
                    </div>
                </div>
                <!-- ویژگی‌ها -->
                <div class="col-12 col-lg-6">
                    @if($product->attributes && is_array($product->attributes))
                        <div class="mb-3">
                            <b>ویژگی‌های محصول:</b>
                            <ul class="tree-list mt-2">
                                @foreach($product->attributes as $attr)
                                    <li>
                                        <b>{{ $attr['key'] ?? '' }}</b>
                                        @if(isset($attr['value']))
                                            : <span>{{ $attr['value'] }}</span>
                                        @endif
                                        @if(isset($attr['children']))
                                            <ul>
                                                @foreach($attr['children'] as $child)
                                                    <li>{{ $child }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <!-- نمایش گالری تصاویر -->
            @php
                $gallery = $product->gallery;
                if(is_string($gallery)) $gallery = json_decode($gallery,true);
            @endphp
            @if($gallery && is_array($gallery) && count($gallery))
                <div class="mt-4">
                    <h5><i class="bi bi-images"></i> گالری تصاویر</h5>
                    <div class="row">
                        @foreach($gallery as $galleryImg)
                            <div class="col-6 col-md-2 mb-2">
                                <img src="{{ asset('storage/'.$galleryImg) }}" class="gallery-img w-100">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- سهامداران (در صورت وجود) -->
            @if(isset($product->shareholders) && is_array($product->shareholders) && count($product->shareholders))
                <div class="mt-4">
                    <h5><i class="bi bi-people"></i> سهامداران محصول</h5>
                    <ul>
                        @foreach($product->shareholders as $shareholder)
                            <li>
                                {{ $shareholder['full_name'] ?? $shareholder['name'] ?? '-' }}
                                @if(isset($shareholder['percent']))
                                    - <span class="text-info">{{ $shareholder['percent'] }}%</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        // هر اسکریپت خاص برای نمایش محصول
    </script>
@endsection
