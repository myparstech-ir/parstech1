@extends('layouts.app')

@section('title', 'لیست دسته‌بندی‌ها')

@section('styles')
<style>
/* Base & Reset */
body {
    background: #f6fbfe;
    font-family: IRANSans, Vazirmatn, Tahoma, sans-serif;
    line-height: 1.6;
}

/* Variables */
:root {
    --primary: #2776d1;
    --primary-light: #eaf5ff;
    --primary-lighter: #f3f8ff;
    --primary-dark: #1e5aa0;
    --success: #1cb08e;
    --success-light: #d4f1e4;
    --warning: #c97e10;
    --warning-light: #ffe9c7;
    --danger: #dc3545;
    --danger-light: #ffeaea;
    --border-color: #e0e8fa;
    --shadow-sm: 0 2px 4px rgba(39, 118, 209, 0.1);
    --shadow-md: 0 4px 6px rgba(39, 118, 209, 0.15);
    --shadow-lg: 0 8px 24px rgba(39, 118, 209, 0.15);
    --radius-sm: 6px;
    --radius-md: 8px;
    --radius-lg: 12px;
    --radius-xl: 16px;
    --transition: all 0.3s ease;
}

/* Main Container */
.categories-wrapper {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 1rem;
}

/* Header */
.categories-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    gap: 1rem;
    flex-wrap: wrap;
}

.categories-title {
    font-size: 1.5rem;
    color: var(--primary);
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
}

.categories-title i {
    font-size: 1.25em;
    opacity: 0.9;
}

.btn-add-category {
    background: var(--success);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: var(--transition);
}

.btn-add-category:hover {
    background: #17966c;
    transform: translateY(-1px);
}

/* Tree Container */
.tree-container {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    padding: 1.5rem;
    overflow: hidden;
}

/* Tree List */
.tree-list {
    list-style: none;
    padding: 0;
    margin: 0;
    position: relative;
}

.tree-item {
    position: relative;
    padding-right: 2rem;
    margin-bottom: 0.5rem;
}

/* Tree Lines */
.tree-item::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 2px;
    height: 100%;
    background: var(--border-color);
}

.tree-item:last-child::before {
    height: 50%;
}

.tree-item::after {
    content: '';
    position: absolute;
    top: 50%;
    right: 0;
    width: 1.5rem;
    height: 2px;
    background: var(--border-color);
}

/* Category Card */
.category-card {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: var(--transition);
    position: relative;
}

.category-card:hover {
    background: var(--primary-lighter);
    border-color: var(--primary);
    box-shadow: var(--shadow-md);
}

/* Category Image */
.category-image {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-md);
    object-fit: cover;
    border: 2px solid var(--border-color);
    background: var(--primary-light);
    transition: var(--transition);
}

.category-card:hover .category-image {
    border-color: var(--primary);
    transform: scale(1.05);
}

/* Category Info */
.category-info {
    flex: 1;
    min-width: 0;
}

.category-name {
    font-weight: 700;
    color: var(--primary-dark);
    margin: 0 0 0.25rem;
    font-size: 1.1rem;
}

.category-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Category Type Badge */
.category-type {
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.category-type i {
    font-size: 0.9em;
}

.type-product {
    background: var(--success-light);
    color: var(--success);
}

.type-service {
    background: var(--primary-light);
    color: var(--primary);
}

.type-person {
    background: var(--warning-light);
    color: var(--warning);
}

/* Category Code */
.category-code {
    font-family: monospace;
    background: rgba(0, 0, 0, 0.05);
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
    font-size: 0.9rem;
}

/* Category Description */
.category-desc {
    color: #666;
    font-size: 0.95rem;
    margin: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 300px;
}

/* Products Count */
.products-count {
    background: var(--primary-light);
    color: var(--primary);
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

/* Action Buttons */
.category-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    padding: 0.5rem 1rem;
    border-radius: var(--radius-md);
    font-size: 0.95rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition);
}

.btn-edit {
    background: var(--primary-light);
    color: var(--primary);
}

.btn-edit:hover {
    background: var(--primary);
    color: white;
}

.btn-delete {
    background: var(--danger-light);
    color: var(--danger);
}

.btn-delete:hover {
    background: var(--danger);
    color: white;
}

/* Success Alert */
.alert {
    padding: 1rem;
    border-radius: var(--radius-lg);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.alert-success {
    background: var(--success-light);
    color: var(--success);
}

/* Subcategories Toggle */
.subcategories-toggle {
    position: absolute;
    right: -1.75rem;
    top: 50%;
    transform: translateY(-50%);
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: white;
    border: 2px solid var(--border-color);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    z-index: 2;
}

.subcategories-toggle:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.subcategories-toggle i {
    font-size: 0.8rem;
    transition: transform 0.3s ease;
}

.subcategories-toggle[aria-expanded="true"] i {
    transform: rotate(90deg);
}

/* Subcategories List */
.subcategories {
    margin-top: 1rem;
    margin-right: 1rem;
    display: none;
}

.subcategories.show {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #666;
}

.empty-state i {
    font-size: 3rem;
    color: var(--primary);
    opacity: 0.5;
    margin-bottom: 1rem;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .category-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .category-desc {
        max-width: 200px;
    }
}

@media (max-width: 768px) {
    .categories-header {
        flex-direction: column;
        align-items: stretch;
    }

    .btn-add-category {
        justify-content: center;
    }

    .category-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .category-actions {
        width: 100%;
        justify-content: stretch;
    }

    .btn-action {
        flex: 1;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .categories-wrapper {
        margin: 1rem auto;
    }

    .tree-container {
        padding: 1rem;
    }

    .category-image {
        width: 40px;
        height: 40px;
    }

    .category-name {
        font-size: 1rem;
    }

    .category-type,
    .category-code,
    .products-count {
        font-size: 0.85rem;
    }
}
</style>
@endsection

@section('content')
<div class="categories-wrapper">
    <div class="categories-header">
        <h1 class="categories-title">
            <i class="fas fa-sitemap"></i>
            <span>مدیریت دسته‌بندی‌ها</span>
        </h1>
        <a href="{{ route('categories.create') }}" class="btn-add-category">
            <i class="fas fa-plus"></i>
            <span>افزودن دسته‌بندی جدید</span>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="tree-container">
        @if(count($categories) > 0)
            <ul class="tree-list">
                @foreach($categories as $category)
                    @include('categories.partials.tree-item', ['category' => $category])
                @endforeach
            </ul>
        @else
            <div class="empty-state">
                <i class="fas fa-sitemap"></i>
                <p>هیچ دسته‌بندی‌ای یافت نشد.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cache DOM elements
    const toggleButtons = document.querySelectorAll('.subcategories-toggle');

    // Add event listeners to all toggle buttons
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', handleToggle);
        btn.addEventListener('keydown', handleKeyDown);
    });

    // Toggle subcategories
    function handleToggle(e) {
        const btn = e.currentTarget;
        const expanded = btn.getAttribute('aria-expanded') === 'true';
        const subcategories = btn.closest('.tree-item')
                               .querySelector('.subcategories');

        // Toggle aria-expanded
        btn.setAttribute('aria-expanded', !expanded);

        // Toggle subcategories visibility
        if (expanded) {
            subcategories.classList.remove('show');
            // Also collapse any expanded children
            const childToggles = subcategories.querySelectorAll('.subcategories-toggle[aria-expanded="true"]');
            childToggles.forEach(toggle => toggle.click());
        } else {
            subcategories.classList.add('show');
        }
    }

    // Handle keyboard navigation
    function handleKeyDown(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            e.target.click();
        }
    }

    // Optional: Add hover effect to parent when child is hovered
    const treeItems = document.querySelectorAll('.tree-item');
    treeItems.forEach(item => {
        item.addEventListener('mouseenter', () => {
            const parent = item.closest('ul').closest('.tree-item');
            if (parent) {
                parent.classList.add('child-hovered');
            }
        });

        item.addEventListener('mouseleave', () => {
            const parent = item.closest('ul').closest('.tree-item');
            if (parent) {
                parent.classList.remove('child-hovered');
            }
        });
    });
});
</script>
@endsection
