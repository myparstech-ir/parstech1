@extends('layouts.app')

@section('title', 'ایجاد شخص جدید')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@latest/dist/css/persian-datepicker.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
:root {
    --main-bg: #f4f8fc;
    --card-bg: #fff;
    --primary: #2196f3;
    --primary-dark: #1565c0;
    --secondary: #10b981;
    --danger: #ef4444;
    --danger-light: #fee2e2;
    --border: #e0e7ef;
    --text-main: #1e293b;
    --text-soft: #64748b;
    --input-bg: #f5f7fa;
    --section-shadow: 0 4px 24px 0 rgba(33,150,243,0.06);
    --radius-lg: 18px;
    --radius-md: 10px;
    --radius-sm: 7px;
    --transition: all .25s cubic-bezier(.4,0,.2,1);
    --label-size: 1.04rem;
    --section-gap: 2.5rem;
    --icon-size: 1.4em;
}
body {
    background: var(--main-bg);
}
[dir="rtl"] { direction: rtl; }
.large-form-container {
    max-width: 1100px;
    margin: 1.5rem auto;
    background: var(--card-bg);
    border-radius: var(--radius-lg);
    box-shadow: var(--section-shadow);
    padding: 2.5rem 2rem;
    direction: rtl;
}
@media (max-width: 768px) {
    .large-form-container {padding: 0.7rem;}
}
.form-section {
    margin-bottom: var(--section-gap);
    border-radius: var(--radius-lg);
    background: var(--card-bg);
    box-shadow: 0 1px 8px 0 rgba(33,150,243,0.06);
}
/* Section Header */
.section-header {
    background: linear-gradient(90deg, var(--primary) 0, #56ccf2 100%);
    color: #fff;
    border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    padding: 1.1rem 1.4rem;
    font-weight: 700;
    font-size: 1.18rem;
    letter-spacing: .3px;
    display: flex;
    align-items: center;
    gap: .75rem;
    box-shadow: 0 1px 8px 0 rgba(33,150,243,0.06);
}
.section-header .section-icon {
    font-size: var(--icon-size);
    margin-left: .65rem;
    opacity: 0.80;
}
.section-header .step-num {
    background: #fff;
    color: var(--primary);
    border-radius: var(--radius-full, 50px);
    font-size: .95em;
    padding: .1em .7em;
    margin-left: .7em;
}
.section-body {
    padding: 2rem 1.4rem 1.4rem 1.4rem;
    border-radius: 0 0 var(--radius-lg) var(--radius-lg);
    background: var(--card-bg);
}
@media (max-width:600px) {
    .section-body {padding: 0.8rem;}
    .section-header {padding: .7rem;}
}
.form-label {
    font-weight: 600;
    color: var(--text-main);
    font-size: var(--label-size);
    margin-bottom: .2rem;
}
.required-field:after {
    content: "*";
    color: var(--danger);
    margin-right: .18em;
}
.form-control,
.select2-container--default .select2-selection--single {
    border-radius: var(--radius-md)!important;
    border: 1.5px solid var(--border);
    font-size: 1.05rem;
    background: var(--input-bg);
    transition: var(--transition);
    padding: .6rem 1rem;
    min-height: 43px;
}
input:focus, select:focus,textarea:focus,
.select2-container--default .select2-selection--single:focus {
    border-color: var(--primary);
    background: #fff;
}
.is-invalid, .is-invalid:focus {
    border-color: var(--danger)!important;
    background: var(--danger-light)!important;
}
.invalid-feedback {
    font-size: .95rem;
    color: var(--danger);
    margin-top: 3px;
}
input[type=text],input[type=email],input[type=number],input[type=url],select,textarea {
    width: 100%;
}
textarea {resize: vertical;}
.accounting-code-container {display:flex;align-items:center;gap:.6rem;}
.switch {position:relative;display:inline-block;width:42px;height:24px;}
.switch input {display:none;}
.slider {position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background:#cbd5e1;border-radius:24px;transition:.35s;}
.slider:before {position:absolute;content:"";height:18px;width:18px;left:3px;bottom:3px;background:white;transition:.35s;border-radius:50%;}
.switch input:checked + .slider {background:var(--primary);}
.switch input:checked + .slider:before {transform:translateX(18px);}
.accounting-code-label { font-size:.97em;color:var(--text-soft);}
.select2-container .select2-selection--single .select2-selection__rendered {line-height: 42px;}
.select2-container--default .select2-selection--single:focus,
.select2-container--default .select2-selection--single:active {border-color: var(--primary);}
.form-section .row { margin-bottom: 1.1rem;}
.form-actions {
    gap:1.2rem;
    display:flex;
    flex-wrap:wrap;
    margin-top:2.2rem;
}
.btn {
    min-width: 110px;
    padding: .65rem 1.6rem;
    font-weight: 600;
    border-radius: var(--radius-md);
    border: none;
    transition: var(--transition);
    font-size: 1.05rem;
    letter-spacing: .03em;
    box-shadow: 0 2px 8px rgba(33,150,243,0.07);
}
.btn-primary {
    background: var(--primary);
    color: #fff;
}
.btn-primary:hover, .btn-primary:focus { background: var(--primary-dark);}
.btn-secondary {
    background: var(--secondary);
    color: #fff;
}
.btn-secondary:hover, .btn-secondary:focus { filter: brightness(0.93);}
.btn-light {
    background: #eaf6fb;
    color: var(--primary);
}
.btn-light:hover { background: var(--primary-light);}
.btn-danger {background: var(--danger); color: #fff;}
.btn-danger:hover {background: var(--danger-light); color: var(--danger);}
.bank-account-row {background: var(--input-bg);}
.bank-account-row input {background: #fff;}
.alert {
    border-radius: var(--radius-md);
    font-size: 1.06em;
}
@media (max-width:991px){
    .large-form-container {padding: 1.2rem;}
}
@media (max-width:600px){
    .large-form-container {padding:.3rem;}
}
::-webkit-input-placeholder { color: #b6bbc3 !important; }
::-moz-placeholder { color: #b6bbc3 !important; }
:-ms-input-placeholder { color: #b6bbc3 !important; }
::placeholder { color: #b6bbc3 !important; }
</style>
@endpush

@section('content')
<div class="container-fluid large-form-container" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form id="person-form" action="{{ route('persons.store') }}" method="POST" novalidate>
                @csrf

                <!-- اطلاعات اصلی -->
                <div class="form-section">
                    <div class="section-header main-info">
                        <span class="step-num">۱</span>
                        <i class="fas fa-user section-icon"></i>
                        اطلاعات اصلی شخص
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <!-- نام -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field">نام</label>
                                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                           value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- نام خانوادگی -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field">نام خانوادگی</label>
                                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                           value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- نوع -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field">نوع شخص</label>
                                    <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                                        <option value="">انتخاب کنید</option>
                                        <option value="customer" {{ old('type') == 'customer' ? 'selected' : '' }}>مشتری</option>
                                        <option value="supplier" {{ old('type') == 'supplier' ? 'selected' : '' }}>تامین کننده</option>
                                        <option value="shareholder" {{ old('type') == 'shareholder' ? 'selected' : '' }}>سهامدار</option>
                                        <option value="employee" {{ old('type') == 'employee' ? 'selected' : '' }}>کارمند</option>
                                    </select>
                                    @error('type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- دسته‌بندی -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">دسته‌بندی</label>
                                    <select id="category_select" name="category_id" class="form-control">
                                        @if(old('category_id') && old('category_text'))
                                            <option value="{{ old('category_id') }}" selected>{{ old('category_text') }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- کد حسابداری -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field">کد مشتری</label>
                                    <div class="accounting-code-container">
                                        <input type="text" name="accounting_code"
                                               id="accounting_code"
                                               class="form-control @error('accounting_code') is-invalid @enderror"
                                               value="{{ old('accounting_code', $defaultCode ?? '') }}"
                                               required {{ old('auto_code', '1') === '1' ? 'readonly' : '' }}>
                                        <label class="switch">
                                            <input type="checkbox" id="autoCodeSwitch" name="auto_code"
                                                   value="1" {{ old('auto_code', '1') === '1' ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>
                                        <span class="accounting-code-label">کد خودکار</span>
                                    </div>
                                    @error('accounting_code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- شرکت -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">شرکت</label>
                                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}">
                                </div>
                            </div>
                            <!-- عنوان -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">عنوان</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                                </div>
                            </div>
                            <!-- نام مستعار -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">نام مستعار</label>
                                    <input type="text" name="nickname" class="form-control"
                                           value="{{ old('nickname') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- اطلاعات عمومی -->
                <div class="form-section">
                    <div class="section-header general-info">
                        <span class="step-num">۲</span>
                        <i class="fas fa-info-circle section-icon"></i>
                        اطلاعات عمومی
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">اعتبار مالی (ریال)</label>
                                    <input type="number" name="credit_limit" class="form-control" value="{{ old('credit_limit', 0) }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">لیست قیمت</label>
                                    <input type="text" name="price_list" class="form-control" value="{{ old('price_list') }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">نوع مالیات</label>
                                    <input type="text" name="tax_type" class="form-control" value="{{ old('tax_type') }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">کد ملی</label>
                                    <input type="text" name="national_code" class="form-control @error('national_code') is-invalid @enderror"
                                           value="{{ old('national_code') }}" maxlength="10" pattern="\d{10}">
                                    @error('national_code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">کد اقتصادی</label>
                                    <input type="text" name="economic_code" class="form-control" value="{{ old('economic_code') }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">شماره ثبت</label>
                                    <input type="text" name="registration_number" class="form-control" value="{{ old('registration_number') }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">کد شعبه</label>
                                    <input type="text" name="branch_code" class="form-control" value="{{ old('branch_code') }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">توضیحات</label>
                                    <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- آدرس -->
                <div class="form-section">
                    <div class="section-header address-info">
                        <span class="step-num">۳</span>
                        <i class="fas fa-map-marker-alt section-icon"></i>
                        آدرس
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field">آدرس کامل</label>
                                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address') }}</textarea>
                                    @error('address')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field">کشور</label>
                                    <input type="text" name="country" class="form-control @error('country') is-invalid @enderror"
                                           value="{{ old('country', 'ایران') }}" required>
                                    @error('country')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field">استان</label>
                                    <select id="province_select" name="province"
                                            class="form-control @error('province') is-invalid @enderror" required data-old-value="{{ old('province') }}">
                                        <option value="">انتخاب استان</option>
                                        @foreach($provinces as $prov)
                                            <option value="{{ $prov->id }}" {{ old('province') == $prov->id ? 'selected' : '' }}>
                                                {{ $prov->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('province')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field">شهر</label>
                                    <select id="city_select" name="city" class="form-control @error('city') is-invalid @enderror" required>
                                        <option value="">ابتدا استان را انتخاب کنید</option>
                                    </select>
                                    @error('city')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">کد پستی</label>
                                    <input type="text" name="postal_code" class="form-control"
                                           value="{{ old('postal_code') }}" maxlength="10" pattern="\d{10}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- اطلاعات تماس -->
                <div class="form-section">
                    <div class="section-header contact-info">
                        <span class="step-num">۴</span>
                        <i class="fas fa-phone section-icon"></i>
                        اطلاعات تماس
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">تلفن(ثابت)</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">موبایل</label>
                                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">فکس</label>
                                    <input type="text" name="fax" class="form-control" value="{{ old('fax') }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">تلفن ۱</label>
                                    <input type="text" name="phone1" class="form-control" value="{{ old('phone1') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">تلفن ۲</label>
                                    <input type="text" name="phone2" class="form-control" value="{{ old('phone2') }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">تلفن ۳</label>
                                    <input type="text" name="phone3" class="form-control" value="{{ old('phone3') }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">ایمیل</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label">وب سایت</label>
                                    <input type="url" name="website" class="form-control" value="{{ old('website') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- حساب‌های بانکی -->
                <div class="form-section">
                    <div class="section-header bank-info">
                        <span class="step-num">۵</span>
                        <i class="fas fa-university section-icon"></i>
                        حساب‌های بانکی
                        <button type="button" class="btn btn-light ms-auto" id="add-bank-account" style="margin-right:auto;">
                            <i class="fas fa-plus"></i> افزودن حساب بانکی
                        </button>
                    </div>
                    <div class="section-body">
                        <div id="bank-accounts">
                            @if(old('bank_accounts'))
                                @foreach(old('bank_accounts') as $idx => $account)
                                    <div class="bank-account-row mb-3 border rounded p-2" data-index="{{ $idx }}">
                                        <div class="form-row">
                                            <div class="col-md-2 mb-2">
                                                <input type="text" name="bank_accounts[{{ $idx }}][bank_name]" class="form-control" placeholder="نام بانک"
                                                    value="{{ $account['bank_name'] ?? '' }}">
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <input type="text" name="bank_accounts[{{ $idx }}][branch]" class="form-control" placeholder="شعبه"
                                                    value="{{ $account['branch'] ?? '' }}">
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <input type="text" name="bank_accounts[{{ $idx }}][account_number]" class="form-control" placeholder="شماره حساب"
                                                    value="{{ $account['account_number'] ?? '' }}">
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <input type="text" name="bank_accounts[{{ $idx }}][card_number]" class="form-control" placeholder="شماره کارت"
                                                    value="{{ $account['card_number'] ?? '' }}">
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <input type="text" name="bank_accounts[{{ $idx }}][iban]" class="form-control" placeholder="شماره شبا"
                                                    value="{{ $account['iban'] ?? '' }}">
                                            </div>
                                            <div class="col-md-1 mb-2 d-flex align-items-center">
                                                <button type="button" class="btn btn-danger btn-sm remove-bank-account" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- تاریخ‌ها -->
                <div class="form-section">
                    <div class="section-header date-info">
                        <span class="step-num">۶</span>
                        <i class="fas fa-calendar section-icon"></i>
                        تاریخ‌ها
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="form-label">تاریخ تولد</label>
                                    <input type="text" name="birth_date" class="form-control datepicker" value="{{ old('birth_date') }}">
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="form-label">تاریخ ازدواج</label>
                                    <input type="text" name="marriage_date" class="form-control datepicker" value="{{ old('marriage_date') }}">
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field">تاریخ عضویت</label>
                                    <input type="text" name="join_date" class="form-control datepicker"
                                           value="{{ old('join_date', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- دکمه‌های فرم -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> ذخیره
                    </button>
                    <a href="{{ route('persons.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> انصراف
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://unpkg.com/persian-date@latest/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@latest/dist/js/persian-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#category_select').select2({
        placeholder: 'انتخاب یا جستجوی دسته‌بندی شخص',
        ajax: {
            url: '{{ route("categories.person-search") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) { return { q: params.term }; },
            processResults: function (data) { return { results: data.slice(0, 5) }; },
            cache: true
        },
        minimumInputLength: 0,
        language: { noResults: () => "دسته‌بندی یافت نشد" }
    });

    @if(old('category_id') && old('category_text'))
        var option = new Option("{{ old('category_text') }}", "{{ old('category_id') }}", true, true);
        $('#category_select').append(option).trigger('change');
    @endif
    $('#autoCodeSwitch').on('change', function() {
        if ($(this).is(':checked')) {
            $('#accounting_code').prop('readonly', true);
            $.get('{{ route("persons.next-code") }}', function(data) {
                $('#accounting_code').val(data.code);
            });
        } else {
            $('#accounting_code').prop('readonly', false);
        }
    });
    @if(old('auto_code', '1') === '1' && !old('accounting_code'))
        $.get('{{ route("persons.next-code") }}', function(data) {
            $('#accounting_code').val(data.code);
        });
    @endif
    $('#province_select').on('change', function() {
        let provinceId = $(this).val();
        $('#city_select').empty().append('<option value="">در حال بارگذاری...</option>');
        if (provinceId) {
            $.getJSON('/provinces/' + provinceId + '/cities', function (data) {
                let items = '<option value="">انتخاب شهر</option>';
                $.each(data, function (i, city) {
                    let selected = '';
                    @if(!old('city'))
                        if(provinceId == 11 && city.id == 1106) selected = 'selected';
                    @endif
                    items += `<option value="${city.id}" ${selected}>${city.name}</option>`;
                });
                $('#city_select').html(items);
            }).fail(function () {
                $('#city_select').html('<option value="">خطا در دریافت شهرها</option>');
            });
        } else {
            $('#city_select').html('<option value="">ابتدا استان را انتخاب کنید</option>');
        }
    });
    @if(old('province'))
        $.getJSON('/provinces/{{ old('province') }}/cities', function(data){
            let items = '<option value="">انتخاب شهر</option>';
            $.each(data, function(i, city){
                let selected = ({{ old('city') ?: 0 }} == city.id) ? 'selected' : '';
                items += `<option value="${city.id}" ${selected}>${city.name}</option>`;
            });
            $('#city_select').html(items);
        });
    @endif
    $('.datepicker').persianDatepicker({
        format: 'YYYY-MM-DD',
        initialValue: false,
        autoClose: true,
        toolbox: {
            calendarSwitch: { enabled: false },
            todayButton: { enabled: true },
            submitButton: { enabled: true }
        }
    });
    let bankAccountIndex = $('#bank-accounts .bank-account-row').length || 0;
    $('#add-bank-account').on('click', function() {
        bankAccountIndex++;
        let bankAccountHtml = `
            <div class="bank-account-row mb-3 border rounded p-2" data-index="${bankAccountIndex}">
                <div class="form-row">
                    <div class="col-md-2 mb-2">
                        <input type="text" name="bank_accounts[${bankAccountIndex}][bank_name]" class="form-control" placeholder="نام بانک">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="text" name="bank_accounts[${bankAccountIndex}][branch]" class="form-control" placeholder="شعبه">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="text" name="bank_accounts[${bankAccountIndex}][account_number]" class="form-control" placeholder="شماره حساب">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="text" name="bank_accounts[${bankAccountIndex}][card_number]" class="form-control" placeholder="شماره کارت">
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="text" name="bank_accounts[${bankAccountIndex}][iban]" class="form-control" placeholder="شماره شبا">
                    </div>
                    <div class="col-md-1 mb-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove-bank-account" title="حذف">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#bank-accounts').append(bankAccountHtml);
    });
    $(document).on('click', '.remove-bank-account', function() {
        $(this).closest('.bank-account-row').remove();
    });
    $('#person-form').on('submit', function(e) {
        let isValid = true;
        $(this).find('[required]').each(function(){
            if(!$(this).val() || $(this).val().trim() === ''){
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        if(!isValid) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $(".is-invalid:first").offset().top - 100
            }, 500);
        }
    });
    $('input, select, textarea').on('input change', function(){
        if($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid');
        }
    });
});
</script>
@endpush
