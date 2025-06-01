@extends('layouts.app')

@section('title', 'لیست دسته‌بندی‌ها (جدولی درختی)')

@section('styles')
<style>
/* اینجا همه استایل‌های CSS که در بالا آمد قرار می‌گیرد */
/* برای جلوگیری از تکرار، آن را دوباره نمی‌نویسم */
</style>
@endsection

@section('content')
<div class="categories-container">
    <div class="categories-header">
        <h2 class="categories-title">
            <i class="fa fa-sitemap"></i>
            <span>مدیریت دسته‌بندی‌ها</span>
        </h2>
        <div class="categories-actions">
            <a href="{{ route('categories.create') }}" class="add-category-btn">
                <i class="fa fa-plus"></i>
                <span>افزودن دسته‌بندی جدید</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="cat-tree-wrapper">
        <div class="table-responsive">
            <table class="cat-tree-table">
                <thead>
                    <tr>
                        <th style="width:35px"></th>
                        <th>نام دسته‌بندی</th>
                        <th>نوع</th>
                        <th>کد</th>
                        <th>توضیحات</th>
                        <th>تصویر</th>
                        <th>تعداد محصولات</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        function renderCategoryRows($categories, $level = 0, $parentId = null) {
                            foreach($categories as $index => $category) {
                                $hasChildren = $category->children && $category->children->count() > 0;
                                $typeClass = $category->category_type == 'product' ? 'cat-type-prod' :
                                            ($category->category_type == 'service' ? 'cat-type-serv' : 'cat-type-pers');
                                $typeIcon = $category->category_type == 'product' ? 'box' :
                                           ($category->category_type == 'service' ? 'cogs' : 'user');
                                $typeText = $category->category_type == 'product' ? 'محصول' :
                                           ($category->category_type == 'service' ? 'خدمت' : 'شخص');

                                echo '<tr class="cat-tree-row" data-id="'.$category->id.'"
                                     data-parent="'.($category->parent_id ?: '').'"
                                     data-level="'.$level.'"
                                     style="--row-index:'.$index.'"
                                     '.($level > 0 ? ' hidden' : '').'>';

                                // Toggle Column
                                echo '<td class="cat-tree-toggle-cell">';
                                if($hasChildren) {
                                    echo '<button class="cat-tree-toggle" aria-expanded="false"
                                           data-toggle-id="'.$category->id.'"
                                           title="باز/بستن زیرشاخه‌ها">
                                           <i class="fa fa-caret-left"></i>
                                          </button>';
                                } else {
                                    echo str_repeat('<span class="cat-tree-indent"></span>', $level+1);
                                }
                                echo '</td>';

                                // Name Column
                                echo '<td class="cat-tree-name-cell">';
                                echo str_repeat('<span class="cat-tree-indent"></span>', $level);
                                echo '<span class="cat-name">'.e($category->name).'</span>';
                                echo '</td>';

                                // Type Column
                                echo '<td class="cat-tree-type-cell">';
                                echo '<span class="'.$typeClass.'">
                                        <i class="fa fa-'.$typeIcon.'"></i>
                                        <span>'.$typeText.'</span>
                                     </span>';
                                echo '</td>';

                                // Code Column
                                echo '<td class="cat-tree-code-cell">';
                                echo '<span class="cat-tree-badge">
                                        <i class="fa fa-hashtag"></i>
                                        <span>'.e($category->code).'</span>
                                     </span>';
                                echo '</td>';

                                // Description Column
                                echo '<td class="cat-tree-desc-cell">';
                                if($category->description) {
                                    echo '<div class="cat-tree-desc" title="'.e($category->description).'">
                                            '.e($category->description).'
                                         </div>';
                                }
                                echo '</td>';

                                // Image Column
                                echo '<td class="cat-tree-img-cell">';
                                if($category->image) {
                                    echo '<div class="cat-tree-img-wrapper">
                                            <img src="/storage/'.e($category->image).'"
                                                 class="cat-tree-img"
                                                 alt="'.$category->name.'"
                                                 loading="lazy">
                                          </div>';
                                }
                                echo '</td>';

                                // Products Count Column
                                echo '<td class="cat-tree-count-cell">';
                                echo '<span class="cat-tree-badge">
                                        <i class="fa fa-cubes"></i>
                                        <span>'.($category->products ? $category->products->count() : 0).'</span>
                                     </span>';
                                echo '</td>';

                                // Actions Column
                                echo '<td class="cat-tree-actions-cell">';
                                echo '<div class="cat-tree-actions">';
                                echo '<a href="'.route('categories.edit', $category->id).'"
                                      class="cat-tree-action-btn edit-btn"
                                      title="ویرایش '.$category->name.'">
                                      <i class="fa fa-edit"></i>
                                      <span>ویرایش</span>
                                     </a>';
                                echo '<form action="'.route('categories.destroy', $category->id).'"
                                      method="POST"
                                      class="d-inline delete-form"
                                      onsubmit="return confirmDelete(event, \''.$category->name.'\')">';
                                echo csrf_field();
                                echo method_field('DELETE');
                                echo '<button type="submit" class="cat-tree-action-btn delete-btn"
                                        title="حذف '.$category->name.'">
                                        <i class="fa fa-trash"></i>
                                        <span>حذف</span>
                                      </button>';
                                echo '</form>';
                                echo '</div>';
                                echo '</td>';
                                echo '</tr>';

                                if($hasChildren) {
                                    renderCategoryRows($category->children, $level + 1, $category->id);
                                }
                            }
                        }
                    @endphp

                    @php
                        renderCategoryRows($categories);
                    @endphp
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Cache DOM elements
    const table = document.querySelector('.cat-tree-table');
    const toggleButtons = document.querySelectorAll('.cat-tree-toggle');

    // Add event listeners
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', handleToggleClick);
        btn.addEventListener('keydown', handleToggleKeydown);
    });

    // Handle toggle button click
    function handleToggleClick(event) {
        const btn = event.currentTarget;
        const row = btn.closest('tr');
        const id = btn.getAttribute('data-toggle-id');
        const expanded = btn.getAttribute('aria-expanded') === 'true';

        // Toggle button state with animation
        btn.setAttribute('aria-expanded', !expanded);

        // Add loading state
        row.classList.add('loading');

        // Toggle children with animation after a small delay
        setTimeout(() => {
            toggleChildren(id, !expanded);
            row.classList.remove('loading');
        }, 150);
    }

    // Handle toggle button keyboard events
    function handleToggleKeydown(event) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            event.target.click();
        }
    }

    // Toggle children rows with animation
    function toggleChildren(parentId, show) {
        const childRows = document.querySelectorAll(`tr[data-parent="${parentId}"]`);

        childRows.forEach((row, index) => {
            // Set animation delay based on index
            row.style.setProperty('--row-index', index);

            if(show) {
                // Show animation
                row.style.opacity = '0';
                row.style.transform = 'translateY(-10px)';
                row.removeAttribute('hidden');

                // Trigger reflow
                row.offsetHeight;

                // Apply animation
                row.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            } else {
                // Hide animation
                row.style.transition = 'opacity 0.2s ease-out, transform 0.2s ease-out';
                row.style.opacity = '0';
                row.style.transform = 'translateY(-10px)';

                // Hide after animation
                setTimeout(() => {
                    row.setAttribute('hidden', 'hidden');
                    row.style.opacity = '';
                    row.style.transform = '';
                }, 200);
            }

            // Recursively handle nested children
            if(!show) {
                const id = row.getAttribute('data-id');
                toggleChildren(id, false);

                const btn = row.querySelector('.cat-tree-toggle');
                if(btn) btn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Delete confirmation
    window.confirmDelete = function(event, name) {
        event.preventDefault();

        const form = event.target;
        const row = form.closest('tr');

        if (confirm(`آیا از حذف دسته‌بندی "${name}" اطمینان دارید؟
این عمل قابل بازگشت نیست.`)) {
            // Add loading state
            row.classList.add('loading');

            // Submit the form
            setTimeout(() => {
                form.submit();
            }, 300);
        }

        return false;
    };

    // Add hover effect to parent row when child is hovered
    const treeRows = document.querySelectorAll('.cat-tree-row');
    treeRows.forEach(row => {
        row.addEventListener('mouseenter', () => {
            const parentId = row.getAttribute('data-parent');
            if (parentId) {
                const parentRow = document.querySelector(`tr[data-id="${parentId}"]`);
                if (parentRow) {
                    parentRow.classList.add('child-hovered');
                }
            }
        });

        row.addEventListener('mouseleave', () => {
            const parentId = row.getAttribute('data-parent');
            if (parentId) {
                const parentRow = document.querySelector(`tr[data-id="${parentId}"]`);
                if (parentRow) {
                    parentRow.classList.remove('child-hovered');
                }
            }
        });
    });

    // Optional: Add smooth scroll when opening deep nested categories
    document.querySelectorAll('.cat-tree-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            if(btn.getAttribute('aria-expanded') === 'true') {
                const row = btn.closest('tr');
                row.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    });
});
</script>
@endsection
