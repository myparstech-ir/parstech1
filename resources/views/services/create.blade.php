@extends('layouts.app')

@section('title', 'افزودن خدمت جدید')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/service-create.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .tab-pane label { font-weight: 500; }
        .required:after { content: '*'; color: #d90429; font-weight: bold; margin-right: 2px;}
        .list-group .input-group { flex-wrap: nowrap; }
        .remove-btn { min-width: 35px; }
        .btn-add-new { min-width: 120px; }
        .img-preview { max-width: 120px; border-radius: 8px; margin-top: 10px; }
        .nav-tabs .nav-link.active { background: #0d6efd; color: #fff !important;}
        .nav-tabs .nav-link { color: #0d6efd; }
        .print-area { background: #f8fafc; border-radius: 10px; border: 1px dashed #0d6efd; padding: 20px; margin-bottom: 15px; }
        .print-btn { float: left; }
        @media print {
            body * { visibility: hidden; }
            .print-area, .print-area * { visibility: visible; }
            .print-area { position: absolute; left: 0; top: 0; width: 100vw; background: #fff !important; box-shadow: none !important; }
            .print-btn { display: none !important; }
        }
    </style>
@endsection

@section('content')
<div class="container py-4">
    <div class="card shadow-lg">
        <div class="card-header bg-info text-white">
            <h1 class="mb-0"><i class="bi bi-plus-circle-dotted me-2"></i>افزودن خدمت جدید</h1>
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
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form id="service-form" action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf

                <ul class="nav nav-tabs nav-tabs-rtl mb-4" id="serviceTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="main-tab" data-bs-toggle="tab" data-bs-target="#mainTabPane" type="button" role="tab" aria-controls="mainTabPane" aria-selected="true">
                            <i class="bi bi-info-circle"></i> اطلاعات پایه
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#mediaTabPane" type="button" role="tab" aria-controls="mediaTabPane" aria-selected="false">
                            <i class="bi bi-images"></i> رسانه
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
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="allitems-tab" data-bs-toggle="tab" data-bs-target="#allitemsTabPane" type="button" role="tab" aria-controls="allitemsTabPane" aria-selected="false">
                            <i class="bi bi-list-check"></i> لیست پرینتی اقلام و موارد خدمت
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="serviceTabContent">
                    {{-- اطلاعات پایه --}}
                    <div class="tab-pane fade show active" id="mainTabPane" role="tabpanel" aria-labelledby="main-tab">
                        <div class="row g-3 mb-1">
                            <div class="col-12 col-md-6">
                                <label class="form-label required">عنوان خدمت</label>
                                <input type="text" name="title" class="form-control" required autofocus value="{{ old('title') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label required">کد خدمت</label>
                                <div class="input-group">
                                    <input type="text" name="service_code" id="service_code" class="form-control" required value="{{ old('service_code', $nextCode ?? '') }}">
                                    <button type="button" class="btn btn-outline-secondary" id="custom_code_switch">کد دلخواه</button>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    اگر می‌خواهید کد خاصی وارد کنید دکمه بالا را بزنید (در غیر این صورت کد خودکار تولید می‌شود)
                                </small>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">دسته‌بندی خدمت</label>
                                <select name="service_category_id" class="form-select">
                                    <option value="">انتخاب کنید</option>
                                    @foreach($serviceCategories ?? [] as $cat)
                                        <option value="{{ $cat->id }}" {{ old('service_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label required">مبلغ خدمت (تومان)</label>
                                <input type="number" name="price" class="form-control" min="0" step="100" required value="{{ old('price') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label required">واحد محاسبه خدمت</label>
                                <div class="input-group">
                                    <select name="unit_id" class="form-select" required>
                                        <option value="">انتخاب واحد</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->title }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-primary" id="add-unit-btn">افزودن واحد جدید</button>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-check form-switch pt-4">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2" for="is_active">فعال باشد</label>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-12 col-md-6">
                                <label class="form-label">اطلاعات خدمات (اختیاری)</label>
                                <input type="text" name="service_info" class="form-control" value="{{ old('service_info') }}">
                            </div>
                        </div>
                    </div>
                    {{-- رسانه --}}
                    <div class="tab-pane fade" id="mediaTabPane" role="tabpanel" aria-labelledby="media-tab">
                        <div class="row gy-3 mb-2">
                            <div class="col-12 col-md-6">
                                <label class="form-label">تصویر شاخص خدمت</label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                <img id="image_preview" src="#" alt="پیش نمایش" style="display:none;" class="img-preview">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">گالری تصاویر</label>
                                <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                                <small class="text-muted">حداکثر ۵ تصویر، هر تصویر کمتر از ۲ مگابایت</small>
                            </div>
                        </div>
                    </div>
                    {{-- توضیحات و ویژگی‌ها --}}
                    <div class="tab-pane fade" id="descTabPane" role="tabpanel" aria-labelledby="desc-tab">
                        <div class="row gy-3 mb-2">
                            <div class="col-12">
                                <label class="form-label">توضیح کوتاه</label>
                                <input type="text" name="short_description" class="form-control" maxlength="255" value="{{ old('short_description') }}">
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label">توضیحات کامل</label>
                                <textarea name="full_description" class="form-control" rows="6">{{ old('full_description') }}</textarea>
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label">اقلام مورد نیاز برای انجام خدمت</label>
                                <ul id="needed-items-list" class="list-group mb-2">
                                    @if(old('needed_items'))
                                        @foreach(old('needed_items') as $need)
                                            <li class="list-group-item d-flex align-items-center">
                                                <input type="text" name="needed_items[]" class="form-control me-2" value="{{ $need }}">
                                                <button type="button" class="btn btn-danger remove-btn" onclick="removeItem(this)"><i class="bi bi-x-lg"></i></button>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                                <div class="input-group">
                                    <input type="text" id="add-needed-item-input" class="form-control" placeholder="مثال: تاریخ ازدواج، شناسنامه، کارت ملی ...">
                                    <button type="button" class="btn btn-outline-success btn-add-new" id="add-needed-item-btn"><i class="bi bi-plus"></i> افزودن مورد</button>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label">نقاط ضعف / محدودیت‌های خدمت</label>
                                <ul id="weak-points-list" class="list-group mb-2">
                                    @if(old('weak_points'))
                                        @foreach(old('weak_points') as $weak)
                                            <li class="list-group-item d-flex align-items-center">
                                                <input type="text" name="weak_points[]" class="form-control me-2" value="{{ $weak }}">
                                                <button type="button" class="btn btn-danger remove-btn" onclick="removeItem(this)"><i class="bi bi-x-lg"></i></button>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                                <div class="input-group">
                                    <input type="text" id="add-weak-point-input" class="form-control" placeholder="مثال: نیاز به قطعه خاص، عدم تضمین دائمی و ...">
                                    <button type="button" class="btn btn-outline-warning btn-add-new" id="add-weak-point-btn"><i class="bi bi-plus"></i> افزودن محدودیت</button>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label">توضیحات مشتری (برای پرکردن توسط مشتری)</label>
                                <textarea name="customer_note" class="form-control" rows="3" placeholder="هر توضیحی که مشتری باید وارد کند ...">{{ old('customer_note') }}</textarea>
                            </div>
                        </div>
                    </div>
                    {{-- سهامداران --}}
                    <div class="tab-pane fade" id="shareholderTabPane" role="tabpanel" aria-labelledby="shareholder-tab">
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label"><b>سهامداران و درصد سهم هرکدام</b></label>
                                <div class="alert alert-light border shadow-sm mb-2">
                                    <small>
                                        درصد سهام هرکدام را وارد کنید. اگر سهامداری در این خدمت سهم ندارد، مقدار را خالی بگذارید.<br>
                                        مجموع سهم‌ها باید حداکثر ۱۰۰ باشد.
                                    </small>
                                </div>
                                @if(isset($shareholders) && $shareholders->count())
                                    <div class="row" id="shareholder-list">
                                        @foreach($shareholders as $shareholder)
                                            <div class="col-12 mb-2">
                                                <div class="input-group">
                                                    <div class="input-group-text" style="min-width:120px">{{ $shareholder->full_name }}</div>
                                                    <input type="number"
                                                           class="form-control"
                                                           name="shareholders[{{ $shareholder->id }}]"
                                                           min="0"
                                                           max="100"
                                                           step="0.01"
                                                           value="{{ old('shareholders.'.$shareholder->id, '') }}"
                                                           placeholder="درصد سهم">
                                                    <span class="input-group-text">%</span>
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
                    {{-- لیست پرینتی اقلام و موارد خدمت --}}
                    <div class="tab-pane fade" id="allitemsTabPane" role="tabpanel" aria-labelledby="allitems-tab">
                        <div class="print-area" id="service-print-area">
                            <h4 class="mb-3">لیست مدارک و اطلاعات مورد نیاز جهت ارائه خدمت</h4>
                            <ul id="print-needed-items" class="mb-3"></ul>
                            <h5>سایر توضیحات یا محدودیت‌ها:</h5>
                            <ul id="print-weak-points" class="mb-3"></ul>
                            <h5>یادداشت مشتری:</h5>
                            <div id="print-customer-note" class="border rounded p-2 bg-light" style="min-height:50px"></div>
                        </div>
                        <button type="button" class="btn btn-outline-primary print-btn" onclick="window.print();"><i class="bi bi-printer"></i> پرینت این صفحه</button>
                        <div class="alert alert-info mt-3">
                            <small>این بخش را می‌توانید چاپ بگیرید و به مشتری بدهید تا مدارک و اطلاعات را کامل کند.</small>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-4"><i class="bi bi-check2-circle"></i> ثبت خدمت</button>
                    <a href="{{ route('services.index') }}" class="btn btn-secondary btn-lg px-4">انصراف</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
    function removeItem(btn) {
        btn.closest('li').remove();
        updatePrintList();
    }
    document.addEventListener('DOMContentLoaded', function () {
        // کد دلخواه خدمت
        let codeInput = document.getElementById('service_code');
        let customSwitch = document.getElementById('custom_code_switch');
        if (codeInput && customSwitch) {
            customSwitch.addEventListener('click', function () {
                if (codeInput.readOnly) {
                    codeInput.readOnly = false;
                    codeInput.value = '';
                    codeInput.focus();
                } else {
                    codeInput.readOnly = true;
                    codeInput.value = '{{ $nextCode ?? '' }}';
                }
            });
        }
        // پیش‌نمایش تصویر
        let imageInput = document.getElementById('image');
        let imagePreview = document.getElementById('image_preview');
        if (imageInput && imagePreview) {
            imageInput.addEventListener('change', function() {
                if (imageInput.files && imageInput.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.style.display = "block";
                        imagePreview.src = e.target.result;
                    }
                    reader.readAsDataURL(imageInput.files[0]);
                }
            });
        }
        // افزودن اقلام مورد نیاز
        document.getElementById('add-needed-item-btn').addEventListener('click', function() {
            let value = document.getElementById('add-needed-item-input').value.trim();
            if(value) {
                let li = document.createElement('li');
                li.className = "list-group-item d-flex align-items-center";
                li.innerHTML = `<input type="text" name="needed_items[]" class="form-control me-2" value="${value}" oninput="updatePrintList()"><button type="button" class="btn btn-danger remove-btn" onclick="removeItem(this)"><i class="bi bi-x-lg"></i></button>`;
                document.getElementById('needed-items-list').appendChild(li);
                document.getElementById('add-needed-item-input').value = '';
                updatePrintList();
            }
        });
        // افزودن نقاط ضعف
        document.getElementById('add-weak-point-btn').addEventListener('click', function() {
            let value = document.getElementById('add-weak-point-input').value.trim();
            if(value) {
                let li = document.createElement('li');
                li.className = "list-group-item d-flex align-items-center";
                li.innerHTML = `<input type="text" name="weak_points[]" class="form-control me-2" value="${value}" oninput="updatePrintList()"><button type="button" class="btn btn-danger remove-btn" onclick="removeItem(this)"><i class="bi bi-x-lg"></i></button>`;
                document.getElementById('weak-points-list').appendChild(li);
                document.getElementById('add-weak-point-input').value = '';
                updatePrintList();
            }
        });
        // چاپ لیست اقلام و محدودیت‌ها و یادداشت مشتری
        ['needed-items-list','weak-points-list','add-needed-item-input','add-weak-point-input','customer_note'].forEach(function(id){
            let el = document.getElementById(id);
            if(el) el.addEventListener('input', updatePrintList);
        });
        document.getElementsByName('customer_note')[0].addEventListener('input', updatePrintList);

        updatePrintList();
    });

    function updatePrintList(){
        // اقلام مورد نیاز
        let neededItems = document.querySelectorAll('#needed-items-list input[name="needed_items[]"]');
        let neededUl = document.getElementById('print-needed-items');
        neededUl.innerHTML = '';
        neededItems.forEach(function(input){
            if(input.value.trim()) {
                let li = document.createElement('li');
                li.textContent = input.value;
                neededUl.appendChild(li);
            }
        });
        // نقاط ضعف
        let weakPoints = document.querySelectorAll('#weak-points-list input[name="weak_points[]"]');
        let weakUl = document.getElementById('print-weak-points');
        weakUl.innerHTML = '';
        weakPoints.forEach(function(input){
            if(input.value.trim()) {
                let li = document.createElement('li');
                li.textContent = input.value;
                weakUl.appendChild(li);
            }
        });
        // یادداشت مشتری
        let note = document.getElementsByName('customer_note')[0].value;
        document.getElementById('print-customer-note').textContent = note;
    }
</script>
@endsection
