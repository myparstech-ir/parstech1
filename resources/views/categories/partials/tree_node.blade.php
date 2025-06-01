<li data-id="{{ $category->id }}" data-parent="{{ $category->parent_id ?? '' }}" data-level="{{ $level }}" data-haschildren="{{ $category->children && count($category->children) > 0 ? 'true' : 'false' }}" style="--level: {{ $level }}">
    <div class="cat-tree-row">
        @if(isset($category->children) && count($category->children))
            <button class="cat-tree-expander" aria-expanded="true" title="باز/بستن زیرشاخه‌ها" onclick="toggleChild(this)">
                <i class="fa fa-caret-left"></i>
            </button>
        @else
            <button class="cat-tree-expander" aria-expanded="false" hidden></button>
        @endif
        @if($category->image)
            <img src="/storage/{{ $category->image }}" class="cat-tree-img" alt="">
        @endif
        <span class="cat-tree-title">{{ $category->name }}</span>
        <span class="cat-tree-label" category-type="{{ $category->category_type }}">
            {{ $category->category_type === 'product' ? 'محصول' : ($category->category_type === 'service' ? 'خدمت' : ($category->category_type === 'person' ? 'شخص' : 'سایر')) }}
        </span>
        @if($category->code)
            <span class="cat-tree-code">{{ $category->code }}</span>
        @endif
        @if($category->description)
            <span class="cat-tree-desc">{{ $category->description }}</span>
        @endif
        @if(isset($category->products) && count($category->products))
            <span class="cat-tree-count">{{ count($category->products) }}</span>
        @endif
        <div class="cat-tree-actions">
            <a href="{{ route('categories.edit', $category->id) }}" class="cat-tree-action-btn"><i class="fa fa-edit ms-1"></i>ویرایش</a>
            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('آیا مطمئن هستید؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="cat-tree-action-btn danger"><i class="fa fa-trash ms-1"></i>حذف</button>
            </form>
        </div>
    </div>
    @if(isset($category->children) && count($category->children))
        <ul class="cat-tree">
            @foreach($category->children as $child)
                @include('categories.partials.tree_node', ['category' => $child, 'level' => $level + 1])
            @endforeach
        </ul>
    @endif
</li>
