@extends('layouts.app')

@section('title', 'لیست دسته‌بندی‌ها')

@section('styles')
<style>
body { background: #f6fbfe; font-family: IRANSans, Vazirmatn, Tahoma, sans-serif; }
.table-categories {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px #2776d123;
    padding: 24px;
    margin-bottom: 32px;
    width: 100%;
    overflow-x: auto;
}
.table-categories th, .table-categories td {
    vertical-align: middle !important;
    text-align: right;
}
.table-categories th {
    background: #eaf5ff;
    color: #2776d1;
    font-weight: bold;
}
.cat-img {
    width: 38px;
    height: 38px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e0e8fa;
    background: #eaf6ff;
}
.badge-product { background: #d4f1e4; color: #1cb08e; }
.badge-service { background: #e2eafe; color: #2776d1; }
.badge-person { background: #ffe9c7; color: #c97e10; }
.badge-other { background: #f3fafd; color: #2776d1; }
.table-categories tbody tr:hover { background: #f3f8ff; }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
        <h2 class="fs-4 fw-bold text-primary mb-0">
            <i class="fa fa-sitemap ms-2"></i>لیست دسته‌بندی‌ها
        </h2>
        <a href="{{ route('categories.create') }}" class="btn btn-success px-4">
            <i class="fa fa-plus ms-2"></i>
            افزودن دسته‌بندی جدید
        </a>
    </div>
    <div class="table-responsive table-categories">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>تصویر</th>
                    <th>نام دسته</th>
                    <th>نوع</th>
                    <th>کد</th>
                    <th>توضیح</th>
                    <th>زیرمجموعه‌ها</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($categories as $category)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>
                        @if($category->image)
                            <img src="/storage/{{ $category->image }}" class="cat-img" alt="">
                        @endif
                    </td>
                    <td>{{ $category->name }}</td>
                    <td>
                        <span class="badge
                            @if($category->category_type === 'product') badge-product
                            @elseif($category->category_type === 'service') badge-service
                            @elseif($category->category_type === 'person') badge-person
                            @else badge-other
                            @endif
                        ">
                            {{ $category->category_type === 'product' ? 'محصول' : ($category->category_type === 'service' ? 'خدمت' : ($category->category_type === 'person' ? 'شخص' : 'سایر')) }}
                        </span>
                    </td>
                    <td>{{ $category->code }}</td>
                    <td>{{ $category->description }}</td>
                    <td>
                        @if(isset($category->children) && count($category->children))
                            @foreach($category->children as $child)
                                <span class="d-inline-block mb-1 px-2 py-1 rounded" style="background:#eef3fa;color:#2776d1;font-size:.97em;">
                                    {{ $child->name }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> ویرایش</a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('آیا مطمئن هستید؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> حذف</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @if(count($categories) == 0)
                <tr>
                    <td colspan="8" class="text-center text-muted">دسته‌بندی‌ای یافت نشد.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css"/>
@endsection
