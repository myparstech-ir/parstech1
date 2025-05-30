@extends('layouts.app')
@section('title', 'افزودن محصول جدید')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/products-create.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone.css" />
    <link rel="stylesheet" href="{{ asset('css/persian-datepicker.min.css') }}">
@endsection

@section('content')
<div class="product-form-outer py-4">
    <div class="card shadow-lg">
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
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-type"></i> نام محصول <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name') }}" autofocus>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-upc-scan"></i> کد کالا</label>
                        <div class="input-group">
                            <input type="text" name="code" id="product-code" class="form-control"
                                   value="{{ old('code', $default_code ?? 'product-1001') }}"
                                   readonly data-default="{{ $default_code ?? 'product-1001' }}">
                            <span class="input-group-text p-0 px-1">
                                <input class="form-check-input m-0" type="checkbox" id="code-edit-switch">
                            </span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-cash-stack"></i> قیمت خرید</label>
                        <div class="input-group">
                            <input type="text" name="buy_price" class="form-control persian-number" required value="{{ old('buy_price') }}">
                            <span class="input-group-text">تومان</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-currency-dollar"></i> قیمت فروش</label>
                        <div class="input-group">
                            <input type="text" name="sell_price" class="form-control persian-number" required value="{{ old('sell_price') }}">
                            <span class="input-group-text">تومان</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-percent"></i> تخفیف (%)</label>
                        <input type="text" name="discount" class="form-control persian-number" value="{{ old('discount') }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-box"></i> موجودی اولیه</label>
                        <input type="text" name="stock" class="form-control persian-number" value="{{ old('stock', 1) }}">
                    </div>
                    {{-- هشدار موجودی --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-exclamation-triangle"></i> هشدار موجودی</label>
                        <input type="text" name="stock_alert" class="form-control persian-number" value="{{ old('stock_alert', 1) }}">
                        <small class="text-muted">در صورت رسیدن موجودی به این عدد، هشدار نمایش داده می‌شود.</small>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-cart-plus"></i> حداقل سفارش</label>
                        <input type="text" name="min_order_qty" class="form-control persian-number" value="{{ old('min_order_qty', 1) }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-list-task"></i> دسته‌بندی <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">انتخاب کنید...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @if(old('category_id')==$cat->id) selected @endif>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-calendar3"></i> تاریخ انقضا (اختیاری)</label>
                        <input type="text" name="expire_date" id="expire_date_picker" class="form-control" value="{{ old('expire_date') }}" autocomplete="off">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-calendar3"></i> تاریخ افزودن محصول</label>
                        <input type="text" name="added_at" id="added_at_picker" class="form-control" value="{{ old('added_at', '') }}" autocomplete="off">
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-check form-switch pt-4">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label ms-2" for="is_active">فعال باشد</label>
                        </div>
                    </div>
                </div>

                <ul class="nav nav-tabs nav-tabs-rtl mb-4 mt-4" id="productTab" role="tablist">
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
                                    <select name="brand_id" class="form-select" id="brand-select">
                                        <option value="">بدون برند</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" @if(old('brand_id')==$brand->id) selected @endif>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#brandModal" id="showBrandModal">برند جدید</button>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">واحد اندازه‌گیری</label>
                                <select name="unit" id="selected-unit" class="form-select">
                                    <option value="عدد" @if(old('unit', 'عدد') == 'عدد') selected @endif>عدد</option>
                                    @foreach($units as $unit)
                                        @if($unit->title != 'عدد')
                                            <option value="{{ $unit->title }}" @if(old('unit')==$unit->title) selected @endif>{{ $unit->title }}</option>
                                        @endif
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
                                    <button type="button" class="btn btn-outline-primary" id="generate-barcode-btn" data-target="barcode-field">ساخت بارکد</button>
                                </div>
                                <span class="barcode-status"></span>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">بارکد فروشگاهی</label>
                                <div class="input-group">
                                    <input type="text" name="store_barcode" id="store-barcode-field" class="form-control persian-number" value="{{ old('store_barcode') }}">
                                    <button type="button" class="btn btn-outline-secondary" id="generate-store-barcode-btn" data-target="store-barcode-field">ساخت بارکد فروشگاه</button>
                                </div>
                                <span class="barcode-status"></span>
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
                                        اگر هیچ سهامداری انتخاب نشود، سهم محصول به طور مساوی بین همه سهامداران تقسیم می‌شود.<br>
                                        اگر فقط یک نفر انتخاب شود، کل محصول برای او خواهد بود.<br>
                                        اگر چند نفر انتخاب شوند، درصد هرکدام را وارد کنید (مجموع باید ۱۰۰ باشد، اگر خالی بگذارید به طور مساوی تقسیم می‌شود).
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
                                                            >
                                                        </div>
                                                    </div>
                                                    <input type="text"
                                                        name="shareholder_percents[{{ $shareholder->id }}]"
                                                        id="percent-{{ $shareholder->id }}"
                                                        class="form-control shareholder-percent persian-number"
                                                        min="0" max="100" step="0.01"
                                                        placeholder="درصد سهم"
                                                        disabled
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
@include('products.units-modal')
@include('products.brand-modal')
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/persian-date.min.js') }}"></script>
    <script src="{{ asset('js/persian-datepicker.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone-min.js"></script>
    <script src="{{ asset('js/products-create-advanced.js') }}"></script>
    <script>
        $(function() {
            // تاریخ شمسی
            $('#expire_date_picker').persianDatepicker({
                format: 'YYYY/MM/DD',
                autoClose: true,
                initialValue: false
            });
            let today = new persianDate().format('YYYY/MM/DD');
            let $addedAt = $('#added_at_picker');
            if(!$addedAt.val()) $addedAt.val(today);
            $addedAt.persianDatepicker({
                format: 'YYYY/MM/DD',
                autoClose: true,
                initialValue: false
            });
            // کنترل سوییچ کد کالا
            const codeSwitch = document.getElementById('code-edit-switch');
            const codeInput = document.getElementById('product-code');
            const codeDefault = codeInput ? codeInput.getAttribute('data-default') : 'product-1001';
            if (codeSwitch && codeInput) {
                codeSwitch.addEventListener('change', function () {
                    codeInput.readOnly = !this.checked;
                    if(this.checked){
                        codeInput.value = '';
                        codeInput.focus();
                    } else {
                        codeInput.value = codeDefault;
                    }
                });
                codeInput.readOnly = true;
                codeInput.value = codeDefault;
            }
        });
    </script>
@endsection
