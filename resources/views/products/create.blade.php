@extends('layouts.app')
@section('title', 'افزودن محصول جدید')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/products-create.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@babakhani/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css"/>
    <style>
        #main-content {
            margin-right: 138px;
            padding: 15px;
            transition: all .3s;
        }
        @media (max-width: 991px) {
            #main-content { margin-right: 0 !important; padding: 6px !important; }
        }
        .swal2-popup { font-family: Vazirmatn, Tahoma, Arial, sans-serif !important; }
        .cat-dropdown-container {
            position: relative;
            width: 100%;
        }
        .cat-dropdown-selected {
            min-height: 48px;
            border-radius: 9px;
            border:1.5px solid #cfe2ff;
            padding: 10px 38px 10px 38px;
            background: #fafbfc;
            font-size: 1.09rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .cat-dropdown-selected:after {
            content: "\f282";
            font-family: bootstrap-icons;
            font-size: 1.1rem;
            color: #1976d2;
            margin-right: 8px;
        }
        .cat-dropdown-list {
            display: none;
            position: absolute;
            right: 0; left: 0;
            background: #fff;
            border: 1.5px solid #cfe2ff;
            border-radius: 0 0 9px 9px;
            z-index: 999;
            box-shadow: 0 10px 30px #2563eb14;
        }
        .cat-dropdown-list.active {
            display: block;
        }
        .cat-dropdown-search {
            border: none;
            border-bottom: 1.2px solid #cfe2ff;
            width: 100%;
            padding: 8px 12px;
            font-size: 1.03rem;
            outline: none;
            direction: rtl;
            background: #f5f9ff;
        }
        .cat-dropdown-item {
            padding: 9px 18px;
            cursor: pointer;
            font-size: 1.07rem;
            color: #1d3557;
            transition: background 0.15s;
            text-align: right;
        }
        .cat-dropdown-item:hover,
        .cat-dropdown-item.selected {
            background: #e5f4ff;
            color: #1976d2;
        }
        .cat-dropdown-empty {
            padding: 10px;
            color: #888;
            font-size: 1rem;
        }
        .cat-dropdown-add-btn {
            position: absolute;
            left: 9px;
            top: 7px;
            z-index: 1000;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid product-create-page-advanced p-0">
    <div class="row g-0">
        @include('layouts.sidebar')
        <div id="main-content" class="main-content-advanced col-12 col-lg">
            <div class="product-form-outer d-flex justify-content-center align-items-start" style="min-height:100vh;">
                <div class="card shadow-lg mt-4 mb-5 w-100" style="max-width: 800px;">
                    <div class="card-header product-header">
                        <h1 class="product-title"><i class="bi bi-plus-circle-dotted me-2"></i>افزودن محصول جدید</h1>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="product-form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                            @csrf

                            <div class="row gx-3 gy-3 mb-4 align-items-end flex-wrap">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold"><i class="bi bi-type"></i> نام محصول <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form-control-lg" required value="{{ old('name') }}" autofocus>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold"><i class="bi bi-upc-scan"></i> کد کالا</label>
                                    <div class="input-group">
                                        <input type="text" name="code" id="product-code" class="form-control form-control-lg" value="{{ old('code', $default_code ?? 'products-1001') }}" readonly>
                                        <span class="input-group-text">
                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" id="code-edit-switch">
                                            </div>
                                        </span>
                                    </div>
                                    <small class="text-muted">برای محصول سفارشی، سوییچ را فعال کن تا امکان ویرایش کد فراهم شود.</small>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold"><i class="bi bi-cash-stack"></i> قیمت خرید</label>
                                    <input type="text" inputmode="decimal" step="0.01" name="buy_price" class="form-control form-control-lg persian-number" required value="{{ old('buy_price') }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold"><i class="bi bi-currency-dollar"></i> قیمت فروش</label>
                                    <input type="text" inputmode="decimal" step="0.01" name="sell_price" class="form-control form-control-lg persian-number" required value="{{ old('sell_price') }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold"><i class="bi bi-percent"></i> تخفیف (%)</label>
                                    <input type="text" step="0.01" name="discount" class="form-control form-control-lg persian-number" value="{{ old('discount') }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold"><i class="bi bi-box"></i> موجودی اولیه</label>
                                    <input type="text" step="0.01" name="stock" class="form-control form-control-lg persian-number" value="{{ old('stock', 0) }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold"><i class="bi bi-exclamation-triangle"></i> حداقل هشدار</label>
                                    <input type="text" name="min_stock" class="form-control form-control-lg persian-number" value="{{ old('min_stock', 0) }}">
                                </div>
                                <div class="col-12 col-md-6 position-relative">
                                    <label class="form-label fw-bold"><i class="bi bi-list-task"></i> دسته‌بندی <span class="text-danger">*</span></label>
                                    <div class="cat-dropdown-container" id="cat-dropdown-container">
                                        <input type="hidden" name="category_id" id="category_id_real" value="{{ old('category_id') }}">
                                        <div class="cat-dropdown-selected" id="cat-dropdown-selected">انتخاب کنید...</div>
                                        <div class="cat-dropdown-list" id="cat-dropdown-list">
                                            <input type="text" class="cat-dropdown-search" id="cat-dropdown-search" placeholder="جستجو...">
                                            <div id="cat-dropdown-items"></div>
                                        </div>
                                        <a href="{{ route('categories.create') }}" target="_blank" class="btn btn-outline-success cat-dropdown-add-btn"><i class="bi bi-plus-circle"></i></a>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold"><i class="bi bi-123"></i> کد حسابداری</label>
                                    <input type="text" name="accounting_code" class="form-control form-control-lg" value="{{ old('accounting_code') }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold"><i class="bi bi-calendar3"></i> تاریخ انقضا</label>
                                    <input type="text" name="expire_date" id="expire_date_picker" class="form-control form-control-lg" value="{{ old('expire_date') }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold"><i class="bi bi-cart-plus"></i> حداقل مقدار سفارش</label>
                                    <input type="text" name="min_order_qty" class="form-control form-control-lg persian-number" value="{{ old('min_order_qty') }}">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold"><i class="bi bi-tools"></i> توضیحات فنی (برای کاتالوگ/فروشگاه)</label>
                                    <textarea name="tech_desc" class="form-control form-control-lg" rows="2">{{ old('tech_desc') }}</textarea>
                                </div>
                            </div>
                            ... <!-- بقیه فرم و تب‌ها مثل قبل -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone-min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@babakhani/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@babakhani/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    <script src="{{ asset('js/products-create-advanced.js') }}"></script>
@endsection
