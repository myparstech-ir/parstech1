@extends('layouts.app')

@section('title', 'نمایش خدمت')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .service-img { max-width: 150px; border-radius: 12px; }
        .info-label { font-weight: bold; color: #005cbf;}
    </style>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0"><i class="bi bi-eye"></i> نمایش خدمت</h3>
        <a href="{{ route('services.edit', $service) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> ویرایش</a>
    </div>
    <div class="row bg-white rounded shadow-sm p-3">
        <div class="col-md-4 mb-3 text-center">
            @if($service->image)
                <img src="{{ asset('storage/'.$service->image) }}" class="service-img" alt="تصویر خدمت">
            @else
                <span class="text-muted">بدون تصویر</span>
            @endif
        </div>
        <div class="col-md-8">
            <table class="table table-borderless">
                <tr>
                    <td class="info-label">کد خدمت:</td>
                    <td>{{ $service->service_code }}</td>
                </tr>
                <tr>
                    <td class="info-label">عنوان خدمت:</td>
                    <td>{{ $service->title }}</td>
                </tr>
                <tr>
                    <td class="info-label">دسته‌بندی:</td>
                    <td>{{ $service->category?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="info-label">قیمت:</td>
                    <td>{{ number_format($service->price) }} تومان</td>
                </tr>
                <tr>
                    <td class="info-label">واحد:</td>
                    <td>{{ $service->unit }}</td>
                </tr>
                <tr>
                    <td class="info-label">وضعیت:</td>
                    <td>
                        @if($service->is_active)
                            <span class="badge bg-success">فعال</span>
                        @else
                            <span class="badge bg-secondary">غیرفعال</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="info-label">توضیح کوتاه:</td>
                    <td>{{ $service->short_description }}</td>
                </tr>
                <tr>
                    <td class="info-label">توضیحات کامل:</td>
                    <td>{!! nl2br(e($service->full_description)) !!}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="mt-4">
        <h5>سهامداران و درصد سهم:</h5>
        <ul>
            @foreach($service->shareholders as $sh)
                <li>
                    {{ $sh->full_name }} ({{ $sh->pivot->percent }}%)
                </li>
            @endforeach
            @if($service->shareholders->isEmpty())
                <li class="text-muted">سهامداری ثبت نشده است.</li>
            @endif
        </ul>
    </div>
    <div class="mt-3">
        <a href="{{ route('services.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-right"></i> بازگشت</a>
    </div>
</div>
@endsection
