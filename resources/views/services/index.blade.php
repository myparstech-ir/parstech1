@extends('layouts.app')

@section('title', 'لیست خدمات')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .table th, .table td { vertical-align: middle; }
        .service-img-thumb { width: 50px; border-radius: 7px; }
        .action-btns .btn { margin-inline-end: 2px; }
    </style>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0"><i class="bi bi-list-task"></i> لیست خدمات</h3>
        <a href="{{ route('services.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> افزودن خدمت جدید</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-4">
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" name="q" class="form-control" placeholder="جستجو بر اساس عنوان یا کد..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="service_category_id" class="form-select">
                    <option value="">دسته‌بندی همه</option>
                    @foreach($serviceCategories as $cat)
                        <option value="{{ $cat->id }}" {{ request('service_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i> جستجو</button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>تصویر</th>
                    <th>کد</th>
                    <th>عنوان</th>
                    <th>دسته‌بندی</th>
                    <th>قیمت (تومان)</th>
                    <th>واحد</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                    <tr>
                        <td>{{ $loop->iteration + ($services->currentPage() - 1) * $services->perPage() }}</td>
                        <td>
                            @if($service->image)
                                <img src="{{ asset('storage/'.$service->image) }}" class="service-img-thumb" alt="img">
                            @else
                                <span class="text-muted">---</span>
                            @endif
                        </td>
                        <td>{{ $service->service_code }}</td>
                        <td><a href="{{ route('services.show', $service) }}">{{ $service->title }}</a></td>
                        <td>{{ $service->category?->name ?? '-' }}</td>
                        <td>{{ number_format($service->price) }}</td>
                        <td>{{ $service->unit }}</td>
                        <td>
                            @if($service->is_active)
                                <span class="badge bg-success">فعال</span>
                            @else
                                <span class="badge bg-secondary">غیرفعال</span>
                            @endif
                        </td>
                        <td class="action-btns">
                            <a href="{{ route('services.show', $service) }}" class="btn btn-info btn-sm" title="نمایش"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('services.edit', $service) }}" class="btn btn-warning btn-sm" title="ویرایش"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('services.destroy', $service) }}" method="POST" class="d-inline" onsubmit="return confirm('آیا مطمئنید؟');">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="حذف"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-danger">خدمتی ثبت نشده است.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>
        {{ $services->withQueryString()->links() }}
    </div>
</div>
@endsection
