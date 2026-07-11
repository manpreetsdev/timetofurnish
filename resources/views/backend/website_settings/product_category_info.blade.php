@extends('backend.layouts.app')

@section('content')
<style>
    /* ... (styles remain unchanged) ... */
    .pci-rule-card {
        border: 1px solid #e4e5eb;
        border-radius: 12px;
        background: #fff;
        margin-bottom: 16px;
        overflow: visible;
    }
    .product-category-info-card {
        overflow: visible !important;
    }
    .pci-rule-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 16px;
        background: #f8f9fb;
        border-bottom: 1px solid #eef0f4;
    }
    .pci-rule-body {
        padding: 16px;
    }
    .pci-content-box {
        background: #fff8f2;
        border: 1px solid rgba(181, 122, 69, 0.25);
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 18px;
    }
    .pci-content-box-title {
        font-weight: 700;
        color: #b57a45;
        margin-bottom: 12px;
        font-size: 14px;
    }
    .pci-field-text,
    .pci-field-image {
        display: none;
    }
    .pci-field-text.is-visible,
    .pci-field-image.is-visible {
        display: block !important;
    }
    .pci-preview-box {
        border: 1px dashed #d7dbe7;
        border-radius: 12px;
        padding: 18px;
        background: #fff;
        min-height: 110px;
    }
    .pci-preview-title-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px 18px;
    }
    .pci-preview-title {
        font-size: 18px;
        font-weight: 700;
        color: #212529;
        margin: 0;
        flex: 1 1 200px;
    }
    .pci-category-box {
        max-height: 280px;
        overflow-y: auto;
        border: 1px solid #e4e5eb;
        border-radius: 10px;
        padding: 12px;
        background: #fafbfc;
    }
    .pci-category-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 10px;
    }
    .pci-selected-count {
        font-size: 12px;
        color: #6c757d;
        margin-top: 6px;
    }
    .pci-preview-badge-text {
        display: inline-flex;
        align-items: flex-start;
        gap: 10px;
        padding: 10px;
        border-radius: 4px;
        background: linear-gradient(135deg, #f8f3ed 0%, #ffffff 100%);
        border: 1px solid rgba(181, 122, 69, 0.28);
        color: #1a2744;
        font-size: 13px;
        font-weight: 600;
        max-width: 320px;
        line-height: 1.45;
        white-space: pre-line; /* Preserve line breaks and spacing in badge preview */
    }
    .pci-preview-badge-text p {
        margin: 0;
    }
    .pci-preview-badge-text p + p {
        margin-top: 4px;
    }
</style>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3">{{ translate('Product Category Info Badge') }}</h1>
            <p class="text-muted mb-0">{{ translate('Add a text line or image next to the product title. Select categories below — checking a parent selects all its subcategories.') }}</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('website.product-category-info.update') }}" method="POST" id="pci-form">
            @csrf
            <div class="card product-category-info-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">{{ translate('Category Rules') }}</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="pci-add-rule">
                        <i class="las la-plus"></i> {{ translate('Add Rule') }}
                    </button>
                </div>
                <div class="card-body">
                    <div id="pci-rules-container"></div>
                    <div id="pci-empty-state" class="text-center text-muted py-5">
                        <i class="las la-tags la-3x mb-3 d-block opacity-50"></i>
                        {{ translate('No rules yet. Click Add Rule to get started.') }}
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">{{ translate('Save Settings') }}</button>
                </div>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ translate('Frontend Preview') }}</h6>
            </div>
            <div class="card-body">
                <div class="pci-preview-box mb-3" id="pci-live-preview">
                    <div class="pci-preview-title-row">
                        <h2 class="pci-preview-title">{{ translate('Sample Product Title') }}</h2>
                        <span class="text-muted fs-13">{{ translate('Badge appears here') }}</span>
                    </div>
                </div>
                <p class="text-muted fs-13 mb-0">
                    {{ translate('Matched products show this badge beside the title on the product details page.') }}
                </p>
            </div>
        </div>
    </div>
</div>

<template id="pci-category-tree-template">
    <div class="pci-category-actions">
        <button type="button" class="btn btn-xs btn-soft-primary pci-select-all">{{ translate('Select All') }}</button>
        <button type="button" class="btn btn-xs btn-soft-secondary pci-clear-all">{{ translate('Clear All') }}</button>
        <button type="button" class="btn btn-xs btn-soft-info pci-expand-all">{{ translate('Expand All') }}</button>
    </div>
    <div class="pci-category-box">
        @foreach ($categories as $category)
            @include('backend.website_settings.partials.product_category_info_category_tree', [
                'category' => $category,
                'selectedIds' => [],
                'level' => 0,
                'ruleIndex' => '__INDEX__',
            ])
        @endforeach
    </div>
</template>


<template id="pci-rule-template">
    <div class="pci-rule-card" data-rule-index="__INDEX__">
        <div class="pci-rule-header">
            <strong>{{ translate('Rule') }} <span class="pci-rule-number">__NUMBER__</span></strong>
            <button type="button" class="btn btn-sm btn-soft-danger pci-remove-rule">
                <i class="las la-trash"></i> {{ translate('Remove') }}
            </button>
        </div>
        <div class="pci-rule-body">
            <div class="pci-content-box">
                <div class="pci-content-box-title">{{ translate('Badge Content') }}</div>

                <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{ translate('Display Type') }}</label>
                    <div class="col-md-9">
                        <select class="form-control pci-display-type" name="badges[__INDEX__][type]">
                            <option value="text">{{ translate('Text Line') }}</option>
                            <option value="image">{{ translate('Image') }}</option>
                        </select>
                    </div>
                </div>

                <div class="pci-field-text is-visible">
                    <div class="form-group row mb-0">
                        <label class="col-md-3 col-from-label">{{ translate('Text') }} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <!-- Remove whitespace between the <textarea> tags to avoid default unwanted newlines -->
                            <textarea
                                class="form-control aiz-text-editor pci-text pci-text-editor"
                                name="badges[__INDEX__][text]"
                                rows="4"
                                data-buttons='[["font", ["bold", "italic", "underline", "clear"]],["para", ["paragraph"]],["view", ["undo", "redo"]]]'
                                data-min-height="120"
                                placeholder="{{ translate('e.g. Fast Delivery') }}"></textarea>
                            <small class="text-muted">{{ translate('Press Enter for a new line. Line breaks and spacing will show the same on the product page.') }}</small>
                        </div>
                    </div>
                </div>

                <div class="pci-field-image">
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{ translate('Image') }} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="badges[__INDEX__][image_id]" class="selected-files pci-image-id">
                            </div>
                            <div class="file-preview"></div>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <label class="col-md-3 col-from-label">{{ translate('Image Width') }}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control pci-image-width" name="badges[__INDEX__][image_width]" value="120px" placeholder="120px">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-from-label">{{ translate('Enabled') }}</label>
                <div class="col-md-9">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="hidden" name="badges[__INDEX__][enabled]" value="0">
                        <input type="checkbox" name="badges[__INDEX__][enabled]" value="1" checked class="pci-enabled">
                        <span></span>
                    </label>
                </div>
            </div>

            <div class="form-group row mb-0">
                <label class="col-md-3 col-from-label">{{ translate('Categories') }} <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <div class="pci-category-tree-slot"></div>
                    <div class="pci-selected-count">0 {{ translate('categories selected') }}</div>
                    <small class="text-muted d-block mt-1">{{ translate('Tick a parent category to auto-select all subcategories.') }}</small>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@section('script')
<script>
    (function () {
        const existingBadges = @json($badges);
        const container = document.getElementById('pci-rules-container');
        const emptyState = document.getElementById('pci-empty-state');
        const ruleTemplate = document.getElementById('pci-rule-template').innerHTML;
        const categoryTreeTemplate = document.getElementById('pci-category-tree-template').innerHTML;
        let ruleIndex = 0;
        let activePreviewCard = null;

        function toggleEmptyState() {
            emptyState.style.display = container.children.length ? 'none' : 'block';
        }

        function updateSelectedCount(card) {
            const count = card.querySelectorAll('.pci-category-checkbox:checked').length;
            const label = card.querySelector('.pci-selected-count');
            if (label) {
                label.textContent = count + ' {{ translate('categories selected') }}';
            }
        }

        function getDescendantCheckboxes(card, parentId) {
            const descendants = [];
            const childrenWrap = card.querySelector('.pci-category-children[data-parent-id="' + parentId + '"]');
            if (!childrenWrap) {
                return descendants;
            }
            childrenWrap.querySelectorAll('.pci-category-checkbox').forEach(function (cb) {
                descendants.push(cb);
            });
            return descendants;
        }

        function expandNode(card, parentId) {
            const childrenWrap = card.querySelector('.pci-category-children[data-parent-id="' + parentId + '"]');
            const toggleBtn = card.querySelector('.pci-category-toggle[data-category-id="' + parentId + '"]');
            if (childrenWrap) {
                childrenWrap.style.display = 'block';
            }
            if (toggleBtn) {
                toggleBtn.setAttribute('aria-expanded', 'true');
                toggleBtn.textContent = '−';
            }
        }

        function setCheckboxAndDescendants(card, checkbox, checked) {
            checkbox.checked = checked;
            const parentId = checkbox.value;
            getDescendantCheckboxes(card, parentId).forEach(function (childCb) {
                childCb.checked = checked;
            });
            if (checked) {
                expandNode(card, parentId);
            }
        }

        function bindCategoryTree(card) {
            card.querySelectorAll('.pci-category-toggle').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const parentId = btn.getAttribute('data-category-id');
                    const children = card.querySelector('.pci-category-children[data-parent-id="' + parentId + '"]');
                    if (!children) {
                        return;
                    }
                    const expanded = btn.getAttribute('aria-expanded') === 'true';
                    children.style.display = expanded ? 'none' : 'block';
                    btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                    btn.textContent = expanded ? '+' : '−';
                });
            });

            card.querySelectorAll('.pci-category-checkbox').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    setCheckboxAndDescendants(card, checkbox, checkbox.checked);
                    updateSelectedCount(card);
                    if (activePreviewCard === card) {
                        updatePreview(card);
                    }
                });
            });

            const selectAllBtn = card.querySelector('.pci-select-all');
            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', function () {
                    card.querySelectorAll('.pci-category-checkbox').forEach(function (cb) {
                        cb.checked = true;
                    });
                    card.querySelectorAll('.pci-category-children').forEach(function (wrap) {
                        wrap.style.display = 'block';
                    });
                    card.querySelectorAll('.pci-category-toggle').forEach(function (btn) {
                        btn.setAttribute('aria-expanded', 'true');
                        btn.textContent = '−';
                    });
                    updateSelectedCount(card);
                });
            }

            const clearAllBtn = card.querySelector('.pci-clear-all');
            if (clearAllBtn) {
                clearAllBtn.addEventListener('click', function () {
                    card.querySelectorAll('.pci-category-checkbox').forEach(function (cb) {
                        cb.checked = false;
                    });
                    updateSelectedCount(card);
                });
            }

            const expandAllBtn = card.querySelector('.pci-expand-all');
            if (expandAllBtn) {
                expandAllBtn.addEventListener('click', function () {
                    card.querySelectorAll('.pci-category-children').forEach(function (wrap) {
                        wrap.style.display = 'block';
                    });
                    card.querySelectorAll('.pci-category-toggle').forEach(function (btn) {
                        btn.setAttribute('aria-expanded', 'true');
                        btn.textContent = '−';
                    });
                });
            }

            updateSelectedCount(card);
        }

        function toggleTypeFields(card) {
            const type = card.querySelector('.pci-display-type').value;
            card.querySelector('.pci-field-text').classList.toggle('is-visible', type === 'text');
            card.querySelector('.pci-field-image').classList.toggle('is-visible', type === 'image');
            if (activePreviewCard === card) {
                updatePreview(card);
            }
        }

        function buildBadgeHtml(card) {
            const type = card.querySelector('.pci-display-type').value;
            const enabled = card.querySelector('.pci-enabled').checked;

            if (!enabled) {
                return '<span class="text-muted fs-13">{{ translate('Rule is disabled') }}</span>';
            }

            if (type === 'image') {
                const img = card.querySelector('.file-preview img');
                const width = card.querySelector('.pci-image-width').value || '120px';
                if (img) {
                    return '<img src="' + img.src + '" alt="" style="width:' + width + '; max-height:88px; object-fit:contain;">';
                }
                return '<span class="text-muted fs-13">{{ translate('Upload an image to preview') }}</span>';
            }

            // Use .value, do not trim (to exactly preserve all whitespace and line breaks)
            const textarea = card.querySelector('.pci-text');
            let text = textarea ? textarea.value : '';
            if (!text) {
                return '<span class="text-muted fs-13">{{ translate('Enter text to preview') }}</span>';
            }

            // Escape HTML to prevent XSS, display as plain text (pre-line in CSS will handle spaces/newlines)
            function escapeHtml(str) {
                return str.replace(/[&<>"']/g, function (c) {
                    return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
                });
            }

            return '<span class="pci-preview-badge-text">' + escapeHtml(text) + '</span>';
        }

        function updatePreview(card) {
            activePreviewCard = card || activePreviewCard;
            const preview = document.getElementById('pci-live-preview');

            if (!activePreviewCard) {
                preview.innerHTML = '<div class="pci-preview-title-row"><h2 class="pci-preview-title">{{ translate('Sample Product Title') }}</h2><span class="text-muted fs-13">{{ translate('Badge appears here') }}</span></div>';
                return;
            }

            preview.innerHTML = '<div class="pci-preview-title-row"><h2 class="pci-preview-title">{{ translate('Sample Product Title') }}</h2>' + buildBadgeHtml(activePreviewCard) + '</div>';
        }

        function bindCardEvents(card) {
            card.addEventListener('click', function () {
                activePreviewCard = card;
                updatePreview(card);
            });

            card.querySelector('.pci-remove-rule').addEventListener('click', function () {
                const wasActive = activePreviewCard === card;
                card.remove();
                renumberRules();
                toggleEmptyState();
                if (wasActive) {
                    activePreviewCard = container.querySelector('.pci-rule-card');
                    updatePreview(activePreviewCard);
                }
            });

            card.querySelector('.pci-display-type').addEventListener('change', function () {
                toggleTypeFields(card);
            });

            card.querySelector('.pci-enabled').addEventListener('change', function () {
                if (activePreviewCard === card) {
                    updatePreview(card);
                }
            });
            card.querySelector('.pci-text').addEventListener('input', function () {
                if (activePreviewCard === card) {
                    updatePreview(card);
                }
            });
            card.querySelector('.pci-image-width').addEventListener('input', function () {
                if (activePreviewCard === card) {
                    updatePreview(card);
                }
            });

            const previewObserver = new MutationObserver(function () {
                if (activePreviewCard === card) {
                    updatePreview(card);
                }
            });
            const filePreview = card.querySelector('.file-preview');
            if (filePreview) {
                previewObserver.observe(filePreview, { childList: true, subtree: true });
            }

            bindCategoryTree(card);
        }

        function renumberRules() {
            container.querySelectorAll('.pci-rule-card').forEach(function (card, index) {
                card.querySelector('.pci-rule-number').textContent = index + 1;
            });
        }

        function setSelectedCategories(card, categoryIds) {
            const ids = (categoryIds || []).map(String);
            card.querySelectorAll('.pci-category-checkbox').forEach(function (checkbox) {
                checkbox.checked = ids.includes(String(checkbox.value));
            });

            card.querySelectorAll('.pci-category-checkbox:checked').forEach(function (checkbox) {
                expandNode(card, checkbox.value);
            });

            card.querySelectorAll('.pci-category-children').forEach(function (wrap) {
                const hasChecked = wrap.querySelector('.pci-category-checkbox:checked');
                if (hasChecked) {
                    wrap.style.display = 'block';
                    const parentId = wrap.getAttribute('data-parent-id');
                    const toggleBtn = card.querySelector('.pci-category-toggle[data-category-id="' + parentId + '"]');
                    if (toggleBtn) {
                        toggleBtn.setAttribute('aria-expanded', 'true');
                        toggleBtn.textContent = '−';
                    }
                }
            });

            updateSelectedCount(card);
        }

        function addRule(data) {
            const index = ruleIndex++;
            const html = ruleTemplate
                .replace(/__INDEX__/g, index)
                .replace(/__NUMBER__/g, container.children.length + 1);

            container.insertAdjacentHTML('beforeend', html);
            const card = container.lastElementChild;

            const treeHtml = categoryTreeTemplate.replace(/__INDEX__/g, index);
            card.querySelector('.pci-category-tree-slot').innerHTML = treeHtml;

            if (data) {
                setSelectedCategories(card, data.category_ids || (data.category_id ? [data.category_id] : []));
                card.querySelector('.pci-enabled').checked = data.enabled === true || data.enabled === 1 || data.enabled === '1';
                card.querySelector('.pci-display-type').value = data.type || 'text';
                // Here we SET EXACT value, as-is, including all spaces and line breaks
                card.querySelector('.pci-text').value = data.text || '';
                card.querySelector('.pci-image-id').value = data.image_id || '';
                card.querySelector('.pci-image-width').value = data.image_width || '120px';
            }

            if (typeof AIZ !== 'undefined' && AIZ.uploader && AIZ.uploader.previewGenerate) {
                AIZ.uploader.previewGenerate();
            }

            bindCardEvents(card);
            toggleTypeFields(card);
            toggleEmptyState();

            if (!activePreviewCard) {
                activePreviewCard = card;
            }
            updatePreview(activePreviewCard);
        }

        document.getElementById('pci-add-rule').addEventListener('click', function () {
            addRule(null);
        });

        document.getElementById('pci-form').addEventListener('submit', function (e) {
            let valid = true;
            let message = '';

            container.querySelectorAll('.pci-rule-card').forEach(function (card, idx) {
                const selected = card.querySelectorAll('.pci-category-checkbox:checked').length;
                const type = card.querySelector('.pci-display-type').value;
                // Do not trim here, require at least one character, keep user spaces/lines
                const text = card.querySelector('.pci-text').value;
                const imageId = card.querySelector('.pci-image-id').value.trim();

                if (!selected) {
                    valid = false;
                    message = '{{ translate('Please select at least one category for each rule.') }}';
                } else if (type === 'text' && !text) {
                    valid = false;
                    message = '{{ translate('Please enter text for text-type rules.') }}';
                } else if (type === 'image' && !imageId) {
                    valid = false;
                    message = '{{ translate('Please upload an image for image-type rules.') }}';
                }
            });

            if (!valid) {
                e.preventDefault();
                AIZ.plugins.notify('warning', message);
            }
        });

        if (existingBadges.length) {
            existingBadges.forEach(function (badge) {
                addRule(badge);
            });
        } else {
            toggleEmptyState();
        }
    })();
</script>
@endsection
