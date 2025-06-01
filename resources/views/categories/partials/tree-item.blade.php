<li class="cat-tree-item" data-level="{{ $level ?? 0 }}">
    {{-- کارت اصلی دسته --}}
    <div class="cat-tree-card">
        {{-- دکمه باز/بسته کردن --}}
        @if($category->children && $category->children->count() > 0)
            <button type="button" class="cat-tree-toggle" aria-expanded="false" title="نمایش/پنهان‌سازی زیرشاخه‌ها">
                <i class="fas fa-chevron-down"></i>
            </button>
        @endif

        {{-- محتوای اصلی --}}
        <div class="cat-tree-content">
            {{-- تصویر دسته --}}
            <div class="cat-tree-image">
                @if($category->image)
                    <img src="/storage/{{ $category->image }}" alt="{{ $category->name }}" class="cat-img" loading="lazy">
                @else
                    <div class="cat-img-placeholder">
                        <i class="fas fa-folder"></i>
                    </div>
                @endif
            </div>

            {{-- اطلاعات دسته --}}
            <div class="cat-tree-info">
                <h3 class="cat-tree-title">{{ $category->name }}</h3>

                <div class="cat-tree-meta">
                    {{-- نوع دسته --}}
                    <span class="cat-type {{ $category->category_type === 'product' ? 'product' : ($category->category_type === 'service' ? 'service' : 'person') }}">
                        <i class="fas fa-{{ $category->category_type === 'product' ? 'box' : ($category->category_type === 'service' ? 'cogs' : 'user') }}"></i>
                        <span>{{ $category->category_type === 'product' ? 'محصول' : ($category->category_type === 'service' ? 'خدمت' : 'شخص') }}</span>
                    </span>

                    {{-- کد دسته --}}
                    @if($category->code)
                        <span class="cat-code">
                            <i class="fas fa-hashtag"></i>
                            {{ $category->code }}
                        </span>
                    @endif

                    {{-- تعداد محصولات --}}
                    @if($category->products)
                        <span class="cat-products">
                            <i class="fas fa-cubes"></i>
                            {{ $category->products->count() }}
                        </span>
                    @endif
                </div>

                {{-- توضیحات --}}
                @if($category->description)
                    <p class="cat-desc" title="{{ $category->description }}">{{ $category->description }}</p>
                @endif
            </div>

            {{-- دکمه‌های عملیات --}}
            <div class="cat-actions">
                <a href="{{ route('categories.edit', $category->id) }}" class="cat-btn edit">
                    <i class="fas fa-edit"></i>
                    <span>ویرایش</span>
                </a>
                <form action="{{ route('categories.destroy', $category->id) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('آیا از حذف این دسته‌بندی اطمینان دارید؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="cat-btn delete">
                        <i class="fas fa-trash"></i>
                        <span>حذف</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- زیردسته‌ها --}}
    @if($category->children && $category->children->count() > 0)
        <ul class="cat-tree-children" style="display: none;">
            @foreach($category->children as $child)
                @include('categories.partials.tree-item', [
                    'category' => $child,
                    'level' => ($level ?? 0) + 1
                ])
            @endforeach
        </ul>
    @endif
</li>

<style>
/* === متغیرها === */
:root {
    /* رنگ‌ها */
    --color-bg: #ffffff;
    --color-bg-hover: #f8fafc;
    --color-border: #e2e8f0;
    --color-border-hover: #2776d1;
    --color-text: #1e293b;
    --color-text-light: #64748b;

    --color-primary: #2776d1;
    --color-primary-light: #eaf5ff;
    --color-primary-hover: #1e5aa0;

    --color-success: #1cb08e;
    --color-success-light: #dcfce7;
    --color-success-hover: #15926f;

    --color-warning: #c97e10;
    --color-warning-light: #fff7ed;
    --color-warning-hover: #a66a0d;

    --color-danger: #dc3545;
    --color-danger-light: #fee2e2;
    --color-danger-hover: #b91c1c;

    /* سایه‌ها */
    --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px rgba(39, 118, 209, 0.1);
    --shadow-lg: 0 10px 15px rgba(39, 118, 209, 0.1);

    /* گردی گوشه‌ها */
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-full: 9999px;

    /* اندازه‌ها */
    --spacing-1: 0.25rem;
    --spacing-2: 0.5rem;
    --spacing-3: 0.75rem;
    --spacing-4: 1rem;
    --spacing-5: 1.25rem;
    --spacing-6: 1.5rem;

    /* انیمیشن */
    --transition-fast: 150ms;
    --transition-normal: 200ms;
    --transition-slow: 300ms;
    --ease-in-out: cubic-bezier(0.4, 0, 0.2, 1);
    --ease-out: cubic-bezier(0, 0, 0.2, 1);
    --ease-in: cubic-bezier(0.4, 0, 1, 1);
}

/* === آیتم درخت === */
.cat-tree-item {
    position: relative;
    margin-bottom: var(--spacing-4);
    padding-right: calc(var(--level, 0) * var(--spacing-6));
}

/* خطوط عمودی */
.cat-tree-item:before {
    content: '';
    position: absolute;
    top: 0;
    right: calc((var(--level, 0) - 1) * var(--spacing-6) + var(--spacing-4));
    width: 2px;
    height: 100%;
    background-color: var(--color-border);
    z-index: 1;
}

/* خط افقی */
.cat-tree-item:after {
    content: '';
    position: absolute;
    top: var(--spacing-6);
    right: calc((var(--level, 0) - 1) * var(--spacing-6) + var(--spacing-4));
    width: var(--spacing-4);
    height: 2px;
    background-color: var(--color-border);
}

/* حذف خطوط برای سطح اول */
.cat-tree-item[data-level="0"]:before,
.cat-tree-item[data-level="0"]:after {
    display: none;
}

/* === کارت دسته === */
.cat-tree-card {
    position: relative;
    display: flex;
    background-color: var(--color-bg);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    padding: var(--spacing-4);
    transition: all var(--transition-normal) var(--ease-out);
}

.cat-tree-card:hover {
    border-color: var(--color-border-hover);
    background-color: var(--color-bg-hover);
    box-shadow: var(--shadow-md);
    transform: translateY(-1px);
}

/* === دکمه‌ی toggle === */
.cat-tree-toggle {
    position: absolute;
    top: 50%;
    right: 0;
    transform: translate(50%, -50%);
    width: 24px;
    height: 24px;
    border: 2px solid var(--color-border);
    border-radius: var(--radius-full);
    background: var(--color-bg);
    color: var(--color-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all var(--transition-normal) var(--ease-out);
    z-index: 2;
}

.cat-tree-toggle:hover {
    border-color: var(--color-primary);
    background: var(--color-primary-light);
    color: var(--color-primary-hover);
}

.cat-tree-toggle i {
    font-size: 0.75rem;
    transition: transform var(--transition-normal) var(--ease-in-out);
}

.cat-tree-toggle[aria-expanded="true"] i {
    transform: rotate(180deg);
}

/* === محتوای اصلی === */
.cat-tree-content {
    flex: 1;
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-4);
}

/* === تصویر === */
.cat-tree-image {
    flex-shrink: 0;
}

.cat-img,
.cat-img-placeholder {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-md);
    object-fit: cover;
    border: 2px solid var(--color-border);
    transition: all var(--transition-normal) var(--ease-out);
}

.cat-img:hover {
    border-color: var(--color-primary);
    transform: scale(1.05);
}

.cat-img-placeholder {
    background: var(--color-primary-light);
    color: var(--color-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

/* === اطلاعات === */
.cat-tree-info {
    flex: 1;
    min-width: 0;
}

.cat-tree-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--color-text);
    margin: 0 0 var(--spacing-2);
}

.cat-tree-meta {
    display: flex;
    align-items: center;
    gap: var(--spacing-3);
    flex-wrap: wrap;
    margin-bottom: var(--spacing-2);
}

/* === نوع دسته === */
.cat-type {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-2);
    padding: var(--spacing-1) var(--spacing-3);
    border-radius: var(--radius-full);
    font-size: 0.875rem;
    font-weight: 500;
}

.cat-type.product {
    background: var(--color-success-light);
    color: var(--color-success);
}

.cat-type.service {
    background: var(--color-primary-light);
    color: var(--color-primary);
}

.cat-type.person {
    background: var(--color-warning-light);
    color: var(--color-warning);
}

/* === کد دسته === */
.cat-code {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-1);
    padding: var(--spacing-1) var(--spacing-2);
    background: var(--color-bg-hover);
    border-radius: var(--radius-sm);
    font-family: monospace;
    font-size: 0.875rem;
    color: var(--color-text-light);
}

/* === تعداد محصولات === */
.cat-products {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-2);
    padding: var(--spacing-1) var(--spacing-3);
    background: var(--color-primary-light);
    color: var(--color-primary);
    border-radius: var(--radius-full);
    font-size: 0.875rem;
    font-weight: 500;
}

/* === توضیحات === */
.cat-desc {
    color: var(--color-text-light);
    font-size: 0.875rem;
    margin: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 400px;
}

/* === دکمه‌های عملیات === */
.cat-actions {
    display: flex;
    gap: var(--spacing-2);
    margin-right: auto;
}

.cat-btn {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-2);
    padding: var(--spacing-2) var(--spacing-4);
    border: none;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all var(--transition-normal) var(--ease-out);
}

.cat-btn.edit {
    background: var(--color-primary-light);
    color: var(--color-primary);
}

.cat-btn.edit:hover {
    background: var(--color-primary);
    color: var(--color-bg);
}

.cat-btn.delete {
    background: var(--color-danger-light);
    color: var(--color-danger);
}

.cat-btn.delete:hover {
    background: var(--color-danger);
    color: var(--color-bg);
}

/* === زیردسته‌ها === */
.cat-tree-children {
    margin-top: var(--spacing-4);
    transition: all var(--transition-normal) var(--ease-in-out);
}

.cat-tree-children[style*="display: none"] {
    opacity: 0;
    transform: translateY(-10px);
}

.cat-tree-children:not([style*="display: none"]) {
    opacity: 1;
    transform: none;
}

/* === واکنش‌گرایی === */
@media (max-width: 1024px) {
    .cat-desc {
        max-width: 300px;
    }
}

@media (max-width: 768px) {
    .cat-tree-content {
        flex-direction: column;
        gap: var(--spacing-3);
    }

    .cat-tree-meta {
        flex-direction: column;
        align-items: flex-start;
    }

    .cat-desc {
        max-width: 100%;
    }

    .cat-actions {
        width: 100%;
        margin-top: var(--spacing-3);
    }

    .cat-btn {
        flex: 1;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .cat-tree-card {
        padding: var(--spacing-3);
    }

    .cat-img,
    .cat-img-placeholder {
        width: 40px;
        height: 40px;
    }

    .cat-tree-title {
        font-size: 1rem;
    }
}

/* === RTL Fixes === */
[dir="rtl"] .cat-tree-item {
    padding-right: 0;
    padding-left: calc(var(--level, 0) * var(--spacing-6));
}

[dir="rtl"] .cat-tree-item:before {
    right: auto;
    left: calc((var(--level, 0) - 1) * var(--spacing-6) + var(--spacing-4));
}

[dir="rtl"] .cat-tree-item:after {
    right: auto;
    left: calc((var(--level, 0) - 1) * var(--spacing-6) + var(--spacing-4));
}

[dir="rtl"] .cat-tree-toggle {
    right: auto;
    left: 0;
    transform: translate(-50%, -50%);
}

[dir="rtl"] .cat-actions {
    margin-right: 0;
    margin-left: auto;
}
</style>
