@extends('layouts.app')
@section('title', 'افزودن محصول جدید')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/products-create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/persian-datepicker.min.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>
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
        .select2-container--default .select2-selection--single { border-radius: 9px; border:1.5px solid #cfe2ff; min-height: 48px;}
        .select2-selection__rendered { text-align: right; }
        .select2-dropdown { text-align: right; direction: rtl; }
        .select2-quick-list ul { padding:0; margin:0;}
        .select2-quick-list li { margin: 2px 0;}
        .select2-quick-list a { color: #1976d2; font-weight: bold; }
    </style>
@endsection

@section('content')
<div class="container-fluid product-create-page-advanced p-0">
    <div class="row g-0">
        @include('layouts.sidebar')
        <div id="main-content" class="main-content-advanced col-12 col-lg">
            <div class="product-form-outer d-flex justify-content-center align-items-start" style="min-height:100vh;">
                <div class="card shadow-lg mt-4 mb-5 w-100" style="max-width: 900px;">
                    <div class="card-header product-header d-flex align-items-center justify-content-between flex-wrap">
                        <h1 class="product-title fs-4 mb-0"><i class="bi bi-plus-circle-dotted me-2"></i>افزودن محصول جدید</h1>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-right-circle"></i> بازگشت به لیست محصولات</a>
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

                            <div class="row gx-3 gy-3 mb-3 align-items-end flex-wrap">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold"><i class="bi bi-type"></i> نام محصول <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form-control-lg" required value="{{ old('name') }}" autofocus>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold"><i class="bi bi-upc-scan"></i> کد کالا</label>
                                    <div class="input-group">
                                        <input type="text" name="code" id="product-code" class="form-control form-control-lg" value="{{ old('code', $default_code ?? 'products-1001') }}" readonly>
                                        <span class="input-group-text p-0">
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
                                    <select name="category_id" id="category-select2" class="form-select form-select-lg" required>
                                        @if(old('category_id'))
                                            <option value="{{ old('category_id') }}" selected>در حال دریافت...</option>
                                        @endif
                                    </select>
                                    <a href="{{ route('categories.create') }}" target="_blank" class="btn btn-outline-success ms-2 position-absolute" style="top: 41px; left: 0;"><i class="bi bi-plus-circle"></i></a>
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

                            <ul class="nav nav-tabs nav-tabs-rtl mb-4" id="productTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="main-tab" data-bs-toggle="tab" data-bs-target="#mainTabPane" type="button" role="tab" aria-controls="mainTabPane" aria-selected="true">
                                        <i class="bi bi-info-circle"></i> اطلاعات تکمیلی
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#mediaTabPane" type="button" role="tab" aria-controls="mediaTabPane" aria-selected="false">
                                        <i class="bi bi-images"></i> رسانه و فایل‌ها
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="desc-tab" data-bs-toggle="tab" data-bs-target="#descTabPane" type="button" role="tab" aria-controls="descTabPane" aria-selected="false">
                                        <i class="bi bi-file-earmark-text"></i> توضیحات و ویژگی‌ها
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="shareholder-tab" data-bs-toggle="tab" data-bs-target="#shareholderTabPane" type="button" role="tab" aria-controls="shareholderTabPane" aria-selected="false">
                                        <i class="bi bi-people"></i> سهامداران
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content" id="productTabContent">
                                <div class="tab-pane fade show active" id="mainTabPane" role="tabpanel" aria-labelledby="main-tab">
                                    <div class="row gy-3 mb-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">برند</label>
                                            <div class="input-group">
                                                <select name="brand_id" class="form-select select2-simple">
                                                    <option value="">بدون برند</option>
                                                    @foreach($brands as $brand)
                                                        <option value="{{ $brand->id }}" @if(old('brand_id')==$brand->id) selected @endif>{{ $brand->name }}</option>
                                                    @endforeach
                                                </select>
                                                <a href="{{ route('brands.create') }}" target="_blank" class="btn btn-outline-primary">برند جدید</a>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">واحد اندازه‌گیری</label>
                                            <select name="unit" id="selected-unit" class="form-select select2-simple">
                                                <option value="">انتخاب کنید...</option>
                                                @foreach($units as $unit)
                                                    <option value="{{ $unit->title }}" @if(old('unit')==$unit->title) selected @endif>{{ $unit->title }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-outline-info mt-2 w-100" data-bs-toggle="modal" data-bs-target="#unitModal">مدیریت واحدها</button>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">وزن (گرم)</label>
                                            <input type="text" name="weight" class="form-control persian-number" value="{{ old('weight') }}">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">بارکد محصول</label>
                                            <div class="input-group">
                                                <input type="text" name="barcode" id="barcode-field" class="form-control persian-number" value="{{ old('barcode') }}">
                                                <button type="button" class="btn btn-outline-primary" id="generate-barcode-btn" data-target="barcode-field">تولید بارکد استاندارد</button>
                                            </div>
                                            <small class="barcode-status"></small>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">بارکد فروشگاهی</label>
                                            <div class="input-group">
                                                <input type="text" name="store_barcode" id="store-barcode-field" class="form-control persian-number" value="{{ old('store_barcode') }}">
                                                <button type="button" class="btn btn-outline-secondary" id="generate-store-barcode-btn" data-target="store-barcode-field">تولید بارکد فروشگاهی</button>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-check form-switch pt-4">
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
                                                <label class="form-check-label ms-2" for="is_active">فعال باشد</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="mediaTabPane" role="tabpanel" aria-labelledby="media-tab">
                                    <div class="row gy-3 mb-2">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">تصویر شاخص محصول</label>
                                            <input type="file" name="image" class="form-control" accept="image/*">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">ویدیوی معرفی محصول</label>
                                            <input type="file" name="video" class="form-control" accept="video/*">
                                        </div>
                                        <div class="col-12 mt-2">
                                            <label class="form-label">گالری تصاویر</label>
                                            <div id="gallery-dropzone" class="dropzone"></div>
                                            <input type="hidden" name="gallery[]" id="gallery-input">
                                            <small class="text-muted">حداکثر ۵ تصویر، هر تصویر کمتر از ۲ مگابایت.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="descTabPane" role="tabpanel" aria-labelledby="desc-tab">
                                    <div class="row gy-3 mb-2">
                                        <div class="col-12">
                                            <label class="form-label">توضیحات کوتاه</label>
                                            <textarea name="short_desc" class="form-control" rows="2">{{ old('short_desc') }}</textarea>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <label class="form-label">توضیحات کامل</label>
                                            <textarea name="description" class="form-control" rows="6">{{ old('description') }}</textarea>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <label class="form-label">ویژگی‌های محصول</label>
                                            <div id="attributes-area"></div>
                                            <button type="button" class="btn btn-outline-success mt-2" id="add-attribute"><i class="bi bi-plus"></i> افزودن ویژگی</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="shareholderTabPane" role="tabpanel" aria-labelledby="shareholder-tab">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label class="form-label"><b>تخصیص سهم سهامداران برای این محصول</b></label>
                                            <div class="alert alert-light border shadow-sm mb-2">
                                                <small>
                                                    اگر هیچ سهامداری انتخاب نشود، سهم محصول به طور مساوی بین همه سهامداران تقسیم می‌شود.
                                                    اگر فقط یک نفر انتخاب شود، کل محصول برای او خواهد بود.<br>
                                                    اگر چند نفر انتخاب شوند، درصد هرکدام را وارد کنید (مجموع باید ۱۰۰ باشد، اگر خالی بماند، به صورت هوشمند تقسیم می‌شود).
                                                </small>
                                            </div>
                                            @if(isset($shareholders) && count($shareholders))
                                                <div class="row" id="shareholder-list">
                                                    @foreach($shareholders as $shareholder)
                                                        <div class="col-12 mb-2">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        <input type="checkbox"
                                                                            name="shareholder_ids[]"
                                                                            value="{{ $shareholder->id }}"
                                                                            id="sh-{{ $shareholder->id }}"
                                                                            class="shareholder-checkbox"
                                                                            @if(is_array(old('shareholder_ids')) && in_array($shareholder->id, old('shareholder_ids'))) checked @endif
                                                                        >
                                                                    </div>
                                                                </div>
                                                                <input type="number"
                                                                    name="shareholder_percents[{{ $shareholder->id }}]"
                                                                    id="percent-{{ $shareholder->id }}"
                                                                    class="form-control shareholder-percent"
                                                                    min="0" max="100" step="0.01"
                                                                    placeholder="درصد سهم"
                                                                    value="{{ old('shareholder_percents.'.$shareholder->id) }}"
                                                                    @if(!(is_array(old('shareholder_ids')) && in_array($shareholder->id, old('shareholder_ids')))) disabled @endif
                                                                >
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">{{ $shareholder->full_name }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="alert alert-warning mt-2">هیچ سهامداری ثبت نشده است.</div>
                                            @endif
                                            <small class="form-text text-muted" id="percent-warning" style="color:red;display:none"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-success btn-lg px-4"><i class="bi bi-check2-circle"></i> ثبت محصول</button>
                                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg px-4">انصراف</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: مدیریت واحد اندازه‌گیری -->
<div class="modal fade" id="unitModal" tabindex="-1" aria-labelledby="unitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">مدیریت واحدهای اندازه‌گیری</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="units-list">
                    @foreach($units as $unit)
                        <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $unit->id }}">
                            <span class="unit-title">{{ $unit->title }}</span>
                            <div>
                                <button type="button" class="btn btn-sm btn-primary edit-unit-btn me-1">ویرایش</button>
                                <button type="button" class="btn btn-sm btn-danger delete-unit-btn">حذف</button>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <hr>
                <form id="add-unit-form" class="d-flex gap-2 mt-2">
                    <input type="text" class="form-control" id="unit-title" placeholder="واحد جدید">
                    <button type="submit" class="btn btn-success">افزودن واحد</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/persian-date.min.js') }}"></script>
    <script src="{{ asset('js/persian-datepicker.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone-min.js"></script>
    <script src="{{ asset('js/products-create-advanced.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // دسته‌بندی با select2 + ایجکس (جایگزین پیاده‌سازی دستی)
        $('#category-select2').select2({
    theme: 'bootstrap4',
    dir: 'rtl',
    language: 'fa',
    placeholder: 'انتخاب دسته‌بندی...',
    allowClear: true,
    minimumInputLength: 0,
    ajax: {
        url: '/api/categories/product-list',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return { q: params.term || '', limit: 5 };
        },
        processResults: function (data) {
            return {
                results: data.items.map(item => ({
                    id: item.id,
                    text: item.name
                }))
            };
        },
        cache: true
    }
});
        // مقدار old لود شود
        @if(old('category_id'))
        $.ajax({
            url: '/api/categories/product-list',
            data: { q: '', limit: 100 },
            dataType: 'json'
        }).done(function(data) {
            let found = data.items.find(cat => cat.id == "{{ old('category_id') }}");
            if(!found) {
                // اگر در 5 تای آخر نبود، به صورت دستی اضافه کن
                $('#category-select2').append(
                    $('<option>', {
                        value: "{{ old('category_id') }}",
                        text: 'دسته‌بندی انتخاب شده (بارگیری نام...)',
                        selected: true
                    })
                );
                // درخواست جدا برای دریافت نام دسته‌بندی
                $.get('/api/categories', { id: "{{ old('category_id') }}" }, function(res){
                    let cat = (Array.isArray(res) ? res : []).find(c => c.id == "{{ old('category_id') }}");
                    if(cat) $('#category-select2 > option[value="{{ old('category_id') }}"]').text(cat.name);
                });
            }
        });
        @endif

        // سایر select2 ساده برای برند/واحد
        $('.select2-simple').select2({ dir: 'rtl', width: '100%' });
    </script>
@endsection
