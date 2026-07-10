<script>
(function () {
'use strict';

const builderRoot = document.getElementById('ttf-page-builder');
if (!builderRoot) return;

const pbForm = document.getElementById('ttf-pb-form');
const sectionGroups = document.getElementById('ttf-section-groups');
const groupEmptyState = document.querySelector('[data-group-empty-state]');
const groupTemplate = document.querySelector('template[data-group-template]');
const titleInput = document.getElementById('ttf-page-title');
const slugInput = document.querySelector('[data-page-slug-input]');
const slugAutofill = document.querySelector('[data-page-slug-autofill]');
const breadcrumbTitle = document.getElementById('ttf-pb-breadcrumb-title');
const canvasTitle = document.getElementById('ttf-pb-canvas-title');
const titleDisplay = document.getElementById('ttf-pb-title-display');
const saveStatus = document.getElementById('ttf-pb-save-status');
const widgetSearch = document.getElementById('ttf-pb-widget-search');
const rightPanel = document.getElementById('ttf-pb-right');
const rightTitle = document.getElementById('ttf-pb-right-title');
const rightContext = document.getElementById('ttf-pb-right-context');
const rightContextLbl = document.getElementById('ttf-pb-right-context-label');
const rightBack = document.getElementById('ttf-pb-right-back');
const portalTarget = document.getElementById('ttf-pb-portal-target');
const pageSettings = document.getElementById('ttf-pb-page-settings');
const navigatorTree = document.getElementById('ttf-pb-navigator-tree');
const colModal = document.getElementById('ttf-pb-col-modal');
const colModalClose = document.getElementById('ttf-pb-col-modal-close');
const colModalCancel = document.getElementById('ttf-pb-col-modal-cancel');
const colModalConfirm = document.getElementById('ttf-pb-col-modal-confirm');
const colOptions = Array.from(document.querySelectorAll('.ttf-pb-col-option'));
const deviceButtons = Array.from(document.querySelectorAll('.ttf-pb-device-btn'));
const tabButtons = Array.from(document.querySelectorAll('.ttf-pb-tab'));
const tabPanes = Array.from(document.querySelectorAll('.ttf-pb-tab-pane'));
const pageSettingsToggle = document.getElementById('ttf-pb-settings-toggle');
const pageSettingsButton = document.getElementById('ttf-pb-open-page-settings');
const leftToggleButton = document.getElementById('ttf-pb-left-toggle');
const leftPanel = document.getElementById('ttf-pb-left');
const pageBody = document.querySelector('.ttf-pb-body');
const addGroupButtons = Array.from(document.querySelectorAll('[data-add-group]'));
const clearCanvasButton = document.getElementById('ttf-pb-clear-canvas');
const saveButton = document.getElementById('ttf-pb-save-btn');

let activePortalOwner = null;
let activePortalEl = null;
let pendingAddGroupCols = 1;
let slugManual = !!(slugInput && slugInput.value.trim());
let draggedWidget = null;
let draggedSidebarType = null;
let isDirty = false;

function slugify(value) {
    return value.toString().toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w-]+/g, '')
        .replace(/--+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
}

function refreshPlugins(scope) {
    if (!window.AIZ || !AIZ.plugins) return;
    if (AIZ.plugins.bootstrapSelect) AIZ.plugins.bootstrapSelect('refresh');
    if (AIZ.plugins.textEditor) AIZ.plugins.textEditor();
    if (AIZ.plugins.aizUppy) AIZ.plugins.aizUppy();
    syncEditorValues(scope || builderRoot);
}

function syncEditorValues(scope) {
    if (!scope || !window.jQuery || !jQuery.fn.summernote) return;
    jQuery(scope).find('.aiz-text-editor').each(function () {
        const $input = jQuery(this);
        if ($input.next('.note-editor').length) {
            $input.val($input.summernote('code'));
        }
    });
}

function copyFormState(source, clone) {
    const sourceFields = source.querySelectorAll('input, select, textarea');
    const cloneFields = clone.querySelectorAll('input, select, textarea');
    sourceFields.forEach((field, index) => {
        const cloneField = cloneFields[index];
        if (!cloneField) return;
        if (field.type === 'checkbox' || field.type === 'radio') {
            cloneField.checked = field.checked;
            return;
        }
        cloneField.value = field.value;
        if (cloneField.tagName === 'TEXTAREA') {
            cloneField.textContent = field.value;
        }
    });
}

function cleanupClonedEditors(scope) {
    scope.querySelectorAll('.note-editor').forEach((node) => node.remove());
    scope.querySelectorAll('.aiz-text-editor').forEach((node) => {
        node.style.display = '';
    });
}

function setSaveStatus(text, saved) {
    if (!saveStatus) return;
    saveStatus.innerHTML = '<i class="las ' + (saved ? 'la-check-circle' : 'la-save') + '"></i> ' + text;
    saveStatus.style.color = saved ? 'var(--pb-green)' : 'var(--pb-accent)';
}

function markDirty() {
    isDirty = true;
    setSaveStatus('Unsaved changes', false);
}

function markSaved() {
    isDirty = false;
    setSaveStatus('All changes saved', true);
}

function updatePageTitleUI() {
    if (!titleInput) return;
    const value = titleInput.value.trim() || 'New Page';
    if (breadcrumbTitle) breadcrumbTitle.textContent = value;
    if (canvasTitle) canvasTitle.textContent = value;
    if (titleDisplay) titleDisplay.textContent = value;
}

function syncSlugFromTitle() {
    if (!titleInput || !slugInput || !slugAutofill || slugManual || !slugAutofill.checked) return;
    slugInput.value = slugify(titleInput.value.trim());
}

function updateSidebarGrid() {
    if (!pageBody) return;
    const leftHidden = leftPanel && !leftPanel.classList.contains('is-open');
    const rightHidden = rightPanel && !rightPanel.classList.contains('is-open');
    pageBody.classList.toggle('has-left-hidden', leftHidden);
    pageBody.classList.toggle('has-right-hidden', rightHidden);
}

function closeSettingsPanelOnMobile() {
    if (window.innerWidth > 991 || !rightPanel) return;
    rightPanel.classList.remove('is-open');
    updateSidebarGrid();
}

function openSettingsPanelOnMobile() {
    if (window.innerWidth > 991 || !rightPanel) return;
    rightPanel.classList.add('is-open');
    updateSidebarGrid();
}

function toggleLeftPanel() {
    if (!leftPanel) return;
    leftPanel.classList.toggle('is-open');
    updateSidebarGrid();
}

function toggleRightPanel() {
    if (!rightPanel) return;
    rightPanel.classList.toggle('is-open');
    updateSidebarGrid();
}

function showPageSettings() {
    if (rightPanel) {
        rightPanel.classList.add('is-open');
        updateSidebarGrid();
    }
    if (activePortalOwner) {
        activePortalOwner.classList.remove('is-active-editing');
        const selector = activePortalOwner.hasAttribute('data-widget-card')
            ? '[data-widget-settings-portal]'
            : '[data-section-settings-portal]';
        const source = activePortalOwner.querySelector(selector);
        if (source && activePortalEl) {
            source.appendChild(activePortalEl);
        }
        activePortalOwner = null;
        activePortalEl = null;
    }

    portalTarget.classList.add('d-none');
    portalTarget.innerHTML = '';
    rightContext.classList.add('d-none');
    rightTitle.textContent = 'Settings';
    pageSettings.classList.remove('d-none');
    openSettingsPanelOnMobile();
    refreshPlugins(pageSettings);
}

function openPortal(owner, portalSelector, label) {
    showPageSettings();
    if (rightPanel) {
        rightPanel.classList.add('is-open');
        updateSidebarGrid();
    }
    const source = owner.querySelector(portalSelector);
    if (!source || !source.firstElementChild) return;

    activePortalOwner = owner;
    activePortalEl = source.firstElementChild;
    owner.classList.add('is-active-editing');
    pageSettings.classList.add('d-none');
    portalTarget.innerHTML = '';
    portalTarget.appendChild(activePortalEl);
    portalTarget.classList.remove('d-none');
    rightContext.classList.remove('d-none');
    rightContextLbl.textContent = label;
    rightTitle.textContent = 'Settings';
    openSettingsPanelOnMobile();
    refreshPlugins(portalTarget);
}

function openParentAccordions(field) {
    let current = field ? field.parentElement : null;
    while (current) {
        if (current.tagName === 'DETAILS') {
            current.open = true;
        }
        current = current.parentElement;
    }
}

function revealInvalidField(field) {
    if (!field) return;

    const groupCard = field.closest('[data-group-card]');
    const widgetCard = field.closest('[data-widget-card]');

    if (groupCard) {
        toggleGroup(groupCard, true);
    }

    if (widgetCard) {
        const widgetPortal = field.closest('[data-widget-settings-portal]');
        if (widgetPortal) {
            openPortal(widgetCard, '[data-widget-settings-portal]', 'Widget: ' + getWidgetTypeLabel(widgetCard));
        } else {
            toggleWidget(widgetCard, true);
        }
    } else if (groupCard) {
        const sectionPortal = field.closest('[data-section-settings-portal]');
        if (sectionPortal) {
            openPortal(groupCard, '[data-section-settings-portal]', 'Section');
        }
    } else if (field.closest('#ttf-pb-page-settings')) {
        showPageSettings();
    }

    openParentAccordions(field);
    openSettingsPanelOnMobile();

    window.setTimeout(function () {
        openParentAccordions(field);
        if (typeof field.scrollIntoView === 'function') {
            field.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        if (typeof field.focus === 'function') {
            field.focus({ preventScroll: true });
        }
        if (typeof field.reportValidity === 'function') {
            field.reportValidity();
        }
    }, 60);
}

function getFirstInvalidField() {
    const fields = Array.from(pbForm.querySelectorAll('input, select, textarea'));
    return fields.find(function (field) {
        return !field.disabled
            && field.type !== 'hidden'
            && typeof field.checkValidity === 'function'
            && !field.checkValidity();
    }) || null;
}

function getCardStyleInput(card, name, fallback = '', scope = null) {
    const source = scope || card;
    const input = source.querySelector('[name$="' + name + '"]') || card.querySelector('[name$="' + name + '"]');
    return input ? input.value.trim() : fallback;
}

function applyAppearanceStyles(card, scope = card) {
    if (!card) return;
    const body = card.querySelector('[data-group-body]') || card.querySelector('[data-widget-body]');
    const backgroundEnabled = scope.querySelector('[data-style-toggle="background"]')?.checked;
    const borderEnabled = scope.querySelector('[data-style-toggle="border"]')?.checked;
    const paddingEnabled = scope.querySelector('[data-style-toggle="padding"]')?.checked;

    const backgroundColor = getCardStyleInput(card, '[background_color]', '', scope);
    const borderRadius = getCardStyleInput(card, '[border_radius]', '', scope);
    const borderColor = getCardStyleInput(card, '[border_color]', '#e2e8f0', scope);
    const borderWidth = getCardStyleInput(card, '[border_width]', '1', scope);
    const borderStyle = getCardStyleInput(card, '[border_style]', 'solid', scope);

    if (backgroundEnabled) {
        card.style.background = backgroundColor || '';
    } else {
        card.style.background = '';
    }

    if (borderEnabled) {
        card.style.borderStyle = borderStyle;
        card.style.borderWidth = borderWidth + 'px';
        card.style.borderColor = borderColor;
    } else {
        card.style.borderStyle = '';
        card.style.borderWidth = '';
        card.style.borderColor = '';
    }

    if (borderRadius) {
        card.style.borderRadius = borderRadius + 'px';
    } else if (!backgroundEnabled && !borderEnabled) {
        card.style.borderRadius = '';
    }

    if (body) {
        if (paddingEnabled) {
            body.style.paddingTop = getCardStyleInput(card, '[padding_top]', '0', scope) + 'px';
            body.style.paddingRight = getCardStyleInput(card, '[padding_right]', '0', scope) + 'px';
            body.style.paddingBottom = getCardStyleInput(card, '[padding_bottom]', '0', scope) + 'px';
            body.style.paddingLeft = getCardStyleInput(card, '[padding_left]', '0', scope) + 'px';
        } else {
            body.style.paddingTop = '';
            body.style.paddingRight = '';
            body.style.paddingBottom = '';
            body.style.paddingLeft = '';
        }
    }
}

function updateGroupToggleIcon(groupCard) {
    if (!groupCard) return;
    const button = groupCard.querySelector('[data-toggle-group-icon] i');
    const body = groupCard.querySelector('[data-group-body]');
    if (!button || !body) return;
    const open = !body.classList.contains('d-none');
    button.className = open ? 'las la-angle-down' : 'las la-angle-right';
}

function updateWidgetToggleIcon(widgetCard) {
    if (!widgetCard) return;
    const button = widgetCard.querySelector('[data-toggle-widget]');
    const icon = button ? button.querySelector('i') : null;
    const body = widgetCard.querySelector('[data-widget-body]');
    if (!button || !icon || !body) return;
    const open = !body.classList.contains('d-none');
    icon.className = open ? 'las la-angle-down' : 'las la-angle-right';
    button.title = open ? button.getAttribute('data-label-close') : button.getAttribute('data-label-open');
}

function syncAppearance(card) {
    if (!card) return;
    const scope = (activePortalOwner === card && activePortalEl) ? activePortalEl : card;
    ['background', 'border', 'padding'].forEach((key) => {
        const toggle = scope.querySelector('[data-style-toggle="' + key + '"]');
        const enabled = toggle ? toggle.checked : false;
        scope.querySelectorAll('[data-style-target="' + key + '"]').forEach((node) => {
            node.classList.toggle('d-none', !enabled);
        });
    });
    applyAppearanceStyles(card, scope);
}

function syncToggleFields(card) {
    if (!card) return;
    const scope = (activePortalOwner === card && activePortalEl) ? activePortalEl : card;
    scope.querySelectorAll('[data-toggle-field]').forEach((toggle) => {
        const name = toggle.getAttribute('data-toggle-field');
        scope.querySelectorAll('[data-toggle-target="' + name + '"]').forEach((target) => {
            target.classList.toggle('d-none', !toggle.checked);
        });
    });
}

function syncVisibilitySummary(card, selector) {
    if (!card) return;
    const target = card.querySelector(selector);
    if (!target) return;
    const scope = (activePortalOwner === card && activePortalEl) ? activePortalEl : card;
    const map = { desktop: 'Desktop', ipad_pro: 'iPad Pro', ipad: 'iPad', phone: 'Phone' };
    const visible = Object.keys(map).filter((key) => {
        const toggle = scope.querySelector('[data-visibility-toggle="' + key + '"]');
        return toggle && toggle.checked;
    });
    target.textContent = visible.length === 4
        ? 'All Devices'
        : (visible.length ? visible.map((key) => map[key]).join(', ') : 'Hidden');
}

function getWidgetTypeLabel(widgetCard) {
    const typeInput = widgetCard.querySelector('input[name*="[type]"]');
    const map = {
        rich_text: 'Text Editor',
        split: 'Two Column',
        full_width: 'Full Width',
        image_grid: 'Grid Cards',
        full_image: 'Image Showcase',
        toc_content: 'TOC + Content',
        header_widget: 'Heading',
        image_widget: 'Single Image',
        button_widget: 'Action Button'
    };
    return map[typeInput ? typeInput.value : ''] || 'Widget';
}

function syncGroupLabel(groupCard) {
    const input = groupCard.querySelector('[data-group-name-input]');
    const label = groupCard.querySelector('[data-group-label]');
    if (label) {
        label.textContent = input && input.value.trim() ? input.value.trim() : 'Untitled Section';
    }
}

function syncWidgetLabel(widgetCard) {
    const preview = widgetCard.querySelector('[data-widget-preview]');
    const label = widgetCard.querySelector('[data-widget-label]');
    const headingInput = widgetCard.querySelector('[data-widget-heading-input]');
    const typeLabel = getWidgetTypeLabel(widgetCard);
    const text = headingInput && headingInput.value.trim() ? headingInput.value.trim() : typeLabel;
    if (label) label.textContent = text;
    if (preview) preview.textContent = text;
}

function syncWidgetCount(groupCard) {
    const countNode = groupCard.querySelector('[data-group-widget-count]');
    if (countNode) {
        countNode.textContent = groupCard.querySelectorAll('[data-widget-card]').length + ' Widgets';
    }
}

function syncWidgetEmptyState(groupCard) {
    groupCard.querySelectorAll('[data-admin-column]').forEach((column) => {
        const drop = column.querySelector('[data-widget-container]');
        const empty = column.querySelector('[data-widget-empty-state]');
        if (!drop || !empty) return;
        empty.classList.toggle('d-none', !!drop.querySelector('[data-widget-card]'));
    });
}

function syncGroupEmptyState() {
    if (!groupEmptyState) return;
    groupEmptyState.classList.toggle('d-none', !!sectionGroups.querySelector('[data-group-card]'));
}

function switchTab(name) {
    tabButtons.forEach((button) => {
        button.classList.toggle('is-active', button.getAttribute('data-tab') === name);
    });
    tabPanes.forEach((pane) => {
        pane.classList.toggle('is-active', pane.id === 'ttf-pb-tab-' + name);
    });
}

function setDeviceMode(device) {
    builderRoot.setAttribute('data-device', device);
    deviceButtons.forEach((button) => {
        button.classList.toggle('is-active', button.getAttribute('data-device') === device);
    });
}

function createColumnNode(index) {
    const column = document.createElement('div');
    column.className = 'ttf-pb-column';
    column.setAttribute('data-admin-column', String(index));
    column.innerHTML = '' +
        '<div class="ttf-pb-column__label">Column ' + (index + 1) + '</div>' +
        '<div class="ttf-pb-column__drop" data-widget-container data-column-index="' + index + '"></div>' +
        '<div class="ttf-pb-column__empty" data-widget-empty-state>Empty column. Drag or add a widget here.</div>';
    return column;
}

function syncGroupColumns(groupCard, requestedColumns) {
    const body = groupCard.querySelector('[data-group-body]');
    const grid = groupCard.querySelector('[data-columns-grid]');
    if (!body || !grid) return;

    const cols = Math.max(1, Math.min(4, parseInt(requestedColumns || '1', 10) || 1));
    const select = groupCard.querySelector('[data-group-columns-select]');
    if (select) select.value = String(cols);

    const widgets = Array.from(grid.querySelectorAll('[data-widget-card]'));
    grid.className = 'ttf-pb-columns ttf-pb-columns--' + cols;
    grid.innerHTML = '';

    const columns = [];
    for (let index = 0; index < cols; index += 1) {
        const column = createColumnNode(index);
        columns.push(column);
        grid.appendChild(column);
    }

    widgets.forEach((widget) => {
        const columnInput = widget.querySelector('[data-widget-column-input]');
        let targetColumn = parseInt(columnInput ? columnInput.value : '0', 10) || 0;
        if (targetColumn >= cols) targetColumn = cols - 1;
        if (targetColumn < 0) targetColumn = 0;
        if (columnInput) columnInput.value = String(targetColumn);
        columns[targetColumn].querySelector('[data-widget-container]').appendChild(widget);
    });

    syncWidgetEmptyState(groupCard);
}

function reindexRepeater(widgetCard) {
    widgetCard.querySelectorAll('[data-item-target]').forEach((container) => {
        const rows = Array.from(container.querySelectorAll('[data-item-row]'));
        rows.forEach((row, itemIndex) => {
            row.setAttribute('data-item-index', String(itemIndex));
            row.querySelectorAll('input, textarea, select').forEach((field) => {
                if (!field.name) return;
                field.name = field.name.replace(/\[items]\[(?:__ITEM_INDEX__|\d+)\]/g, '[items][' + itemIndex + ']');
            });
        });
        container.setAttribute('data-next-index', String(rows.length));
    });
}

function reindexAll() {
    Array.from(sectionGroups.querySelectorAll('[data-group-card]')).forEach((groupCard, groupIndex) => {
        groupCard.setAttribute('data-group-index', String(groupIndex));
        groupCard.querySelectorAll('input, textarea, select').forEach((field) => {
            if (!field.name) return;
            field.name = field.name.replace(/builder\[sections]\[(?:__GROUP_INDEX__|\d+)\]/g, 'builder[sections][' + groupIndex + ']');
        });

        Array.from(groupCard.querySelectorAll('[data-widget-card]')).forEach((widgetCard, widgetIndex) => {
            widgetCard.setAttribute('data-widget-index', String(widgetIndex));
            widgetCard.querySelectorAll('input, textarea, select').forEach((field) => {
                if (!field.name) return;
                field.name = field.name.replace(/\[widgets]\[(?:__WIDGET_INDEX__|\d+)\]/g, '[widgets][' + widgetIndex + ']');
            });
            reindexRepeater(widgetCard);
            syncWidgetLabel(widgetCard);
            syncAppearance(widgetCard);
            syncToggleFields(widgetCard);
            updateWidgetToggleIcon(widgetCard);
            syncVisibilitySummary(widgetCard, '[data-widget-visibility-summary]');
        });

        syncGroupLabel(groupCard);
        syncWidgetCount(groupCard);
        syncWidgetEmptyState(groupCard);
        syncAppearance(groupCard);
        updateGroupToggleIcon(groupCard);
        syncVisibilitySummary(groupCard, '[data-group-visibility-summary]');
    });

    sectionGroups.setAttribute('data-next-group-index', String(sectionGroups.querySelectorAll('[data-group-card]').length));
    syncGroupEmptyState();
    buildNavigator();
    refreshPlugins(sectionGroups);
}

function buildNavigator() {
    if (!navigatorTree) return;
    const groups = Array.from(sectionGroups.querySelectorAll('[data-group-card]'));
    if (!groups.length) {
        navigatorTree.innerHTML = '<div class="ttf-pb-nav-empty">No sections yet</div>';
        return;
    }

    const html = groups.map((groupCard, groupIndex) => {
        const groupLabel = (groupCard.querySelector('[data-group-label]') || {}).textContent || 'Untitled Section';
        const widgets = Array.from(groupCard.querySelectorAll('[data-widget-card]'));
        const widgetHtml = widgets.map((widgetCard, widgetIndex) => {
            const label = (widgetCard.querySelector('[data-widget-label]') || {}).textContent || getWidgetTypeLabel(widgetCard);
            return '<button type="button" class="ttf-pb-nav-item ttf-pb-nav-item--child" data-nav-group="' + groupIndex + '" data-nav-widget="' + widgetIndex + '">' + label + '</button>';
        }).join('');
        return '<div class="ttf-pb-nav-group">' +
            '<button type="button" class="ttf-pb-nav-item" data-nav-group="' + groupIndex + '">' + groupLabel + '</button>' +
            widgetHtml +
            '</div>';
    }).join('');

    navigatorTree.innerHTML = html;
}

function toggleGroup(groupCard, forceOpen) {
    const body = groupCard.querySelector('[data-group-body]');
    if (!body) return;
    const open = typeof forceOpen === 'boolean' ? forceOpen : body.classList.contains('d-none');
    body.classList.toggle('d-none', !open);
    updateGroupToggleIcon(groupCard);
}

function toggleWidget(widgetCard, forceOpen) {
    const body = widgetCard.querySelector('[data-widget-body]');
    if (!body) return;
    const open = typeof forceOpen === 'boolean' ? forceOpen : body.classList.contains('d-none');
    body.classList.toggle('d-none', !open);
    if (open) refreshPlugins(body);
    updateWidgetToggleIcon(widgetCard);
}

function createGroup(columns) {
    if (!groupTemplate) return null;
    const tempIndex = sectionGroups.querySelectorAll('[data-group-card]').length;
    const html = groupTemplate.innerHTML
        .replace(/__GROUP_INDEX__/g, String(tempIndex))
        .replace(/__WIDGET_INDEX__/g, '0');
    const wrapper = document.createElement('div');
    wrapper.innerHTML = html.trim();
    const groupCard = wrapper.firstElementChild;
    if (!groupCard) return null;
    sectionGroups.appendChild(groupCard);
    syncGroupColumns(groupCard, columns || 1);
    toggleGroup(groupCard, true);
    updateGroupToggleIcon(groupCard);
    reindexAll();
    return groupCard;
}

function createWidgetFromType(groupCard, type, columnIndex) {
    const template = groupCard.querySelector('template[data-widget-template="' + type + '"]');
    if (!template) return null;

    const tempGroupIndex = groupCard.getAttribute('data-group-index') || '0';
    const tempWidgetIndex = groupCard.querySelectorAll('[data-widget-card]').length;
    const html = template.innerHTML
        .replace(/__GROUP_INDEX__/g, tempGroupIndex)
        .replace(/__WIDGET_INDEX__/g, String(tempWidgetIndex));
    const wrapper = document.createElement('div');
    wrapper.innerHTML = html.trim();
    const widgetCard = wrapper.firstElementChild;
    if (!widgetCard) return null;
    const columnInput = widgetCard.querySelector('[data-widget-column-input]');
    if (columnInput) {
        columnInput.value = String(columnIndex || 0);
    }
    return widgetCard;
}

function addWidgetToColumn(container, type) {
    const groupCard = container.closest('[data-group-card]');
    if (!groupCard) return;
    const columnIndex = parseInt(container.getAttribute('data-column-index') || '0', 10) || 0;
    const widgetCard = createWidgetFromType(groupCard, type, columnIndex);
    if (!widgetCard) return;
    container.appendChild(widgetCard);
    toggleGroup(groupCard, true);
    toggleWidget(widgetCard, true);
    reindexAll();
    openPortal(widgetCard, '[data-widget-settings-portal]', 'Widget: ' + getWidgetTypeLabel(widgetCard));
    markDirty();
}

function updateTocAnchors(widgetCard) {
    widgetCard.querySelectorAll('[data-item-row]').forEach((row) => {
        const titleField = row.querySelector('[data-toc-title-input]');
        const anchorField = row.querySelector('[data-toc-anchor-input]');
        if (titleField && anchorField) {
            anchorField.value = slugify(titleField.value || '');
        }
    });
}

function addRepeaterItem(button) {
    const key = button.getAttribute('data-add-item');
    const widgetCard = button.closest('[data-widget-card]');
    if (!key || !widgetCard) return;
    const container = widgetCard.querySelector('[data-item-target="' + key + '"]');
    const template = widgetCard.querySelector('template[data-item-template="' + key + '"]');
    if (!container || !template) return;

    const nextIndex = parseInt(container.getAttribute('data-next-index') || '0', 10) || 0;
    const wrapper = document.createElement('div');
    wrapper.innerHTML = template.innerHTML.replace(/__ITEM_INDEX__/g, String(nextIndex)).trim();
    const row = wrapper.firstElementChild;
    if (!row) return;
    container.appendChild(row);
    container.setAttribute('data-next-index', String(nextIndex + 1));
    reindexRepeater(widgetCard);
    updateTocAnchors(widgetCard);
    refreshPlugins(row);
    markDirty();
}

function moveNode(node, direction) {
    if (!node || !node.parentElement) return;
    const sibling = direction < 0 ? node.previousElementSibling : node.nextElementSibling;
    if (!sibling) return;
    if (direction < 0) {
        node.parentElement.insertBefore(node, sibling);
    } else {
        node.parentElement.insertBefore(sibling, node);
    }
}

function setColorTextValue(input) {
    const row = input.closest('.ttf-pb-color-row');
    if (!row) return;
    const textInput = row.querySelector('input[type="text"]');
    if (textInput) {
        textInput.value = input.value;
    }
}

function setColorPickerValue(input) {
    const row = input.closest('.ttf-pb-color-row');
    if (!row) return;
    const picker = row.querySelector('input[type="color"]');
    if (picker && /^#([0-9a-f]{3}|[0-9a-f]{6})$/i.test(input.value.trim())) {
        picker.value = input.value.trim();
    }
}

function filterWidgetLibrary() {
    const term = widgetSearch ? widgetSearch.value.trim().toLowerCase() : '';
    builderRoot.querySelectorAll('[data-sidebar-widget]').forEach((tile) => {
        const text = tile.textContent.toLowerCase();
        tile.classList.toggle('d-none', !!term && text.indexOf(term) === -1);
    });
}

function showColumnModal() {
    if (!colModal) return;
    colModal.classList.remove('is-hidden');
}

function hideColumnModal() {
    if (!colModal) return;
    colModal.classList.add('is-hidden');
}

function initStaticControls() {
    if (saveButton && pbForm) {
        saveButton.addEventListener('click', function () {
            if (typeof pbForm.requestSubmit === 'function') {
                pbForm.requestSubmit();
                return;
            }
            const fallbackSubmit = document.createElement('button');
            fallbackSubmit.type = 'submit';
            fallbackSubmit.className = 'd-none';
            pbForm.appendChild(fallbackSubmit);
            fallbackSubmit.click();
            fallbackSubmit.remove();
        });
    }

    tabButtons.forEach((button) => {
        button.addEventListener('click', function () {
            switchTab(this.getAttribute('data-tab'));
        });
    });

    deviceButtons.forEach((button) => {
        button.addEventListener('click', function () {
            setDeviceMode(this.getAttribute('data-device'));
        });
    });

    if (pageSettingsToggle) {
        pageSettingsToggle.addEventListener('click', function () {
            if (!rightPanel) return;
            const open = rightPanel.classList.toggle('is-open');
            updateSidebarGrid();
            if (open) showPageSettings();
        });
    }

    if (pageSettingsButton) {
        pageSettingsButton.addEventListener('click', function () {
            if (!rightPanel) return;
            if (!rightPanel.classList.contains('is-open')) {
                rightPanel.classList.add('is-open');
                updateSidebarGrid();
            }
            showPageSettings();
        });
    }

    if (leftToggleButton && leftPanel) {
        leftToggleButton.addEventListener('click', function () {
            leftPanel.classList.toggle('is-open');
            updateSidebarGrid();
        });
    }

    addGroupButtons.forEach((button) => {
        button.addEventListener('click', showColumnModal);
    });

    if (clearCanvasButton) {
        clearCanvasButton.addEventListener('click', function () {
            if (!sectionGroups.querySelector('[data-group-card]')) return;
            if (!window.confirm('Clear all sections from this page?')) return;
            showPageSettings();
            sectionGroups.innerHTML = '';
            reindexAll();
            markDirty();
        });
    }

    if (rightBack) {
        rightBack.addEventListener('click', showPageSettings);
    }

    if (widgetSearch) {
        widgetSearch.addEventListener('input', filterWidgetLibrary);
    }

    if (colModalClose) colModalClose.addEventListener('click', hideColumnModal);
    if (colModalCancel) colModalCancel.addEventListener('click', hideColumnModal);
    if (colModal) {
        colModal.addEventListener('click', function (event) {
            if (event.target === colModal) hideColumnModal();
        });
    }

    colOptions.forEach((option) => {
        option.addEventListener('click', function () {
            pendingAddGroupCols = parseInt(this.getAttribute('data-cols') || '1', 10) || 1;
            colOptions.forEach((node) => node.classList.remove('is-selected'));
            this.classList.add('is-selected');
        });
    });

    if (colModalConfirm) {
        colModalConfirm.addEventListener('click', function () {
            hideColumnModal();
            const groupCard = createGroup(pendingAddGroupCols);
            if (groupCard) {
                groupCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                markDirty();
            }
        });
    }

    if (titleInput) {
        titleInput.addEventListener('input', function () {
            updatePageTitleUI();
            syncSlugFromTitle();
        });
    }

    if (slugAutofill) {
        slugAutofill.addEventListener('change', function () {
            slugManual = !this.checked;
            syncSlugFromTitle();
        });
    }

    if (slugInput) {
        slugInput.addEventListener('input', function () {
            slugManual = !slugAutofill || !slugAutofill.checked || !!this.value.trim();
        });
    }

    if (navigatorTree) {
        navigatorTree.addEventListener('click', function (event) {
            const button = event.target.closest('[data-nav-group]');
            if (!button) return;
            const groupCard = sectionGroups.querySelector('[data-group-index="' + button.getAttribute('data-nav-group') + '"]');
            if (!groupCard) return;
            toggleGroup(groupCard, true);
            const widgetIndex = button.getAttribute('data-nav-widget');
            if (widgetIndex !== null) {
                const widgetCard = groupCard.querySelector('[data-widget-index="' + widgetIndex + '"]');
                if (widgetCard) {
                    toggleWidget(widgetCard, true);
                    widgetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                groupCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }
}

function handleBuilderClick(event) {
    const button = event.target.closest('button');
    if (!button) return;

    if (button.hasAttribute('data-toggle-group')) {
        return;
    }

    if (button.hasAttribute('data-toggle-widget')) {
        event.preventDefault();
        toggleWidget(button.closest('[data-widget-card]'));
        return;
    }

    if (button.hasAttribute('data-edit-widget-settings')) {
        event.preventDefault();
        const widgetCard = button.closest('[data-widget-card]');
        toggleWidget(widgetCard, true);
        openPortal(widgetCard, '[data-widget-settings-portal]', 'Widget: ' + getWidgetTypeLabel(widgetCard));
        return;
    }

    if (button.hasAttribute('data-edit-section-settings')) {
        event.preventDefault();
        const groupCard = button.closest('[data-group-card]');
        toggleGroup(groupCard, true);
        openPortal(groupCard, '[data-section-settings-portal]', 'Section');
        return;
    }

    if (button.hasAttribute('data-remove-group')) {
        event.preventDefault();
        const groupCard = button.closest('[data-group-card]');
        if (groupCard && window.confirm('Delete this section?')) {
            if (activePortalOwner === groupCard) showPageSettings();
            groupCard.remove();
            reindexAll();
            markDirty();
        }
        return;
    }

    if (button.hasAttribute('data-copy-group')) {
        event.preventDefault();
        const groupCard = button.closest('[data-group-card]');
        if (!groupCard) return;
        showPageSettings();
        const clone = groupCard.cloneNode(true);
        cleanupClonedEditors(clone);
        copyFormState(groupCard, clone);
        groupCard.parentElement.insertBefore(clone, groupCard.nextElementSibling);
        reindexAll();
        markDirty();
        return;
    }

    if (button.hasAttribute('data-move-group-up')) {
        event.preventDefault();
        moveNode(button.closest('[data-group-card]'), -1);
        reindexAll();
        markDirty();
        return;
    }

    if (button.hasAttribute('data-move-group-down')) {
        event.preventDefault();
        moveNode(button.closest('[data-group-card]'), 1);
        reindexAll();
        markDirty();
        return;
    }

    if (button.hasAttribute('data-remove-widget')) {
        event.preventDefault();
        const widgetCard = button.closest('[data-widget-card]');
        const groupCard = button.closest('[data-group-card]');
        if (widgetCard && window.confirm('Delete this widget?')) {
            if (activePortalOwner === widgetCard) showPageSettings();
            widgetCard.remove();
            if (groupCard) {
                syncWidgetEmptyState(groupCard);
                syncWidgetCount(groupCard);
            }
            reindexAll();
            markDirty();
        }
        return;
    }

    if (button.hasAttribute('data-copy-widget')) {
        event.preventDefault();
        const widgetCard = button.closest('[data-widget-card]');
        if (!widgetCard) return;
        showPageSettings();
        const clone = widgetCard.cloneNode(true);
        cleanupClonedEditors(clone);
        copyFormState(widgetCard, clone);
        widgetCard.parentElement.insertBefore(clone, widgetCard.nextElementSibling);
        reindexAll();
        markDirty();
        return;
    }

    if (button.hasAttribute('data-move-widget-up')) {
        event.preventDefault();
        moveNode(button.closest('[data-widget-card]'), -1);
        reindexAll();
        markDirty();
        return;
    }

    if (button.hasAttribute('data-move-widget-down')) {
        event.preventDefault();
        moveNode(button.closest('[data-widget-card]'), 1);
        reindexAll();
        markDirty();
        return;
    }

    if (button.hasAttribute('data-add-item')) {
        event.preventDefault();
        addRepeaterItem(button);
        return;
    }

    if (button.hasAttribute('data-remove-item')) {
        event.preventDefault();
        const widgetCard = button.closest('[data-widget-card]');
        const row = button.closest('[data-item-row]');
        if (!widgetCard || !row) return;
        row.remove();
        reindexRepeater(widgetCard);
        updateTocAnchors(widgetCard);
        markDirty();
        return;
    }

    if (button.hasAttribute('data-move-item-up')) {
        event.preventDefault();
        const widgetCard = button.closest('[data-widget-card]');
        moveNode(button.closest('[data-item-row]'), -1);
        if (widgetCard) {
            reindexRepeater(widgetCard);
            updateTocAnchors(widgetCard);
        }
        markDirty();
        return;
    }

    if (button.hasAttribute('data-move-item-down')) {
        event.preventDefault();
        const widgetCard = button.closest('[data-widget-card]');
        moveNode(button.closest('[data-item-row]'), 1);
        if (widgetCard) {
            reindexRepeater(widgetCard);
            updateTocAnchors(widgetCard);
        }
        markDirty();
    }
}

function handleBuilderInput(event) {
    const target = event.target;
    const widgetCard = target.closest('[data-widget-card]');
    const groupCard = target.closest('[data-group-card]');
    const ownerCard = widgetCard || groupCard || activePortalOwner;

    if (target.matches('.ttf-pb-color-row input[type="color"]')) {
        setColorTextValue(target);
    }

    if (target.matches('.ttf-pb-color-row input[type="text"]')) {
        setColorPickerValue(target);
    }

    if (target.matches('[data-group-name-input]') && ownerCard) {
        syncGroupLabel(ownerCard);
        buildNavigator();
    }

    if (target.matches('[data-widget-heading-input]') && ownerCard) {
        syncWidgetLabel(ownerCard);
        buildNavigator();
    }

    if (target.matches('[data-toggle-field]') && ownerCard) {
        syncToggleFields(ownerCard);
    }

    if (target.matches('[data-style-toggle]') && ownerCard) {
        syncAppearance(ownerCard);
    }

    if (target.matches('[data-visibility-toggle]') && ownerCard) {
        if (ownerCard.hasAttribute('data-widget-card')) {
            syncVisibilitySummary(ownerCard, '[data-widget-visibility-summary]');
        } else if (ownerCard.hasAttribute('data-group-card')) {
            syncVisibilitySummary(ownerCard, '[data-group-visibility-summary]');
        }
    }

    if (target.matches('[data-group-columns-select]') && ownerCard) {
        syncGroupColumns(ownerCard, target.value);
        reindexAll();
    }

    if (target.matches('[data-toc-title-input]') && widgetCard) {
        updateTocAnchors(widgetCard);
    }

    updatePageTitleUI();
    syncSlugFromTitle();
    markDirty();
}

function handleGroupHeaderClick(event) {
    const header = event.target.closest('[data-toggle-group]');
    if (!header) return;
    if (event.target.closest('.ttf-pb-section__actions')) return;
    toggleGroup(header.closest('[data-group-card]'));
}

function handleSidebarWidgetClick(event) {
    const tile = event.target.closest('[data-sidebar-widget]');
    if (!tile) return;
    const firstContainer = sectionGroups.querySelector('[data-widget-container]');
    if (firstContainer) {
        addWidgetToColumn(firstContainer, tile.getAttribute('data-sidebar-widget'));
        return;
    }

    const groupCard = createGroup(1);
    if (!groupCard) return;
    const container = groupCard.querySelector('[data-widget-container]');
    if (container) {
        addWidgetToColumn(container, tile.getAttribute('data-sidebar-widget'));
    }
}

function handleDragStart(event) {
    const sidebarTile = event.target.closest('[data-sidebar-widget]');
    if (sidebarTile) {
        draggedSidebarType = sidebarTile.getAttribute('data-sidebar-widget');
        draggedWidget = null;
        event.dataTransfer.effectAllowed = 'copy';
        return;
    }

    const widgetCard = event.target.closest('[data-widget-card]');
    if (widgetCard) {
        draggedWidget = widgetCard;
        draggedSidebarType = null;
        event.dataTransfer.effectAllowed = 'move';
    }
}

function handleDragEnd() {
    draggedSidebarType = null;
    draggedWidget = null;
    builderRoot.querySelectorAll('.is-drop-zone').forEach((node) => node.classList.remove('is-drop-zone'));
}

function handleDragOver(event) {
    const container = event.target.closest('[data-widget-container]');
    if (!container) return;
    if (!draggedSidebarType && !draggedWidget) return;
    event.preventDefault();
    container.classList.add('is-drop-zone');
    event.dataTransfer.dropEffect = draggedSidebarType ? 'copy' : 'move';
}

function handleDragLeave(event) {
    const container = event.target.closest('[data-widget-container]');
    if (!container) return;
    container.classList.remove('is-drop-zone');
}

function handleDrop(event) {
    const container = event.target.closest('[data-widget-container]');
    if (!container) return;
    if (!draggedSidebarType && !draggedWidget) return;
    event.preventDefault();
    container.classList.remove('is-drop-zone');

    if (draggedSidebarType) {
        addWidgetToColumn(container, draggedSidebarType);
        return;
    }

    if (draggedWidget) {
        container.appendChild(draggedWidget);
        const columnInput = draggedWidget.querySelector('[data-widget-column-input]');
        if (columnInput) {
            columnInput.value = container.getAttribute('data-column-index') || '0';
        }
        reindexAll();
        markDirty();
    }
}

function initFormLifecycle() {
    pbForm.addEventListener('submit', function (event) {
        syncEditorValues(builderRoot);
        const invalidField = getFirstInvalidField();
        if (invalidField) {
            event.preventDefault();
            revealInvalidField(invalidField);
            return;
        }
        markSaved();
    });
}

function init() {
    initStaticControls();
    initFormLifecycle();

    builderRoot.addEventListener('click', handleGroupHeaderClick);
    builderRoot.addEventListener('click', handleSidebarWidgetClick);
    builderRoot.addEventListener('click', handleBuilderClick);
    builderRoot.addEventListener('input', handleBuilderInput);
    builderRoot.addEventListener('change', handleBuilderInput);
    builderRoot.addEventListener('dragstart', handleDragStart);
    builderRoot.addEventListener('dragend', handleDragEnd);
    builderRoot.addEventListener('dragover', handleDragOver);
    builderRoot.addEventListener('dragleave', handleDragLeave);
    builderRoot.addEventListener('drop', handleDrop);

    window.addEventListener('resize', function () {
        updateSidebarGrid();
    });

    switchTab('widgets');
    setDeviceMode('desktop');
    updatePageTitleUI();
    reindexAll();
    filterWidgetLibrary();
    if (leftPanel) leftPanel.classList.add('is-open');
    if (rightPanel) rightPanel.classList.add('is-open');
    updateSidebarGrid();
    showPageSettings();
    markSaved();
}

init();
})();
</script>
