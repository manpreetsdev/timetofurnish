@php
    $children = $category->childrenCategories;
    $hasChildren = $children->count() > 0;
    $checkboxId = 'pci_cat_' . $ruleIndex . '_' . $category->id;
    $isChecked = in_array($category->id, $selectedIds);
@endphp

<div class="pci-category-row d-flex align-items-center mb-2" style="padding-left: {{ $level * 20 }}px;">
    @if ($hasChildren)
        <button
            type="button"
            class="btn btn-link p-0 mr-2 pci-category-toggle"
            data-category-id="{{ $category->id }}"
            aria-expanded="false"
            style="width: 22px; line-height: 1; font-size: 20px; text-decoration: none; color: #222;"
        >+</button>
    @else
        <span class="mr-2" style="width: 22px; display: inline-block;"></span>
    @endif
    <input
        type="checkbox"
        id="{{ $checkboxId }}"
        name="badges[{{ $ruleIndex }}][category_ids][]"
        value="{{ $category->id }}"
        class="pci-category-checkbox"
        data-category-id="{{ $category->id }}"
        data-has-children="{{ $hasChildren ? '1' : '0' }}"
        {{ $isChecked ? 'checked' : '' }}
    />
    <label for="{{ $checkboxId }}" class="mb-0 ml-2 cursor-pointer">
        {{ $category->getTranslation('name') }}
    </label>
</div>

@if ($hasChildren)
    <div class="pci-category-children" data-parent-id="{{ $category->id }}" style="display: none;">
        @foreach ($children as $childCategory)
            @include('backend.website_settings.partials.product_category_info_category_tree', [
                'category' => $childCategory,
                'selectedIds' => $selectedIds,
                'level' => $level + 1,
                'ruleIndex' => $ruleIndex,
            ])
        @endforeach
    </div>
@endif
