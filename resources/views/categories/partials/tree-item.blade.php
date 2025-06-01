<div @if(isset($isChild) && $isChild) data-is-child="true" @endif class="cat-tree-row" data-id="{{ $category->id }}" data-parent="{{ $category->parent_id ?? '' }}">
    {{-- تورفتگی و نشانگر درختی --}}
    <div class="cat-tree-indent-wrapper">
        @if(isset($level))
            @for($i = 0; $i < $level; $i++)
                <div class="cat-tree-indent">
                    <span class="cat-tree-indent-line"></span>
                </div>
            @endfor
        @endif
        @if($category->children && $category->children->count() > 0)
            <button class="cat-tree-toggle" aria-expanded="false" title="نمایش/پنهان‌سازی زیرشاخه‌ها">
                <i class="fas fa-chevron-left"></i>
            </button>
        @endif
    </div>

    {{-- اطلاعات اصلی دسته --}}
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

        {{-- نام و اطلاعات دسته --}}
        <div class="cat-tree-info">
            <div class="cat-tree-title">{{ $category->name }}</div>
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
                    <span class="cat-products-count">
                        <i class="fas fa-cubes"></i>
                        {{ $category->products->count() }}
                    </span>
                @endif
            </div>

            {{-- توضیحات --}}
            @if($category->description)
                <div class="cat-description" title="{{ $category->description }}">
                    {{ $category->description }}
                </div>
            @endif
        </div>

        {{-- دکمه‌های عملیات --}}
        <div class="cat-tree-actions">
            <a href="{{ route('categories.edit', $category->id) }}" class="cat-action-btn edit">
                <i class="fas fa-edit"></i>
                <span>ویرایش</span>
            </a>
            <form action="{{ route('categories.destroy', $category->id) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('آیا از حذف این دسته‌بندی اطمینان دارید؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="cat-action-btn delete">
                    <i class="fas fa-trash"></i>
                    <span>حذف</span>
                </button>
            </form>
        </div>
    </div>

    {{-- زیردسته‌ها --}}
    @if($category->children && $category->children->count() > 0)
        <div class="cat-tree-children" style="display: none;">
            @foreach($category->children as $child)
                @include('categories.partials.tree-item', [
                    'category' => $child,
                    'level' => ($level ?? 0) + 1,
                    'isChild' => true
                ])
            @endforeach
        </div>
    @endif
</div>

<style>
/* متغیرهای CSS */
:root {
    --indent-width: 2rem;
    --indent-line-width: 2px;
    --indent-line-color: #e2e8f0;
    --toggle-size: 1.5rem;
    --row-spacing: 0.75rem;
    --transition-speed: 0.3s;
    --primary-color: #2776d1;
    --primary-light: #eaf5ff;
    --success-color: #1cb08e;
    --warning-color: #c97e10;
    --danger-color: #dc3545;
    --text-color: #1a202c;
    --border-radius: 0.5rem;
}

/* ردیف درخت */
.cat-tree-row {
    position: relative;
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    margin-bottom: var(--row-spacing);
    background: white;
    border: 1px solid var(--indent-line-color);
    border-radius: var(--border-radius);
    transition: all var(--transition-speed) ease;
}

.cat-tree-row:hover {
    border-color: var(--primary-color);
    box-shadow: 0 4px 12px rgba(39, 118, 209, 0.1);
}

/* تورفتگی و خطوط */
.cat-tree-indent-wrapper {
    display: flex;
    align-items: center;
    margin-left: 1rem;
}

.cat-tree-indent {
    width: var(--indent-width);
    height: 100%;
    position: relative;
}

.cat-tree-indent-line {
    position: absolute;
    right: 50%;
    top: 0;
    bottom: 0;
    width: var(--indent-line-width);
    background: var(--indent-line-color);
}

/* دکمه باز/بسته کردن */
.cat-tree-toggle {
    width: var(--toggle-size);
    height: var(--toggle-size);
    border: var(--indent-line-width) solid var(--indent-line-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    color: var(--primary-color);
    cursor: pointer;
    transition: all var(--transition-speed) ease;
}

.cat-tree-toggle:hover {
    border-color: var(--primary-color);
    background: var(--primary-light);
}

.cat-tree-toggle i {
    font-size: 0.75rem;
    transition: transform var(--transition-speed) ease;
}

.cat-tree-toggle[aria-expanded="true"] i {
    transform: rotate(-90deg);
}

/* محتوای اصلی */
.cat-tree-content {
    flex: 1;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

/* تصویر */
.cat-tree-image {
    flex-shrink: 0;
}

.cat-img {
    width: 3rem;
    height: 3rem;
    border-radius: 0.5rem;
    object-fit: cover;
    border: 2px solid var(--indent-line-color);
    transition: all var(--transition-speed) ease;
}

.cat-img:hover {
    border-color: var(--primary-color);
    transform: scale(1.05);
}

.cat-img-placeholder {
    width: 3rem;
    height: 3rem;
    border-radius: 0.5rem;
    background: var(--primary-light);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
    font-size: 1.25rem;
}

/* اطلاعات */
.cat-tree-info {
    flex: 1;
    min-width: 0;
}

.cat-tree-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 0.5rem;
}

.cat-tree-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
}

/* نوع دسته */
.cat-type {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.cat-type.product {
    background: #dcfce7;
    color: var(--success-color);
}

.cat-type.service {
    background: var(--primary-light);
    color: var(--primary-color);
}

.cat-type.person {
    background: #fff7ed;
    color: var(--warning-color);
}

/* کد دسته */
.cat-code {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    background: #f1f5f9;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    color: #64748b;
    font-family: monospace;
}

/* تعداد محصولات */
.cat-products-count {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    background: var(--primary-light);
    color: var(--primary-color);
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

/* توضیحات */
.cat-description {
    color: #64748b;
    font-size: 0.875rem;
    max-width: 400px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* دکمه‌های عملیات */
.cat-tree-actions {
    display: flex;
    gap: 0.5rem;
    margin-right: auto;
}

.cat-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all var(--transition-speed) ease;
}

.cat-action-btn.edit {
    background: var(--primary-light);
    color: var(--primary-color);
}

.cat-action-btn.edit:hover {
    background: var(--primary-color);
    color: white;
}

.cat-action-btn.delete {
    background: #fee2e2;
    color: var(--danger-color);
}

.cat-action-btn.delete:hover {
    background: var(--danger-color);
    color: white;
}

/* زیردسته‌ها */
.cat-tree-children {
    width: 100%;
    padding-right: calc(var(--indent-width) + 1rem);
    margin-top: var(--row-spacing);
}

/* واکنش‌گرایی */
@media (max-width: 768px) {
    .cat-tree-content {
        flex-direction: column;
    }

    .cat-tree-actions {
        margin-top: 1rem;
        width: 100%;
    }

    .cat-action-btn {
        flex: 1;
        justify-content: center;
    }

    .cat-tree-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}

/* RTL تنظیمات */
[dir="rtl"] .cat-tree-indent-line {
    right: auto;
    left: 50%;
}

[dir="rtl"] .cat-tree-toggle i {
    transform: rotate(180deg);
}

[dir="rtl"] .cat-tree-toggle[aria-expanded="true"] i {
    transform: rotate(90deg);
}

[dir="rtl"] .cat-tree-children {
    padding-right: 0;
    padding-left: calc(var(--indent-width) + 1rem);
}
</style>
