<li class="cat-tree-item" data-level="{{ $level ?? 0 }}">
    {{-- کارت اصلی دسته --}}
    <div class="cat-tree-card {{ $category->category_type }}-card">
        {{-- محتوای اصلی --}}
        <div class="cat-tree-content">
            {{-- تصویر دسته --}}
            <div class="cat-tree-image">
                @if($category->image)
                    <img src="/storage/{{ $category->image }}" alt="{{ $category->name }}" class="cat-img" loading="lazy">
                @else
                    <div class="cat-img-placeholder">
                        <i class="fas fa-{{ $category->category_type === 'product' ? 'box' : ($category->category_type === 'service' ? 'cogs' : 'user') }}"></i>
                    </div>
                @endif
            </div>

            {{-- اطلاعات دسته --}}
            <div class="cat-tree-info">
                <h3 class="cat-tree-title">{{ $category->name }}</h3>

                <div class="cat-tree-meta">
                    {{-- نوع دسته --}}
                    <span class="cat-type {{ $category->category_type }}">
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

            @if($category->children && $category->children->count() > 0)
                <button type="button" class="cat-tree-toggle" aria-expanded="false" title="نمایش/پنهان‌سازی زیرشاخه‌ها">
                    <i class="fas fa-chevron-down"></i>
                </button>
            @endif
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
    /* رنگ‌های اصلی */
    --product-color: #10B981;
    --product-light: #D1FAE5;
    --product-border: #34D399;
    --product-hover: #059669;

    --service-color: #F59E0B;
    --service-light: #FEF3C7;
    --service-border: #FBBF24;
    --service-hover: #D97706;

    --person-color: #3B82F6;
    --person-light: #DBEAFE;
    --person-border: #60A5FA;
    --person-hover: #2563EB;

    /* رنگ‌های پایه */
    --color-bg: #ffffff;
    --color-text: #1e293b;
    --color-text-light: #64748b;
    --color-border: #e2e8f0;
    --color-danger: #dc2626;
    --color-danger-light: #fee2e2;

    /* سایه‌ها */
    --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);

    /* اندازه‌ها */
    --spacing-1: 0.25rem;
    --spacing-2: 0.5rem;
    --spacing-3: 0.75rem;
    --spacing-4: 1rem;
    --spacing-5: 1.25rem;
    --spacing-6: 1.5rem;

    /* شعاع گوشه‌ها */
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-full: 9999px;

    /* انیمیشن */
    --transition-speed: 300ms;
    --ease-in-out: cubic-bezier(0.4, 0, 0.2, 1);
}

/* === آیتم درخت === */
.cat-tree-item {
    list-style: none;
    position: relative;
    margin: var(--spacing-3) 0;
    padding-right: calc(var(--level, 0) * var(--spacing-6));
}

/* خط عمودی */
.cat-tree-item::before {
    content: '';
    position: absolute;
    top: 0;
    right: calc((var(--level, 0) - 1) * var(--spacing-6) + var(--spacing-4));
    width: 2px;
    height: 100%;
    background: var(--color-border);
}

/* خط افقی */
.cat-tree-item::after {
    content: '';
    position: absolute;
    top: var(--spacing-6);
    right: calc((var(--level, 0) - 1) * var(--spacing-6) + var(--spacing-4));
    width: var(--spacing-4);
    height: 2px;
    background: var(--color-border);
}

/* حذف خطوط برای سطح اول */
.cat-tree-item[data-level="0"]::before,
.cat-tree-item[data-level="0"]::after {
    display: none;
}

/* === کارت دسته === */
.cat-tree-card {
    position: relative;
    background: var(--color-bg);
    border-radius: var(--radius-lg);
    padding: var(--spacing-4);
    cursor: pointer;
    transition: all var(--transition-speed) var(--ease-in-out);
}

/* استایل‌های مخصوص هر نوع */
.cat-tree-card.product-card {
    border: 1px solid var(--product-border);
    background: linear-gradient(to left, var(--product-light) 0%, white 50%);
}

.cat-tree-card.service-card {
    border: 1px solid var(--service-border);
    background: linear-gradient(to left, var(--service-light) 0%, white 50%);
}

.cat-tree-card.person-card {
    border: 1px solid var(--person-border);
    background: linear-gradient(to left, var(--person-light) 0%, white 50%);
}

/* هاور کارت‌ها */
.cat-tree-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.cat-tree-card.product-card:hover {
    border-color: var(--product-color);
}

.cat-tree-card.service-card:hover {
    border-color: var(--service-color);
}

.cat-tree-card.person-card:hover {
    border-color: var(--person-color);
}

/* === محتوای کارت === */
.cat-tree-content {
    display: flex;
    align-items: center;
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
    transition: transform var(--transition-speed) var(--ease-in-out);
}

.cat-img {
    object-fit: cover;
}

.cat-img-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.product-card .cat-img-placeholder {
    background: var(--product-light);
    color: var(--product-color);
}

.service-card .cat-img-placeholder {
    background: var(--service-light);
    color: var(--service-color);
}

.person-card .cat-img-placeholder {
    background: var(--person-light);
    color: var(--person-color);
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
    flex-wrap: wrap;
    gap: var(--spacing-3);
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
    background: var(--product-light);
    color: var(--product-color);
}

.cat-type.service {
    background: var(--service-light);
    color: var(--service-color);
}

.cat-type.person {
    background: var(--person-light);
    color: var(--person-color);
}

/* === کد دسته === */
.cat-code {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-1);
    padding: var(--spacing-1) var(--spacing-2);
    background: rgba(0, 0, 0, 0.05);
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
    border-radius: var(--radius-full);
    font-size: 0.875rem;
    font-weight: 500;
}

.product-card .cat-products {
    background: var(--product-light);
    color: var(--product-color);
}

.service-card .cat-products {
    background: var(--service-light);
    color: var(--service-color);
}

.person-card .cat-products {
    background: var(--person-light);
    color: var(--person-color);
}

/* === توضیحات === */
.cat-desc {
    color: var(--color-text-light);
    font-size: 0.875rem;
    margin: var(--spacing-2) 0 0;
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
    transition: all var(--transition-speed) var(--ease-in-out);
}

.cat-btn.edit {
    background: var(--color-bg);
    border: 1px solid currentColor;
}

.cat-btn.delete {
    background: var(--color-bg);
    border: 1px solid var(--color-danger);
    color: var(--color-danger);
}

.product-card .cat-btn.edit {
    color: var(--product-color);
}

.service-card .cat-btn.edit {
    color: var(--service-color);
}

.person-card .cat-btn.edit {
    color: var(--person-color);
}

.cat-btn.edit:hover {
    color: white;
}

.product-card .cat-btn.edit:hover {
    background: var(--product-color);
}

.service-card .cat-btn.edit:hover {
    background: var(--service-color);
}

.person-card .cat-btn.edit:hover {
    background: var(--person-color);
}

.cat-btn.delete:hover {
    background: var(--color-danger);
    color: white;
}

/* === دکمه toggle === */
.cat-tree-toggle {
    position: absolute;
    left: var(--spacing-4);
    top: 50%;
    transform: translateY(-50%);
    width: 28px;
    height: 28px;
    border-radius: var(--radius-full);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all var(--transition-speed) var(--ease-in-out);
    background: white;
    box-shadow: var(--shadow-sm);
}

.product-card .cat-tree-toggle {
    color: var(--product-color);
    border: 2px solid var(--product-border);
}

.service-card .cat-tree-toggle {
    color: var(--service-color);
    border: 2px solid var(--service-border);
}

.person-card .cat-tree-toggle {
    color: var(--person-color);
    border: 2px solid var(--person-border);
}

.cat-tree-toggle:hover {
    transform: translateY(-50%) scale(1.1);
}

.cat-tree-toggle i {
    font-size: 0.875rem;
    transition: transform var(--transition-speed) var(--ease-in-out);
}

.cat-tree-toggle[aria-expanded="true"] i {
    transform: rotate(180deg);
}

/* === زیردسته‌ها === */
.cat-tree-children {
    list-style: none;
    padding: 0;
    margin: var(--spacing-4) 0 0 0;
    transition: all var(--transition-speed) var(--ease-in-out);
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
        align-items: flex-start;
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

    .cat-tree-toggle {
        top: var(--spacing-4);
        left: var(--spacing-4);
        transform: none;
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

[dir="rtl"] .cat-tree-item::before {
    right: auto;
    left: calc((var(--level, 0) - 1) * var(--spacing-6) + var(--spacing-4));
}

[dir="rtl"] .cat-tree-item::after {
    right: auto;
    left: calc((var(--level, 0) - 1) * var(--spacing-6) + var(--spacing-4));
}

[dir="rtl"] .cat-actions {
    margin-right: 0;
    margin-left: auto;
}

[dir="rtl"] .cat-tree-toggle {
    left: auto;
    right: var(--spacing-4);
}
</style>
