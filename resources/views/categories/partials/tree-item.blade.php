<li class="tree-item">
    <div class="category-card">
        @if($category->children && $category->children->count() > 0)
            <button class="subcategories-toggle" aria-expanded="false" title="نمایش/مخفی کردن زیرشاخه‌ها">
                <i class="fas fa-chevron-right"></i>
            </button>
        @endif

        @if($category->image)
            <img src="/storage/{{ $category->image }}" class="category-image" alt="{{ $category->name }}">
        @endif

        <div class="category-info">
            <h3 class="category-name">{{ $category->name }}</h3>
            <div class="category-meta">
                <span class="category-type {{ $category->category_type == 'product' ? 'type-product' : ($category->category_type == 'service' ? 'type-service' : 'type-person') }}">
                    <i class="fas fa-{{ $category->category_type == 'product' ? 'box' : ($category->category_type == 'service' ? 'cogs' : 'user') }}"></i>
                    <span>{{ $category->category_type == 'product' ? 'محصول' : ($category->category_type == 'service' ? 'خدمت' : 'شخص') }}</span>
                </span>

                <span class="category-code">{{ $category->code }}</span>

                @if($category->description)
                    <p class="category-desc" title="{{ $category->description }}">{{ $category->description }}</p>
                @endif

                <span class="products-count">
                    <i class="fas fa-cube"></i>
                    <span>{{ $category->products ? $category->products->count() : '0' }}</span>
                </span>
            </div>
        </div>

        <div class="category-actions">
            <a href="{{ route('categories.edit', $category->id) }}" class="btn-action btn-edit">
                <i class="fas fa-edit"></i>
                <span>ویرایش</span>
            </a>
            <form action="{{ route('categories.destroy', $category->id) }}"
                  method="POST"
                  style="display: inline"
                  onsubmit="return confirm('آیا از حذف این دسته‌بندی اطمینان دارید؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action btn-delete">
                    <i class="fas fa-trash"></i>
                    <span>حذف</span>
                </button>
            </form>
        </div>
    </div>

    @if($category->children && $category->children->count() > 0)
        <ul class="tree-list subcategories">
            @foreach($category->children as $child)
                @include('categories.partials.tree-item', ['category' => $child])
            @endforeach
        </ul>
    @endif
</li>
