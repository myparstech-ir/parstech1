@extends('layouts.app')

@section('title', 'دسته‌بندی‌ها (درختی پیشرفته)')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.15/themes/default/style.min.css"/>
    <style>
        .category-tree-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 16px #00000013;
            padding: 30px 20px 10px 20px;
            min-height: 360px;
        }
        .jstree-default .jstree-icon {
            background: none !important;
        }
        .jstree-default .jstree-anchor > .fa {
            font-size: 19px;
            vertical-align: -3px;
            margin-left: 4px;
        }
        .badge {
            font-size: 12px !important;
            vertical-align: 2px;
        }
        .category-details-card {
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e4e4e4;
            box-shadow: 0 2px 8px #0001;
            padding: 24px;
        }
    </style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4">
                    <h2 class="mb-0 fs-4 fw-bold text-primary"><i class="fa fa-sitemap ms-2"></i>دسته‌بندی‌ها (درختی)</h2>
                    <a href="{{ route('categories.create') }}" class="btn btn-success px-4"><i class="fa fa-plus ms-2"></i>افزودن دسته‌بندی جدید</a>
                </div>
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="mb-3">
                    <input id="category-search" type="text" class="form-control" style="width:260px; max-width:100%;" placeholder="جستجو در دسته‌بندی‌ها...">
                </div>
                <div id="categoryTree" class="category-tree-container"></div>
                <div id="category-details" class="category-details-card mt-4" style="display:none"></div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.15/jstree.min.js"></script>
<script>
$(function() {
    $('#categoryTree').jstree({
        core: {
            data: {
                url: '{{ route('categories.tree-data') }}',
                dataType: 'json'
            },
            multiple: false,
            themes: { variant: 'large', icons: true }
        },
        plugins: ['search', 'wholerow'],
        search: { case_sensitive: false, show_only_matches: true },
        rtl: true
    });

    var to = false;
    $('#category-search').keyup(function () {
        if(to) clearTimeout(to);
        to = setTimeout(function () {
            var v = $('#category-search').val();
            $('#categoryTree').jstree(true).search(v);
        }, 250);
    });

    $('#categoryTree').on('select_node.jstree', function (e, data) {
        var node = data.node;
        var desc = node.data.description || '';
        var img = node.data.image ? '<img src="/storage/'+node.data.image+'" class="cat-img mb-2" />' : '';
        var html = `
            <div>
                <h5 class="mb-2 fw-bold">${$(node.text).text()}</h5>
                ${img ? '<div class="mb-2">'+img+'</div>' : ''}
                <div>${desc}</div>
                <div class="mt-3 text-end">
                    <a href="/categories/${node.id}/edit" class="btn btn-warning btn-sm px-3"><i class="fa fa-edit ms-1"></i>ویرایش</a>
                </div>
            </div>
        `;
        $('#category-details').html(html).show();
    });

    $('#categoryTree').on('deselect_all.jstree', function () {
        $('#category-details').hide();
    });
});
</script>
@endsection
