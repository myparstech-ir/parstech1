@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/persian-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/persianDatepicker-melon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sales-invoice.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sales-create.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* بهبود ظاهر و مدرن‌سازی */
        .sales-create-container {
            background: linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%);
            border-radius: 20px;
            box-shadow: 0 4px 32px #0001;
            padding: 34px 22px 10px 22px;
            margin: 32px auto 20px auto;
            max-width: 1200px;
        }
        .sales-create-header {
            background: linear-gradient(90deg,#2563eb 25%,#22d3ee 100%);
            border-radius: 18px;
            color: #fff;
            padding: 18px 32px;
            margin-bottom: 24px;
            box-shadow: 0 3px 18px #0ea5e94d;
            display: flex; align-items: center; gap: 16px;
        }
        .sales-create-header h2 {
            font-size: 2rem; font-weight: bold; margin: 0;
        }
        .invoice-section {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 1px 14px #0001;
            padding: 20px 22px 12px 22px;
            margin-bottom: 18px;
            transition: box-shadow .2s;
        }
        .form-label.required:after {content:" *";color:#ef4444;font-weight:bold;}
        .grand-total { font-size: 1.7rem; color: #0ea5e9; font-weight: bold; }
        .selected-products-table thead th {
            background: #e0f2fe;
            color: #1e293b;
        }
        .selected-products-table tbody tr {
            transition: background .17s;
        }
        .selected-products-table tbody tr:hover {
            background: #f1f5f9;
        }
        .remove-btn { color: #ef4444; font-size: 1.7rem; cursor: pointer; }
        .invoice-totals {
            display: flex;
            gap: 40px;
            align-items: center;
            font-size: 1.15rem;
        }
        .total-label { color: #64748b; }
        .total-value { color: #0ea5e9; font-weight: bold; }
        .product-search-results {
            max-height: 300px;
            overflow-y: auto;
            position: absolute;
            z-index: 10;
            width: 100%;
            left: 0; right: 0;
            background: #fff;
            border-radius: 10px;
            border: 1px solid #bae6fd;
            box-shadow: 0 5px 18px #0ea5e94d;
        }
        .product-item {
            cursor: pointer;
            padding: 10px 18px;
            border-bottom: 1px solid #e0e0e0;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .product-item:last-child { border-bottom: none; }
        .product-item:hover { background: #f0fdfa; }
        .animate-fade-in {animation: fadeIn .7s;}
        @keyframes fadeIn {from{opacity:0;transform:translateY(40px);} to{opacity:1;transform:none;}}
    </style>
@endsection

@section('content')
<div class="sales-create-container">
    <div class="sales-create-header animate-fade-in">
        <h2><i class="fa fa-file-invoice-dollar"></i> فاکتور فروش جدید</h2>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({icon: 'success', title: 'موفقیت', text: '{{ session("success") }}', timer: 3000, showConfirmButton: false});
            });
        </script>
        <div class="alert alert-success animate-fade-in d-none">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    confirmButtonText: 'بستن'
                });
            });
        </script>
        <div class="alert alert-danger animate-fade-in d-none">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="sales-invoice-form" class="animate-fade-in" autocomplete="off" method="POST" action="{{ route('sales.store') }}">
        @csrf

        <!-- اطلاعات اولیه فاکتور -->
        <div class="invoice-section">
            <div class="row g-3">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label required">شماره فاکتور</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="invoice_number" id="invoice_number"
                                   value="{{ old('invoice_number', $nextNumber ?? '') }}" readonly required>
                            <span class="input-group-text">
                                <label class="form-switch mb-0">
                                    <input type="checkbox" id="invoiceNumberSwitch" checked>
                                    <span class="slider"></span>
                                </label>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">شماره ارجاع</label>
                        <input type="text" class="form-control" name="reference" id="reference"
                               value="{{ old('reference') }}" placeholder="شماره ارجاع...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label required">تاریخ صدور</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="issued_at_jalali" id="issued_at_jalali"
                                   value="{{ old('issued_at_jalali') }}" readonly>
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label required">واحد پول</label>
                        <select class="form-select" name="currency_id" id="currency_id" required>
                            <option value="">انتخاب کنید...</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                                    {{ $currency->name }} - {{ $currency->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- اطلاعات مشتری و فروشنده -->
        <div class="invoice-section mt-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="form-group position-relative">
                        <label class="form-label required">مشتری</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="customer_search"
                                   placeholder="جستجوی مشتری..." value="{{ old('customer_name') }}">
                            <input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id') }}" required>
                            <button type="button" class="btn btn-success" id="addCustomerBtn">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                        <div id="customer-search-results" class="dropdown-menu w-100"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">عنوان فاکتور</label>
                        <input type="text" class="form-control" name="title" id="invoice_title"
                               placeholder="عنوان فاکتور..." value="{{ old('title') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label required">فروشنده</label>
                        <select class="form-select" name="seller_id" id="seller_id" required>
                            <option value="">انتخاب کنید...</option>
                            @foreach($sellers as $seller)
                                <option value="{{ $seller->id }}" {{ old('seller_id') == $seller->id ? 'selected' : '' }}>
                                    {{ $seller->seller_code }} - {{ $seller->first_name }} {{ $seller->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- محصولات و خدمات -->
        <div class="invoice-section mt-4">
            @include('sales.partials.product_list')
        </div>

        <!-- جدول اقلام فاکتور -->
        <div class="invoice-section mt-4">
            @include('sales.partials.invoice_items_table')
        </div>

        <input type="hidden" name="products_input" id="products_input" value="{{ old('products_input') }}">

        <!-- جمع کل و دکمه ثبت -->
        <div class="invoice-footer mt-4">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <div class="invoice-totals">
                        <div class="total-item">
                            <div class="total-label">تعداد کل:</div>
                            <div class="total-value" id="total_count">۰</div>
                        </div>
                        <div class="total-item">
                            <div class="total-label">مبلغ کل:</div>
                            <div class="total-value grand-total" id="total_amount">۰ ریال</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-check"></i>
                        ثبت فاکتور فروش
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/persian-date.min.js') }}"></script>
    <script src="{{ asset('js/persian-datepicker.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/sales-invoice.js') }}"></script>
    <script>
    $(function() {
        // تاریخ فاکتور
        if (typeof persianDate !== "undefined") {
            var now = new persianDate();
            var jalali = now.format('YYYY/MM/DD');
            $('#issued_at_jalali').val(jalali);
        } else {
            var date = new Date();
            var pad = n => n < 10 ? '0'+n : n;
            var miladi = date.getFullYear() + '-' + pad(date.getMonth()+1) + '-' + pad(date.getDate());
            $('#issued_at_jalali').val(miladi);
        }
        $('#issued_at_jalali').prop('readonly', true).css('background', '#eee').css('cursor', 'not-allowed');

        // نمونه ایجکس جستجوی مشتری (اگر در js اصلیت نیست)
        $('#customer_search').on('input', function() {
            let val = $(this).val();
            if(val.length < 2) { $('#customer-search-results').hide(); return; }
            $.get('/customers/ajax-list', {q: val, limit: 10}, function(data) {
                let html = '';
                if(data.length) {
                    data.forEach(function(c) {
                        html += `<div class="dropdown-item" data-id="${c.id}">${c.first_name} ${c.last_name} <small class="text-muted">${c.mobile}</small></div>`;
                    });
                    $('#customer-search-results').html(html).show();
                } else {
                    $('#customer-search-results').hide();
                }
            });
        });
        $(document).on('click', '#customer-search-results .dropdown-item', function() {
            $('#customer_id').val($(this).data('id'));
            $('#customer_search').val($(this).text());
            $('#customer-search-results').hide();
        });
        $(document).on('click', function(e) {
            if(!$(e.target).closest('#customer_search').length) $('#customer-search-results').hide();
        });
    });
    </script>
@endsection
