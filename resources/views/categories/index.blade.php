@extends('layouts.app')

@section('title', 'دسته‌بندی‌ها (درختی پیشرفته)')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.15/themes/default/style.min.css"/>
<link rel="stylesheet" href="{{ asset('css/category-tree-jstree.css') }}">
@endsection

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-center">دسته‌بندی‌ها (درختی)</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <input id="category-search" type="text" class="form-control d-inline-block" style="width:220px" placeholder="جستجو در دسته‌بندی‌ها...">
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">افزودن دسته‌بندی جدید</a>
    </div>
    <div id="categoryTree" class="category-tree-container"></div>
    <div id="category-details" class="category-details-card mt-4" style="display:none"></div>
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
        var img = node.data.image ? '<img src="/storage/'+node.data.image+'" class="cat-img" />' : '';
        var html = `
            <div class="card shadow-sm p-3">
                <h5 class="mb-2">${node.text}</h5>
                ${img ? '<div class="mb-2">'+img+'</div>' : ''}
                <div>${desc}</div>
                <div class="mt-2 text-end">
                    <a href="/categories/${node.id}/edit" class="btn btn-sm btn-warning">ویرایش</a>
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
