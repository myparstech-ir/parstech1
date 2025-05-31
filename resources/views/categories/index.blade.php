@extends('layouts.app')

@section('title', 'لیست دسته‌بندی‌ها (جدولی درختی)')

@section('styles')
<style>
    body { background: #f7fafd; }
    .cat-tree-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 32px #00000011;
        overflow: hidden;
    }
    .cat-tree-table th, .cat-tree-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #e2e6ee;
        font-size: 1.04rem;
        text-align: right;
        background: #fff;
    }
    .cat-tree-table th {
        background: #f3f7fa;
        font-weight: 900;
        color: #2776d1;
        font-size: 1.08rem;
    }
    .cat-tree-table tr:last-child td { border-bottom: none; }

    .cat-tree-indent {
        display: inline-block;
        width: 22px;
        height: 1px;
        vertical-align: middle;
    }
    .cat-tree-toggle {
        background: none;
        border: none;
        outline: none;
        cursor: pointer;
        margin-left: 2px;
        font-size: 1.15em;
        color: #2769c7;
        vertical-align: middle;
        transition: transform .2s;
    }
    .cat-tree-toggle[aria-expanded="true"] { transform: rotate(90deg);}
    .cat-tree-row { transition: background .15s;}
    .cat-tree-row:hover { background: #eaf5ff; }
    .cat-tree-img {
        width: 42px; height: 42px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e0e8fa;
        box-shadow: 0 1px 8px #2776d133;
        background: #eaf6ff;
    }
    .cat-tree-action-btn {
        border: none;
        outline: none;
        background: #f7fafd;
        color: #2776d1;
        border-radius: 7px;
        padding: 7px 13px;
        margin-left: 5px;
        font-size: 1em;
        font-weight: bold;
        cursor: pointer;
        transition: background .17s, color .16s;
    }
    .cat-tree-action-btn:hover {
        background: #2776d1;
        color: #fff;
    }
    .cat-type-prod { color: #1cb08e; font-weight: bold;}
    .cat-type-serv { color: #2776d1; font-weight: bold;}
    .cat-type-pers { color: #c97e10; font-weight: bold;}
    .cat-tree-desc {
        color: #555;
        font-size: 0.99em;
        max-width: 300px;
        white-space: pre-line;
    }
    .cat-tree-badge {
        display: inline-block;
        padding: 2px 10px;
        font-size: 0.98em;
        border-radius: 7px;
        background: #eef3fa;
        color: #2769c7;
        margin-left: 3px;
        font-weight: bold;
    }
    .cat-tree-table tr[hidden] {display: none;}
    @media (max-width: 900px) {
        .cat-tree-table, .cat-tree-table th, .cat-tree-table td { font-size: 0.93em;}
        .cat-tree-img { width: 32px; height: 32px;}
    }
    @media (max-width: 600px) {
        .cat-tree-table th, .cat-tree-table td { padding: 8px 5px;}
        .cat-tree-table { font-size: 0.85em;}
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
        <h2 class="fs-4 fw-bold text-primary mb-0">
            <i class="fa fa-sitemap ms-2"></i>دسته‌بندی‌ها (جدولی درختی)
        </h2>
        <a href="{{ route('categories.create') }}" class="btn btn-success px-4">
            <i class="fa fa-plus ms-2"></i>
            افزودن دسته‌بندی جدید
        </a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
        <table class="cat-tree-table">
            <thead>
                <tr>
                    <th style="width:35px"></th>
                    <th>نام دسته</th>
                    <th>نوع</th>
                    <th>کد</th>
                    <th>توضیح</th>
                    <th>تصویر</th>
                    <th>تعداد محصول</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @php
                    function renderCategoryRows($categories, $level = 0, $parentId = null) {
                        foreach($categories as $category) {
                            $hasChildren = $category->children && $category->children->count() > 0;
                            $typeClass = $category->category_type == 'product' ? 'cat-type-prod' :
                                         ($category->category_type == 'service' ? 'cat-type-serv' : 'cat-type-pers');
                            echo '<tr class="cat-tree-row" data-id="'.$category->id.'" data-parent="'.($category->parent_id ?: '').'" data-level="'.$level.'"'.($level > 0 ? ' hidden' : '').'>';
                            echo '<td>';
                            if($hasChildren) {
                                echo '<button class="cat-tree-toggle" aria-expanded="false" data-toggle-id="'.$category->id.'" title="باز/بستن زیرشاخه‌ها"><i class="fa fa-caret-left"></i></button>';
                            } else {
                                echo str_repeat('<span class="cat-tree-indent"></span>', $level+1);
                            }
                            echo '</td>';
                            echo '<td>';
                            echo str_repeat('<span class="cat-tree-indent"></span>', $level);
                            echo e($category->name);
                            echo '</td>';
                            echo '<td class="'.$typeClass.'">';
                            echo $category->category_type == 'product' ? 'محصول' : ($category->category_type == 'service' ? 'خدمت' : 'شخص');
                            echo '</td>';
                            echo '<td><span class="cat-tree-badge">'.e($category->code).'</span></td>';
                            echo '<td><span class="cat-tree-desc">'.e($category->description).'</span></td>';
                            echo '<td>';
                            if($category->image)
                                echo '<img src="/storage/'.e($category->image).'" class="cat-tree-img" alt="">';
                            echo '</td>';
                            echo '<td>';
                            echo '<span class="cat-tree-badge">'.($category->products ? $category->products->count() : 0).'</span>';
                            echo '</td>';
                            echo '<td>';
                            echo '<a href="'.route('categories.edit', $category->id).'" class="cat-tree-action-btn"><i class="fa fa-edit ms-1"></i>ویرایش</a>';
                            echo '<form action="'.route('categories.destroy', $category->id).'" method="POST" style="display:inline;" onsubmit="return confirm(\'آیا مطمئن هستید؟\')">';
                            echo csrf_field();
                            echo method_field('DELETE');
                            echo '<button type="submit" class="cat-tree-action-btn"><i class="fa fa-trash ms-1"></i>حذف</button>';
                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                            if($hasChildren) {
                                renderCategoryRows($category->children, $level + 1, $category->id);
                            }
                        }
                    }
                @endphp
                @php
                    // گرفته شده با eager loading: Category::with('children', 'products')->whereNull('parent_id')->get()
                    renderCategoryRows($categories);
                @endphp
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.cat-tree-toggle').forEach(function(btn){
        btn.addEventListener('click', function(){
            let id = btn.getAttribute('data-toggle-id');
            let expanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', !expanded);
            toggleChildren(id, !expanded);
        });
    });

    function toggleChildren(parentId, show) {
        document.querySelectorAll('tr[data-parent="' + parentId + '"]').forEach(function(row){
            if(show) {
                row.removeAttribute('hidden');
            } else {
                row.setAttribute('hidden', 'hidden');
            }
            // اگر در حال جمع‌کردن هستیم، همه زیرشاخه‌های تو در تو را هم مخفی کن
            if(!show) {
                let id = row.getAttribute('data-id');
                toggleChildren(id, false);
                let btn = row.querySelector('.cat-tree-toggle');
                if(btn) btn.setAttribute('aria-expanded', false);
            }
        });
    }
});
</script>
@endsection
