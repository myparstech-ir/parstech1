@extends('layouts.app')

@section('title', 'افزودن دسته‌بندی جدید')

@section('styles')
<style>
/* رنگ‌ها و متغیرهای پایه */
:root {
    --primary: #2776d1;
    --primary-light: #eaf5ff;
    --success: #26b371;
    --danger: #dc3545;
    --gray: #f6f8fa;
    --bg: #fff;
    --input-bg: #fafdff;
    --border: #e2e6ee;
    --radius: 18px;
    --transition: 0.23s;
    --shadow: 0 4px 18px #2776d12a;
    --input-focus: #b5d3f7;
}
body { background: var(--gray); }
.category-create-card {
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    background: var(--bg);
    overflow: hidden;
    border: none;
}
.category-create-header {
    background: linear-gradient(90deg, var(--primary) 60%, #1e5aa0 100%);
    border-bottom: none;
    padding: 1.2rem 2rem;
}
#category-create-title {
    font-weight: bold;
    font-size: 1.35rem;
    letter-spacing: .5px;
}
.category-create-tabs {
    gap: 1.2rem;
}
.category-create-tab-btn {
    border: none;
    border-radius: 8px 8px 0 0;
    background: var(--input-bg);
    color: var(--primary);
    font-weight: 600;
    padding: .7rem 2.2rem;
    font-size: 1.05rem;
    transition: all var(--transition);
    margin-bottom: -2px;
    box-shadow: 0 2px 8px #2776d11a;
}
.category-create-tab-btn.active, .category-create-tab-btn:hover {
    background: var(--primary);
    color: #fff;
    box-shadow: 0 6px 18px #2776d122;
}
.category-create-body {
    padding: 2.2rem 1.7rem 2.2rem 1.7rem;
    background: var(--bg);
    border-radius: 0 0 var(--radius) var(--radius);
}
.img-upload-wrapper {
    display: inline-block;
    position: relative;
}
.category-create-img, .cat-img-placeholder {
    width: 74px;
    height: 74px;
    border-radius: 12px;
    object-fit: cover;
    border: 2.5px solid var(--border);
    background: var(--input-bg);
    cursor: pointer;
    box-shadow: 0 2px 8px #2776d11a;
    transition: border-color var(--transition), box-shadow var(--transition);
}
.category-create-img:hover, .cat-img-placeholder:hover {
    border-color: var(--primary);
    box-shadow: 0 4px 18px #2776d13a;
}
.cat-img-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 2.2rem;
    background: var(--primary-light);
}
.img-overlay {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    background: rgba(39, 118, 209, 0.83);
    color: #fff;
    border-radius: 0 0 12px 12px;
    text-align: center;
    cursor: pointer;
    padding: 5px 0;
    font-size: .95rem;
    font-weight: 500;
    opacity: 0;
    transition: opacity var(--transition);
}
.img-upload-wrapper:hover .img-overlay { opacity: 1; }
.img-hidden-input { display: none; }
.category-create-label {
    font-weight: 600;
    color: var(--primary);
    margin-bottom: .3rem;
    font-size: 1.00rem;
}
.category-create-input {
    border-radius: 10px;
    background: var(--input-bg);
    border: 1.4px solid var(--border);
    font-size: 1.04rem;
    padding: .7rem .95rem;
    transition: border-color var(--transition), background var(--transition);
}
.category-create-input:focus {
    border-color: var(--input-focus);
    background: #f2f9ff;
    box-shadow: 0 2px 10px #2776d13a;
}
.category-create-submit {
    font-size: 1.08rem;
    font-weight: bold;
    border-radius: 7px;
    padding: .7rem 2.4rem;
    border: none;
    box-shadow: 0 2px 8px #2776d11a;
    transition: all var(--transition);
}
.category-create-submit.product { background: var(--primary); color: #fff; }
.category-create-submit.product:hover { background: #1e5aa0; }
.category-create-submit.person { background: var(--success); color: #fff; }
.category-create-submit.person:hover { background: #18915a; }
.category-create-submit.service { background: #fbc02d; color: #fff; }
.category-create-submit.service:hover { background: #c49004; }
@media (max-width: 700px) {
    .category-create-card, .category-create-body { padding: 1.1rem !important; }
    .category-create-tabs { flex-direction: column; gap: .8rem; }
}
/* استایل نمایش خطا و موفقیت */
.alert { border-radius: 9px; font-size: .97rem; }
</style>
@endsection

@section('content')
<div class="container mt-4 category-create-container">
    <div class="card category-create-card" id="category-create-card">
        <div class="card-header text-white category-create-header" id="category-create-header">
            <h5 class="mb-0" id="category-create-title">افزودن دسته‌بندی جدید</h5>
        </div>
        <div class="card-body category-create-body">

            {{-- پیام موفقیت --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- نمایش خطاها --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- دکمه‌های تب‌بندی رنگی --}}
            <div class="mb-4 d-flex justify-content-center category-create-tabs">
                <button type="button" class="btn category-create-tab-btn" id="btn-person" onclick="showTab('person')">دسته‌بندی اشخاص</button>
                <button type="button" class="btn category-create-tab-btn" id="btn-product" onclick="showTab('product')">دسته‌بندی کالا</button>
                <button type="button" class="btn category-create-tab-btn" id="btn-service" onclick="showTab('service')">دسته‌بندی خدمات</button>
            </div>

            {{-- فرم دسته‌بندی اشخاص --}}
            <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data" id="form-person" style="display: none;">
                @csrf
                <input type="hidden" name="category_type" value="person">
                <div class="mb-3 text-center">
                    <div class="img-upload-wrapper">
                        <img id="img-person" src="{{ asset('img/category-person.png') }}" alt="پیش‌فرض اشخاص" class="img-thumbnail category-create-img" onclick="triggerFileInput('person_image')">
                        <div class="img-overlay" onclick="triggerFileInput('person_image')"><span>تغییر</span></div>
                        <input type="file" name="image" id="person_image" class="form-control category-create-input img-hidden-input" accept="image/*" onchange="previewImage(this, 'img-person')">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="person_name" class="form-label category-create-label">نام دسته‌بندی اشخاص</label>
                    <input type="text" name="name" id="person_name" class="form-control category-create-input" required>
                </div>
                <div class="mb-3">
                    <label for="person_code" class="form-label category-create-label">کد دسته‌بندی</label>
                    <input type="text" name="code" id="person_code" class="form-control category-create-input" value="{{ $nextPersonCode ?? 'per1001' }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="person_parent_id" class="form-label category-create-label">زیر دسته</label>
                    <select name="parent_id" id="person_parent_id" class="form-control category-create-input select2">
                        <option value="">بدون زیر دسته</option>
                        @foreach($personCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="person_description" class="form-label category-create-label">توضیحات</label>
                    <textarea name="description" id="person_description" class="form-control category-create-input" rows="2"></textarea>
                </div>
                <div class="mb-3 text-end">
                    <button type="submit" class="btn category-create-submit person"><i class="fa fa-user-plus ms-1"></i>ثبت دسته‌بندی اشخاص</button>
                </div>
            </form>

            {{-- فرم دسته‌بندی کالا --}}
            <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data" id="form-product" style="display: none;">
                @csrf
                <input type="hidden" name="category_type" value="product">
                <div class="mb-3 text-center">
                    <div class="img-upload-wrapper">
                        <img id="img-product" src="{{ asset('img/category-product.png') }}" alt="پیش‌فرض کالا" class="img-thumbnail category-create-img" onclick="triggerFileInput('product_image')">
                        <div class="img-overlay" onclick="triggerFileInput('product_image')"><span>تغییر</span></div>
                        <input type="file" name="image" id="product_image" class="form-control category-create-input img-hidden-input" accept="image/*" onchange="previewImage(this, 'img-product')">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="product_name" class="form-label category-create-label">نام دسته‌بندی کالا</label>
                    <input type="text" name="name" id="product_name" class="form-control category-create-input" required>
                </div>
                <div class="mb-3">
                    <label for="product_code" class="form-label category-create-label">کد دسته‌بندی</label>
                    <input type="text" name="code" id="product_code" class="form-control category-create-input" value="{{ $nextProductCode ?? 'pro1001' }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="product_parent_id" class="form-label category-create-label">زیر دسته</label>
                    <select name="parent_id" id="product_parent_id" class="form-control category-create-input select2">
                        <option value="">بدون زیر دسته</option>
                        @foreach($productCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="product_description" class="form-label category-create-label">توضیحات</label>
                    <textarea name="description" id="product_description" class="form-control category-create-input" rows="2"></textarea>
                </div>
                <div class="mb-3 text-end">
                    <button type="submit" class="btn category-create-submit product"><i class="fa fa-box-open ms-1"></i>ثبت دسته‌بندی کالا</button>
                </div>
            </form>

            {{-- فرم دسته‌بندی خدمات --}}
            <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data" id="form-service" style="display: none;">
                @csrf
                <input type="hidden" name="category_type" value="service">
                <div class="mb-3 text-center">
                    <div class="img-upload-wrapper">
                        <img id="img-service" src="{{ asset('img/category-service.png') }}" alt="پیش‌فرض خدمات" class="img-thumbnail category-create-img" onclick="triggerFileInput('service_image')">
                        <div class="img-overlay" onclick="triggerFileInput('service_image')"><span>تغییر</span></div>
                        <input type="file" name="image" id="service_image" class="form-control category-create-input img-hidden-input" accept="image/*" onchange="previewImage(this, 'img-service')">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="service_name" class="form-label category-create-label">نام دسته‌بندی خدمات</label>
                    <input type="text" name="name" id="service_name" class="form-control category-create-input" required>
                </div>
                <div class="mb-3">
                    <label for="service_code" class="form-label category-create-label">کد دسته‌بندی</label>
                    <input type="text" name="code" id="service_code" class="form-control category-create-input" value="{{ $nextServiceCode ?? 'ser1001' }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="service_parent_id" class="form-label category-create-label">زیر دسته</label>
                    <select name="parent_id" id="service_parent_id" class="form-control category-create-input select2">
                        <option value="">بدون زیر دسته</option>
                        @foreach($serviceCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="service_description" class="form-label category-create-label">توضیحات</label>
                    <textarea name="description" id="service_description" class="form-control category-create-input" rows="2"></textarea>
                </div>
                <div class="mb-3 text-end">
                    <button type="submit" class="btn category-create-submit service"><i class="fa fa-cogs ms-1"></i>ثبت دسته‌بندی خدمات</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css"/>
<!-- اگر پکیج select2 داری این را فعال کن تا انتخاب والد بهتر شود -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    // رنگ‌های تب هر بخش
    const tabColors = {
        person: {
            bg: '#26b371',
            btn: '#26b371',
            btnClass: 'active-person',
            card: '#e8f7f0'
        },
        product: {
            bg: '#2776d1',
            btn: '#2776d1',
            btnClass: 'active-product',
            card: '#eaf5ff'
        },
        service: {
            bg: '#fbc02d',
            btn: '#fbc02d',
            btnClass: 'active-service',
            card: '#fffbe5'
        }
    };

    function showTab(type) {
        document.getElementById('form-person').style.display = (type === 'person') ? 'block' : 'none';
        document.getElementById('form-product').style.display = (type === 'product') ? 'block' : 'none';
        document.getElementById('form-service').style.display = (type === 'service') ? 'block' : 'none';

        ['person','product','service'].forEach(function(t){
            document.getElementById('btn-'+t).classList.remove('active', 'active-person', 'active-product', 'active-service');
            document.getElementById('btn-'+t).style.background = 'var(--input-bg)';
            document.getElementById('btn-'+t).style.color = 'var(--primary)';
        });

        document.getElementById('btn-' + type).classList.add('active', tabColors[type].btnClass);
        document.getElementById('btn-' + type).style.background = tabColors[type].btn;
        document.getElementById('btn-' + type).style.color = '#fff';

        document.getElementById('category-create-header').style.background = tabColors[type].bg;
        document.getElementById('category-create-card').style.background = tabColors[type].card;

        let label = '';
        if (type === 'person') label = 'افزودن دسته‌بندی اشخاص';
        if (type === 'product') label = 'افزودن دسته‌بندی کالا';
        if (type === 'service') label = 'افزودن دسته‌بندی خدمات';
        document.getElementById('category-create-title').textContent = label;
    }

    document.addEventListener("DOMContentLoaded", function() {
        showTab('product');
        // فعال‌سازی select2 برای انتخاب والد
        if (window.jQuery && $('.select2').length) {
            $('.select2').select2({
                width: '100%',
                placeholder: "جستجو/انتخاب دسته والد...",
                allowClear: true,
                dir: "rtl"
            });
        }
    });

    // نمایش پیش‌نمایش عکس
    function previewImage(input, imgId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById(imgId).src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // کلیک روی عکس، انتخاب فایل
    function triggerFileInput(inputId) {
        document.getElementById(inputId).click();
    }
</script>
@endsection
