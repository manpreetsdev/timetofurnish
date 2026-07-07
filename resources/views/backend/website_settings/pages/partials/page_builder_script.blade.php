<script>
    (function () {
        const sectionGroups = document.getElementById('ttf-section-groups');
        const groupEmptyState = document.querySelector('[data-group-empty-state]');
        const groupTemplate = document.querySelector('template[data-group-template]');
        const pageForm = document.querySelector('.ttf-admin-page-form');
        const pageTitleInput = document.querySelector('#ttf-page-title');
        const pageSlugInput = document.querySelector('[data-page-slug-input]');
        const pageSlugAutofillToggle = document.querySelector('[data-page-slug-autofill]');
        const builderLayout = document.querySelector('[data-page-builder-layout]');
        const sidebarToggles = document.querySelectorAll('[data-sidebar-toggle]');

        let draggedGroupCard = null;
        let draggedWidgetCard = null;
        let draggedSidebarWidgetType = null;
        let activePortalOwner = null;
        let activePortalElement = null;
        let slugEditedManually = pageSlugInput ? pageSlugInput.value.trim() !== '' : false;

        const activeSettingsHeader = document.getElementById('active-settings-header');
        const activeSettingsTitle = document.getElementById('active-settings-title');
        const activeSettingsPortalTarget = document.getElementById('active-settings-portal-target');
        const defaultPageSettings = document.getElementById('default-page-settings');
        const widgetSidebar = document.querySelector('[data-builder-sidebar="widgets"]');
        const settingsSidebar = document.querySelector('[data-builder-sidebar="settings"]');
        const settingsSidebarResizer = document.querySelector('[data-sidebar-resizer="settings"]');

        function refreshPlugins() {
            if (window.AIZ && AIZ.plugins) {
                if (AIZ.plugins.bootstrapSelect) {
                    AIZ.plugins.bootstrapSelect('refresh');
                }
                if (AIZ.plugins.textEditor) {
                    AIZ.plugins.textEditor();
                }
                if (AIZ.plugins.aizUppy) {
                    AIZ.plugins.aizUppy();
                }
            }
        }

        function slugify(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        }

        function syncSlugFromTitle(force) {
            if (!pageTitleInput || !pageSlugInput || !pageSlugAutofillToggle) {
                return;
            }

            if (!pageSlugAutofillToggle.checked && !force) {
                return;
            }

            if (slugEditedManually && !force) {
                return;
            }

            pageSlugInput.value = slugify(pageTitleInput.value || '');
        }

        function isSidebarOpen(sidebarName) {
            const sidebar = sidebarName === 'widgets' ? widgetSidebar : settingsSidebar;
            return !!(sidebar && sidebar.classList.contains('is-open'));
        }

        function syncSidebarButtons() {
            const sidebarMap = {
                widgets: widgetSidebar,
                settings: settingsSidebar,
            };

            Object.keys(sidebarMap).forEach(function (sidebarName) {
                const isOpen = isSidebarOpen(sidebarName);
                const sidebar = sidebarMap[sidebarName];

                if (sidebar) {
                    sidebar.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
                }

                sidebarToggles.forEach(function (button) {
                    if (button.getAttribute('data-sidebar-toggle') !== sidebarName) {
                        return;
                    }

                    button.classList.toggle('is-active', isOpen);
                    button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            });
        }

        function setSidebarState(sidebarName, shouldOpen) {
            const sidebar = sidebarName === 'widgets' ? widgetSidebar : settingsSidebar;
            if (!sidebar) {
                return;
            }

            sidebar.classList.toggle('is-open', shouldOpen);
            sidebar.setAttribute('aria-hidden', shouldOpen ? 'false' : 'true');

            if (sidebarName === 'settings' && !shouldOpen) {
                closeSettingsPortal();
            }

            syncSidebarButtons();
        }

        function toggleSidebarState(sidebarName) {
            setSidebarState(sidebarName, !isSidebarOpen(sidebarName));
        }

        function ensureSidebarOpen(sidebarName) {
            if (!isSidebarOpen(sidebarName)) {
                setSidebarState(sidebarName, true);
            }
        }

        function clampSettingsSidebarWidth(width) {
            const minWidth = 320;
            const maxWidth = Math.max(minWidth, Math.min(Math.round(window.innerWidth * 0.68), 760));

            return Math.max(minWidth, Math.min(width, maxWidth));
        }

        function setSettingsSidebarWidth(width) {
            if (!settingsSidebar || window.innerWidth <= 767.98) {
                return;
            }

            settingsSidebar.style.width = clampSettingsSidebarWidth(width) + 'px';
        }

        function closeSettingsPortal() {
            if (activePortalOwner && activePortalElement) {
                activePortalOwner.classList.remove('is-active-editing');

                const isWidget = activePortalOwner.hasAttribute('data-widget-card');
                const selector = isWidget ? '[data-widget-settings-portal]' : '[data-section-settings-portal]';
                const portalSource = activePortalOwner.querySelector(selector);
                if (portalSource) {
                    portalSource.appendChild(activePortalElement);
                }
            }

            activePortalOwner = null;
            activePortalElement = null;

            if (activeSettingsHeader) {
                activeSettingsHeader.classList.add('d-none');
            }
            if (activeSettingsPortalTarget) {
                activeSettingsPortalTarget.classList.add('d-none');
                activeSettingsPortalTarget.innerHTML = '';
            }
            if (defaultPageSettings) {
                defaultPageSettings.classList.remove('d-none');
            }
        }

        function openSettingsPortal(ownerElement, portalSelector, titleText) {
            closeSettingsPortal();

            const portalSource = ownerElement.querySelector(portalSelector);
            if (!portalSource) {
                return;
            }

            const settingsDiv = portalSource.firstElementChild;
            if (!settingsDiv) {
                return;
            }

            activePortalOwner = ownerElement;
            activePortalElement = settingsDiv;
            ownerElement.classList.add('is-active-editing');
            ensureSidebarOpen('settings');

            if (activeSettingsPortalTarget) {
                activeSettingsPortalTarget.appendChild(settingsDiv);
                activeSettingsPortalTarget.classList.remove('d-none');
            }

            if (activeSettingsTitle) {
                activeSettingsTitle.textContent = titleText;
            }
            if (activeSettingsHeader) {
                activeSettingsHeader.classList.remove('d-none');
            }
            if (defaultPageSettings) {
                defaultPageSettings.classList.add('d-none');
            }

            refreshPlugins();
        }

        let isResizingSettingsSidebar = false;

        function beginSettingsSidebarResize(event) {
            if (!settingsSidebar || window.innerWidth <= 767.98) {
                return;
            }

            isResizingSettingsSidebar = true;
            settingsSidebar.classList.add('is-resizing');
            document.body.classList.add('ttf-is-resizing-sidebar');
            event.preventDefault();
        }

        function handleSettingsSidebarResize(event) {
            if (!isResizingSettingsSidebar || !settingsSidebar) {
                return;
            }

            const sidebarRect = settingsSidebar.getBoundingClientRect();
            const rightOffset = Math.max(0, window.innerWidth - sidebarRect.right);
            setSettingsSidebarWidth(window.innerWidth - event.clientX - rightOffset);
        }

        function endSettingsSidebarResize() {
            if (!isResizingSettingsSidebar || !settingsSidebar) {
                return;
            }

            isResizingSettingsSidebar = false;
            settingsSidebar.classList.remove('is-resizing');
            document.body.classList.remove('ttf-is-resizing-sidebar');
        }

        function syncGroupEmptyState() {
            if (!sectionGroups || !groupEmptyState) {
                return;
            }

            groupEmptyState.classList.toggle('d-none', sectionGroups.querySelector('[data-group-card]') !== null);
        }

        function syncWidgetEmptyState(groupCard) {
            if (!groupCard) {
                return;
            }

            groupCard.querySelectorAll('[data-widget-container]').forEach(function (widgetContainer) {
                const column = widgetContainer.closest('[data-admin-column]');
                const widgetEmptyState = column ? column.querySelector('[data-widget-empty-state]') : null;
                if (widgetEmptyState) {
                    widgetEmptyState.classList.toggle('d-none', widgetContainer.querySelector('[data-widget-card]') !== null);
                }
            });
        }

        function clearWidgetDropState() {
            document.querySelectorAll('[data-widget-container].is-drop-zone').forEach(function (container) {
                container.classList.remove('is-drop-zone');
            });
        }

        function getOwnerScope(owner) {
            if (owner && activePortalOwner === owner && activePortalElement) {
                return activePortalElement;
            }

            return owner;
        }

        function setExpanded(card, bodySelector, buttonSelector, expanded) {
            if (!card) {
                return;
            }

            const body = card.querySelector(bodySelector);
            const button = card.querySelector(buttonSelector);

            if (body) {
                body.classList.toggle('d-none', !expanded);
            }

            card.classList.toggle('is-expanded', expanded);

            if (button) {
                button.textContent = expanded
                    ? (button.getAttribute('data-label-close') || 'Collapse')
                    : (button.getAttribute('data-label-open') || 'Expand');
            }
        }

        function syncAppearance(card) {
            if (!card) {
                return;
            }

            const scope = getOwnerScope(card);
            const backgroundToggle = scope.querySelector('[data-style-toggle="background"]');
            const borderToggle = scope.querySelector('[data-style-toggle="border"]');
            const paddingToggle = scope.querySelector('[data-style-toggle="padding"]');
            const backgroundEnabled = backgroundToggle ? backgroundToggle.checked : false;
            const borderEnabled = borderToggle ? borderToggle.checked : false;
            const paddingEnabled = paddingToggle ? paddingToggle.checked : false;

            scope.querySelectorAll('[data-style-target="background"]').forEach(function (element) {
                element.classList.toggle('d-none', !backgroundEnabled);
            });
            scope.querySelectorAll('[data-style-target="border"]').forEach(function (element) {
                element.classList.toggle('d-none', !borderEnabled);
            });
            scope.querySelectorAll('[data-style-target="padding"]').forEach(function (element) {
                element.classList.toggle('d-none', !paddingEnabled);
            });
            scope.querySelectorAll('[data-style-target="radius"]').forEach(function (element) {
                element.classList.toggle('d-none', !(backgroundEnabled || borderEnabled));
            });
        }

        function syncToggleFields(card) {
            if (!card) {
                return;
            }

            const scope = getOwnerScope(card);
            scope.querySelectorAll('[data-toggle-field]').forEach(function (toggle) {
                const fieldName = toggle.getAttribute('data-toggle-field');
                if (!fieldName) {
                    return;
                }

                scope.querySelectorAll('[data-toggle-target="' + fieldName + '"]').forEach(function (target) {
                    target.classList.toggle('d-none', !toggle.checked);
                });
            });
        }

        function syncVisibilitySummary(card, summarySelector) {
            if (!card) {
                return;
            }

            const summary = card.querySelector(summarySelector);
            if (!summary) {
                return;
            }

            const scope = getOwnerScope(card);
            const labels = [];
            const devices = {
                desktop: 'Desktop',
                ipad_pro: 'iPad Pro',
                ipad: 'iPad',
                phone: 'Phone'
            };

            Object.keys(devices).forEach(function (deviceKey) {
                const toggle = scope.querySelector('[data-visibility-toggle="' + deviceKey + '"]');
                if (toggle && toggle.checked) {
                    labels.push(devices[deviceKey]);
                }
            });

            if (labels.length === 4) {
                summary.textContent = 'All Devices';
                return;
            }

            if (labels.length === 0) {
                summary.textContent = 'Hidden Everywhere';
                return;
            }

            summary.textContent = labels.join(', ');
        }

        function syncGroupLabel(groupCard) {
            if (!groupCard) {
                return;
            }

            const input = getOwnerScope(groupCard).querySelector('[data-group-name-input]');
            const label = groupCard.querySelector('[data-group-label]');

            if (label) {
                label.textContent = input && input.value.trim() !== '' ? input.value.trim() : 'Untitled Section';
            }
        }

        function getWidgetTypeLabel(widgetCard) {
            const typeInput = widgetCard ? widgetCard.querySelector('input[name*="[type]"]') : null;
            const labels = {
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

            return labels[typeInput ? typeInput.value : 'rich_text'] || 'Widget';
        }

        function syncWidgetLabel(widgetCard) {
            if (!widgetCard) {
                return;
            }

            const scope = getOwnerScope(widgetCard);
            const headingInput = scope.querySelector('[data-widget-heading-input]');
            const subheadingInput = scope.querySelector('[data-widget-subheading-input]');
            const label = widgetCard.querySelector('[data-widget-label]');
            const preview = widgetCard.querySelector('[data-widget-preview]');
            const fallback = getWidgetTypeLabel(widgetCard);

            if (label) {
                label.textContent = fallback;
            }

            if (preview) {
                const heading = headingInput ? headingInput.value.trim() : '';
                const subheading = subheadingInput ? subheadingInput.value.trim() : '';
                preview.textContent = heading || subheading || fallback;
            }

            if (activePortalOwner === widgetCard && activeSettingsTitle) {
                activeSettingsTitle.textContent = 'Widget: ' + fallback;
            }
        }

        function syncWidgetCount(groupCard) {
            if (!groupCard) {
                return;
            }

            const countLabel = groupCard.querySelector('[data-group-widget-count]');
            const widgetCount = groupCard.querySelectorAll('[data-widget-card]').length;

            if (countLabel) {
                countLabel.textContent = widgetCount + ' Widgets';
            }
        }

        function replaceGroupIndex(groupCard, oldIndex, newIndex) {
            if (String(oldIndex) === String(newIndex)) {
                return;
            }

            const pattern = new RegExp('builder\\[sections\\]\\[' + oldIndex + '\\]', 'g');

            groupCard.querySelectorAll('[name]').forEach(function (field) {
                field.name = field.name.replace(pattern, 'builder[sections][' + newIndex + ']');
            });

            groupCard.querySelectorAll('template').forEach(function (template) {
                template.innerHTML = template.innerHTML.replace(pattern, 'builder[sections][' + newIndex + ']');
            });
        }

        function replaceWidgetIndex(groupCard, widgetCard, oldIndex, newIndex) {
            if (String(oldIndex) === String(newIndex)) {
                return;
            }

            const groupIndex = groupCard.getAttribute('data-group-index');
            const pattern = new RegExp('builder\\[sections\\]\\[' + groupIndex + '\\]\\[widgets\\]\\[' + oldIndex + '\\]', 'g');

            widgetCard.querySelectorAll('[name]').forEach(function (field) {
                field.name = field.name.replace(pattern, 'builder[sections][' + groupIndex + '][widgets][' + newIndex + ']');
            });

            widgetCard.querySelectorAll('template').forEach(function (template) {
                template.innerHTML = template.innerHTML.replace(pattern, 'builder[sections][' + groupIndex + '][widgets][' + newIndex + ']');
            });
        }

        function replaceRepeaterIndex(itemRow, oldIndex, newIndex) {
            if (String(oldIndex) === String(newIndex)) {
                return;
            }

            const pattern = new RegExp('\\[items\\]\\[' + oldIndex + '\\]', 'g');

            itemRow.querySelectorAll('[name]').forEach(function (field) {
                field.name = field.name.replace(pattern, '[items][' + newIndex + ']');
            });
        }

        function reindexRepeater(container) {
            if (!container) {
                return;
            }

            const rows = container.querySelectorAll('[data-item-row]');
            rows.forEach(function (row, index) {
                const oldIndex = row.getAttribute('data-item-index') || String(index);
                replaceRepeaterIndex(row, oldIndex, index);
                row.setAttribute('data-item-index', String(index));
            });
            container.setAttribute('data-next-index', String(rows.length));
        }

        function reindexAllRepeaters(scope) {
            if (!scope) {
                return;
            }

            scope.querySelectorAll('[data-item-target]').forEach(function (container) {
                reindexRepeater(container);
            });
        }

        function reindexWidgets(groupCard) {
            if (!groupCard) {
                return;
            }

            let widgetIndex = 0;
            const containers = groupCard.querySelectorAll('[data-widget-container]');
            if (containers.length === 0) {
                return;
            }

            containers.forEach(function (container) {
                const columnIndex = container.getAttribute('data-column-index') || '0';

                container.querySelectorAll('[data-widget-card]').forEach(function (widgetCard) {
                    const oldIndex = widgetCard.getAttribute('data-widget-index');
                    replaceWidgetIndex(groupCard, widgetCard, oldIndex, widgetIndex);
                    widgetCard.setAttribute('data-widget-index', String(widgetIndex));

                    const colInput = widgetCard.querySelector('[data-widget-column-input]');
                    if (colInput) {
                        colInput.value = columnIndex;
                    }

                    reindexAllRepeaters(widgetCard);
                    widgetIndex++;
                });
            });

            syncWidgetCount(groupCard);
            syncWidgetEmptyState(groupCard);
        }

        function reindexGroups() {
            if (!sectionGroups) {
                return;
            }

            sectionGroups.querySelectorAll('[data-group-card]').forEach(function (groupCard, groupIndex) {
                const oldIndex = groupCard.getAttribute('data-group-index');
                replaceGroupIndex(groupCard, oldIndex, groupIndex);
                groupCard.setAttribute('data-group-index', String(groupIndex));
                reindexWidgets(groupCard);
            });

            sectionGroups.setAttribute('data-next-group-index', String(sectionGroups.querySelectorAll('[data-group-card]').length));
            syncGroupEmptyState();
        }

        function initializeGroupCard(groupCard, expanded) {
            syncGroupLabel(groupCard);
            syncVisibilitySummary(groupCard, '[data-group-visibility-summary]');
            syncAppearance(groupCard);
            syncWidgetCount(groupCard);
            syncWidgetEmptyState(groupCard);
            setExpanded(groupCard, '[data-group-body]', '[data-toggle-group]', expanded);
            groupCard.querySelectorAll('[data-widget-card]').forEach(function (widgetCard) {
                initializeWidgetCard(widgetCard, false);
            });
        }

        function initializeWidgetCard(widgetCard, expanded) {
            syncWidgetLabel(widgetCard);
            syncVisibilitySummary(widgetCard, '[data-widget-visibility-summary]');
            syncAppearance(widgetCard);
            syncToggleFields(widgetCard);
            reindexAllRepeaters(widgetCard);
            setExpanded(widgetCard, '[data-widget-body]', '[data-toggle-widget]', expanded);
        }

        function syncEditorValues(scope) {
            if (!scope || !window.jQuery || !window.jQuery.fn || !window.jQuery.fn.summernote) {
                return;
            }

            window.jQuery(scope).find('.aiz-text-editor').each(function () {
                const $textarea = window.jQuery(this);
                if ($textarea.next('.note-editor').length) {
                    $textarea.val($textarea.summernote('code'));
                }
            });
        }

        function copyFormState(source, clone) {
            if (!source || !clone) {
                return;
            }

            const selectors = 'input, select, textarea';
            const sourceFields = source.querySelectorAll(selectors);
            const cloneFields = clone.querySelectorAll(selectors);

            sourceFields.forEach(function (field, index) {
                const cloneField = cloneFields[index];
                if (!cloneField) {
                    return;
                }

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
            if (!scope) {
                return;
            }

            scope.querySelectorAll('.note-editor').forEach(function (editor) {
                editor.remove();
            });

            scope.querySelectorAll('.aiz-text-editor').forEach(function (textarea) {
                textarea.style.display = '';
            });
        }

        function duplicateGroup(groupCard) {
            if (!groupCard || !sectionGroups) {
                return;
            }

            syncEditorValues(groupCard);
            closeSettingsPortal();

            const clone = groupCard.cloneNode(true);
            copyFormState(groupCard, clone);
            cleanupClonedEditors(clone);
            groupCard.insertAdjacentElement('afterend', clone);
            initializeGroupCard(clone, true);
            reindexGroups();
            refreshPlugins();
        }

        function duplicateWidget(widgetCard) {
            if (!widgetCard) {
                return;
            }

            const groupCard = widgetCard.closest('[data-group-card]');
            if (!groupCard) {
                return;
            }

            syncEditorValues(widgetCard);
            closeSettingsPortal();

            const clone = widgetCard.cloneNode(true);
            copyFormState(widgetCard, clone);
            cleanupClonedEditors(clone);
            widgetCard.insertAdjacentElement('afterend', clone);
            initializeWidgetCard(clone, true);
            reindexWidgets(groupCard);
            refreshPlugins();
        }

        function addGroup() {
            if (!sectionGroups || !groupTemplate) {
                return;
            }

            const groupIndex = Number(sectionGroups.getAttribute('data-next-group-index') || 0);
            const html = groupTemplate.innerHTML.split('__GROUP_INDEX__').join(groupIndex);
            sectionGroups.insertAdjacentHTML('beforeend', html);
            sectionGroups.setAttribute('data-next-group-index', String(groupIndex + 1));

            const groupCard = sectionGroups.querySelector('[data-group-card]:last-child');
            initializeGroupCard(groupCard, true);
            reindexGroups();
            refreshPlugins();
        }

        function addItem(button, itemType) {
            const widgetCard = button.closest('[data-widget-card]') || (activePortalOwner && activePortalOwner.hasAttribute('data-widget-card') ? activePortalOwner : null);
            const scope = widgetCard ? getOwnerScope(widgetCard) : null;
            const target = scope ? scope.querySelector('[data-item-target="' + itemType + '"]') : null;
            const template = scope ? scope.querySelector('template[data-item-template="' + itemType + '"]') : null;

            if (!target || !template) {
                return;
            }

            const itemIndex = Number(target.getAttribute('data-next-index') || 0);
            const html = template.innerHTML.split('__ITEM_INDEX__').join(itemIndex);
            target.insertAdjacentHTML('beforeend', html);
            reindexRepeater(target);
            refreshPlugins();
        }

        function moveElementBeforeCurrent(element) {
            if (!element || !element.previousElementSibling) {
                return false;
            }

            element.parentNode.insertBefore(element, element.previousElementSibling);
            return true;
        }

        function moveElementAfterCurrent(element) {
            if (!element || !element.nextElementSibling) {
                return false;
            }

            element.parentNode.insertBefore(element.nextElementSibling, element);
            return true;
        }

        function getDragAfterWidget(container, clientY) {
            const draggableElements = Array.from(container.querySelectorAll('[data-widget-card]:not(.is-dragging)'));

            return draggableElements.reduce(function (closest, child) {
                const box = child.getBoundingClientRect();
                const offset = clientY - box.top - box.height / 2;

                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                }

                return closest;
            }, { offset: Number.NEGATIVE_INFINITY, element: null }).element;
        }

        function getDragAfterGroup(container, clientY) {
            const draggableElements = Array.from(container.querySelectorAll('[data-group-card]:not(.is-dragging)'));

            return draggableElements.reduce(function (closest, child) {
                const box = child.getBoundingClientRect();
                const offset = clientY - box.top - box.height / 2;

                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                }

                return closest;
            }, { offset: Number.NEGATIVE_INFINITY, element: null }).element;
        }

        document.addEventListener('input', function (event) {
            if (event.target === pageTitleInput) {
                syncSlugFromTitle(false);
            }

            if (event.target === pageSlugInput) {
                slugEditedManually = true;
                if (pageSlugAutofillToggle) {
                    pageSlugAutofillToggle.checked = false;
                }
            }

            if (event.target.matches('[data-group-name-input]')) {
                const groupCard = event.target.closest('[data-group-card]') || (activePortalOwner && activePortalOwner.hasAttribute('data-group-card') ? activePortalOwner : null);
                syncGroupLabel(groupCard);
                if (activePortalOwner === groupCard && activeSettingsTitle) {
                    activeSettingsTitle.textContent = 'Section: ' + (event.target.value.trim() || 'Untitled Section');
                }
            }

            if (event.target.matches('[data-widget-heading-input], [data-widget-subheading-input]')) {
                syncWidgetLabel(event.target.closest('[data-widget-card]') || (activePortalOwner && activePortalOwner.hasAttribute('data-widget-card') ? activePortalOwner : null));
            }

            if (event.target.matches('[data-toc-title-input]')) {
                const row = event.target.closest('[data-item-row]');
                const anchorInput = row ? row.querySelector('[data-toc-anchor-input]') : null;
                if (anchorInput && anchorInput.value.trim() === '') {
                    anchorInput.value = slugify(event.target.value);
                }
            }
        });

        document.addEventListener('change', function (event) {
            const groupCard = event.target.closest('[data-group-card]') || (activePortalOwner && activePortalOwner.hasAttribute('data-group-card') ? activePortalOwner : null);
            const widgetCard = event.target.closest('[data-widget-card]') || (activePortalOwner && activePortalOwner.hasAttribute('data-widget-card') ? activePortalOwner : null);

            if (event.target === pageSlugAutofillToggle) {
                slugEditedManually = !event.target.checked;
                if (event.target.checked) {
                    syncSlugFromTitle(true);
                }
            }

            if (event.target.matches('[data-style-toggle]')) {
                syncAppearance(widgetCard || groupCard || activePortalOwner);
            }

            if (event.target.matches('[data-toggle-field]')) {
                syncToggleFields(widgetCard || groupCard || activePortalOwner);
                syncWidgetLabel(widgetCard || (activePortalOwner && activePortalOwner.hasAttribute('data-widget-card') ? activePortalOwner : null));
            }

            if (event.target.matches('[data-visibility-toggle]')) {
                const targetOwner = widgetCard || groupCard || activePortalOwner;
                if (targetOwner) {
                    const isWidget = targetOwner.hasAttribute('data-widget-card');
                    syncVisibilitySummary(targetOwner, isWidget ? '[data-widget-visibility-summary]' : '[data-group-visibility-summary]');
                }
            }

            if (event.target.matches('[data-group-columns-select]')) {
                const select = event.target;
                const columnsGrid = groupCard ? groupCard.querySelector('[data-columns-grid]') : null;
                if (!groupCard || !columnsGrid) {
                    return;
                }

                const oldColumnsCount = columnsGrid.querySelectorAll('[data-admin-column]').length;
                const newColumnsCount = parseInt(select.value, 10) || 1;
                if (oldColumnsCount === newColumnsCount) {
                    return;
                }

                columnsGrid.className = 'ttf-admin-columns-grid ttf-columns-count-' + newColumnsCount;

                if (newColumnsCount > oldColumnsCount) {
                    for (let col = oldColumnsCount; col < newColumnsCount; col++) {
                        const colHtml = `
                            <div class="ttf-admin-column" data-admin-column="${col}">
                                <div class="ttf-admin-column-header">
                                    <span>Column ${col + 1}</span>
                                </div>
                                <div class="ttf-widget-list" data-widget-container data-column-index="${col}"></div>
                                <div class="ttf-sections-empty-state" data-widget-empty-state>
                                    <small class="text-muted">Empty column. Drag or add a widget here.</small>
                                </div>
                            </div>
                        `;
                        columnsGrid.insertAdjacentHTML('beforeend', colHtml);
                    }
                } else {
                    const targetContainer = columnsGrid.querySelector('[data-admin-column="' + (newColumnsCount - 1) + '"] [data-widget-container]');

                    for (let col = newColumnsCount; col < oldColumnsCount; col++) {
                        const sourceColumn = columnsGrid.querySelector('[data-admin-column="' + col + '"]');
                        if (sourceColumn) {
                            const sourceContainer = sourceColumn.querySelector('[data-widget-container]');
                            if (sourceContainer && targetContainer) {
                                sourceContainer.querySelectorAll('[data-widget-card]').forEach(function (widgetCardEl) {
                                    targetContainer.appendChild(widgetCardEl);
                                });
                            }
                            sourceColumn.remove();
                        }
                    }
                }

                reindexWidgets(groupCard);
                refreshPlugins();
            }
        });

        document.addEventListener('click', function (event) {
            const sidebarToggleButton = event.target.closest('[data-sidebar-toggle]');
            if (sidebarToggleButton) {
                toggleSidebarState(sidebarToggleButton.getAttribute('data-sidebar-toggle'));
                return;
            }

            const sidebarCloseButton = event.target.closest('[data-sidebar-close]');
            if (sidebarCloseButton) {
                setSidebarState(sidebarCloseButton.getAttribute('data-sidebar-close'), false);
                return;
            }

            const closeSettingsBtn = event.target.closest('#close-active-settings');
            if (closeSettingsBtn) {
                closeSettingsPortal();
                return;
            }

            const editSectionSettingsBtn = event.target.closest('[data-edit-section-settings]');
            if (editSectionSettingsBtn) {
                const groupCard = editSectionSettingsBtn.closest('[data-group-card]');
                if (groupCard) {
                    const groupNameInput = groupCard.querySelector('[data-group-name-input]');
                    const groupTitle = groupNameInput ? groupNameInput.value.trim() : 'Untitled Section';
                    openSettingsPortal(groupCard, '[data-section-settings-portal]', 'Section: ' + (groupTitle || 'Untitled Section'));
                }
                return;
            }

            const editWidgetSettingsBtn = event.target.closest('[data-edit-widget-settings]');
            if (editWidgetSettingsBtn) {
                const widgetCard = editWidgetSettingsBtn.closest('[data-widget-card]');
                if (widgetCard) {
                    openSettingsPortal(widgetCard, '[data-widget-settings-portal]', 'Widget: ' + getWidgetTypeLabel(widgetCard));
                }
                return;
            }

            const addGroupButton = event.target.closest('[data-add-group]');
            if (addGroupButton) {
                closeSettingsPortal();
                addGroup();
                return;
            }

            const moveGroupUpButton = event.target.closest('[data-move-group-up]');
            if (moveGroupUpButton) {
                const groupCard = moveGroupUpButton.closest('[data-group-card]');
                closeSettingsPortal();
                if (moveElementBeforeCurrent(groupCard)) {
                    reindexGroups();
                }
                return;
            }

            const moveGroupDownButton = event.target.closest('[data-move-group-down]');
            if (moveGroupDownButton) {
                const groupCard = moveGroupDownButton.closest('[data-group-card]');
                closeSettingsPortal();
                if (moveElementAfterCurrent(groupCard)) {
                    reindexGroups();
                }
                return;
            }

            const copyGroupButton = event.target.closest('[data-copy-group]');
            if (copyGroupButton) {
                duplicateGroup(copyGroupButton.closest('[data-group-card]'));
                return;
            }

            const moveWidgetUpButton = event.target.closest('[data-move-widget-up]');
            if (moveWidgetUpButton) {
                const widgetCard = moveWidgetUpButton.closest('[data-widget-card]');
                const groupCard = moveWidgetUpButton.closest('[data-group-card]');
                closeSettingsPortal();
                if (moveElementBeforeCurrent(widgetCard)) {
                    reindexWidgets(groupCard);
                }
                return;
            }

            const moveWidgetDownButton = event.target.closest('[data-move-widget-down]');
            if (moveWidgetDownButton) {
                const widgetCard = moveWidgetDownButton.closest('[data-widget-card]');
                const groupCard = moveWidgetDownButton.closest('[data-group-card]');
                closeSettingsPortal();
                if (moveElementAfterCurrent(widgetCard)) {
                    reindexWidgets(groupCard);
                }
                return;
            }

            const copyWidgetButton = event.target.closest('[data-copy-widget]');
            if (copyWidgetButton) {
                duplicateWidget(copyWidgetButton.closest('[data-widget-card]'));
                return;
            }

            const toggleGroupButton = event.target.closest('[data-toggle-group]');
            if (toggleGroupButton) {
                const groupCard = toggleGroupButton.closest('[data-group-card]');
                setExpanded(groupCard, '[data-group-body]', '[data-toggle-group]', !groupCard.classList.contains('is-expanded'));
                return;
            }

            const removeGroupButton = event.target.closest('[data-remove-group]');
            if (removeGroupButton) {
                const groupCard = removeGroupButton.closest('[data-group-card]');
                if (groupCard) {
                    closeSettingsPortal();
                    groupCard.remove();
                    reindexGroups();
                }
                return;
            }

            const toggleWidgetButton = event.target.closest('[data-toggle-widget]');
            if (toggleWidgetButton) {
                const widgetCard = toggleWidgetButton.closest('[data-widget-card]');
                setExpanded(widgetCard, '[data-widget-body]', '[data-toggle-widget]', !widgetCard.classList.contains('is-expanded'));
                return;
            }

            const removeWidgetButton = event.target.closest('[data-remove-widget]');
            if (removeWidgetButton) {
                const widgetCard = removeWidgetButton.closest('[data-widget-card]');
                const groupCard = removeWidgetButton.closest('[data-group-card]');
                if (widgetCard) {
                    closeSettingsPortal();
                    widgetCard.remove();
                    reindexWidgets(groupCard);
                }
                return;
            }

            const addItemButton = event.target.closest('[data-add-item]');
            if (addItemButton) {
                addItem(addItemButton, addItemButton.getAttribute('data-add-item'));
                return;
            }

            const moveItemUpButton = event.target.closest('[data-move-item-up]');
            if (moveItemUpButton) {
                const itemRow = moveItemUpButton.closest('[data-item-row]');
                const repeater = moveItemUpButton.closest('[data-item-target]');
                if (moveElementBeforeCurrent(itemRow)) {
                    reindexRepeater(repeater);
                }
                return;
            }

            const moveItemDownButton = event.target.closest('[data-move-item-down]');
            if (moveItemDownButton) {
                const itemRow = moveItemDownButton.closest('[data-item-row]');
                const repeater = moveItemDownButton.closest('[data-item-target]');
                if (moveElementAfterCurrent(itemRow)) {
                    reindexRepeater(repeater);
                }
                return;
            }

            const removeItemButton = event.target.closest('[data-remove-item]');
            if (removeItemButton) {
                const itemRow = removeItemButton.closest('[data-item-row]');
                const repeater = removeItemButton.closest('[data-item-target]');
                if (itemRow) {
                    itemRow.remove();
                    reindexRepeater(repeater);
                }
                return;
            }

            if (
                isSidebarOpen('settings') &&
                settingsSidebar &&
                !event.target.closest('[data-builder-sidebar="settings"]') &&
                !event.target.closest('[data-sidebar-toggle="settings"]') &&
                !event.target.closest('#aizUploaderModal') &&
                !event.target.closest('.modal') &&
                !event.target.closest('.modal-backdrop') &&
                !event.target.closest('.aiz-uploader-all') &&
                !event.target.closest('.dropdown-menu')
            ) {
                setSidebarState('settings', false);
            }
        });

        document.addEventListener('mousedown', function (event) {
            const widgetDragHandle = event.target.closest('[data-widget-drag-handle]');
            if (widgetDragHandle) {
                const widgetCard = widgetDragHandle.closest('[data-widget-card]');
                if (widgetCard) {
                    widgetCard.setAttribute('draggable', 'true');
                }
                return;
            }

            const dragHandle = event.target.closest('[data-group-drag-handle]');
            if (!dragHandle) {
                return;
            }

            const groupCard = dragHandle.closest('[data-group-card]');
            if (groupCard) {
                groupCard.setAttribute('draggable', 'true');
            }
        });

        document.addEventListener('mouseup', function () {
            document.querySelectorAll('[data-widget-card][draggable="true"]').forEach(function (widgetCard) {
                if (!widgetCard.classList.contains('is-dragging')) {
                    widgetCard.setAttribute('draggable', 'false');
                }
            });

            document.querySelectorAll('[data-group-card][draggable="true"]').forEach(function (groupCard) {
                if (!groupCard.classList.contains('is-dragging')) {
                    groupCard.setAttribute('draggable', 'false');
                }
            });

            endSettingsSidebarResize();
        });

        document.addEventListener('dragstart', function (event) {
            closeSettingsPortal();

            const sidebarWidget = event.target.closest('[data-sidebar-widget]');
            if (sidebarWidget) {
                draggedSidebarWidgetType = sidebarWidget.getAttribute('data-sidebar-widget');
                sidebarWidget.classList.add('is-dragging');
                event.dataTransfer.effectAllowed = 'move';
                return;
            }

            const widgetCard = event.target.closest('[data-widget-card]');
            if (widgetCard) {
                draggedWidgetCard = widgetCard;
                widgetCard.classList.add('is-dragging');
                event.dataTransfer.effectAllowed = 'move';
                return;
            }

            const groupCard = event.target.closest('[data-group-card]');
            if (groupCard && event.target.matches('[data-group-card]')) {
                draggedGroupCard = groupCard;
                groupCard.classList.add('is-dragging');
                event.dataTransfer.effectAllowed = 'move';
            }
        });

        document.addEventListener('dragend', function () {
            document.querySelectorAll('[data-sidebar-widget].is-dragging').forEach(function (el) {
                el.classList.remove('is-dragging');
            });
            draggedSidebarWidgetType = null;
            clearWidgetDropState();

            if (draggedWidgetCard) {
                draggedWidgetCard.classList.remove('is-dragging');
                draggedWidgetCard.setAttribute('draggable', 'false');

                const groupCard = draggedWidgetCard.closest('[data-group-card]');
                if (groupCard) {
                    reindexWidgets(groupCard);
                }

                draggedWidgetCard = null;
                reindexGroups();
                return;
            }

            if (draggedGroupCard) {
                draggedGroupCard.classList.remove('is-dragging');
                draggedGroupCard.setAttribute('draggable', 'false');
                draggedGroupCard = null;
                reindexGroups();
            }
        });

        if (sectionGroups) {
            sectionGroups.addEventListener('dragover', function (event) {
                if (draggedWidgetCard || draggedSidebarWidgetType) {
                    event.preventDefault();
                    let widgetContainer = event.target.closest('[data-widget-container]');
                    if (!widgetContainer) {
                        const groupCard = event.target.closest('[data-group-card]');
                        widgetContainer = groupCard ? groupCard.querySelector('[data-widget-container]') : null;
                    }

                    if (!widgetContainer) {
                        return;
                    }

                    clearWidgetDropState();
                    widgetContainer.classList.add('is-drop-zone');

                    if (draggedWidgetCard) {
                        const afterElement = getDragAfterWidget(widgetContainer, event.clientY);
                        if (!afterElement) {
                            widgetContainer.appendChild(draggedWidgetCard);
                        } else {
                            widgetContainer.insertBefore(draggedWidgetCard, afterElement);
                        }
                    }
                    return;
                }

                if (!draggedGroupCard) {
                    return;
                }

                event.preventDefault();
                const afterElement = getDragAfterGroup(sectionGroups, event.clientY);

                if (!afterElement) {
                    sectionGroups.appendChild(draggedGroupCard);
                    return;
                }

                sectionGroups.insertBefore(draggedGroupCard, afterElement);
            });

            sectionGroups.addEventListener('drop', function (event) {
                event.preventDefault();
                let widgetContainer = event.target.closest('[data-widget-container]');
                if (!widgetContainer) {
                    const groupCard = event.target.closest('[data-group-card]');
                    widgetContainer = groupCard ? groupCard.querySelector('[data-widget-container]') : null;
                }

                if (!widgetContainer) {
                    return;
                }

                const groupCard = widgetContainer.closest('[data-group-card]');
                if (!groupCard) {
                    return;
                }

                if (draggedSidebarWidgetType) {
                    const widgetType = draggedSidebarWidgetType;
                    const widgetTemplate = groupCard.querySelector('template[data-widget-template="' + widgetType + '"]');
                    if (!widgetTemplate) {
                        return;
                    }

                    const widgetIndex = Number(widgetContainer.querySelectorAll('[data-widget-card]').length);
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = widgetTemplate.innerHTML.split('__WIDGET_INDEX__').join(widgetIndex);
                    const widgetCard = tempDiv.firstElementChild;

                    const afterElement = getDragAfterWidget(widgetContainer, event.clientY);
                    if (!afterElement) {
                        widgetContainer.appendChild(widgetCard);
                    } else {
                        widgetContainer.insertBefore(widgetCard, afterElement);
                    }

                    initializeWidgetCard(widgetCard, true);
                    reindexWidgets(groupCard);
                    refreshPlugins();
                }
            });
        }

        if (pageForm) {
            pageForm.addEventListener('submit', function () {
                syncEditorValues(pageForm);
                closeSettingsPortal();
                reindexGroups();
            });
        }

        if (settingsSidebarResizer) {
            settingsSidebarResizer.addEventListener('mousedown', beginSettingsSidebarResize);
        }

        document.addEventListener('mousemove', handleSettingsSidebarResize);

        window.addEventListener('resize', function () {
            if (!settingsSidebar) {
                return;
            }

            if (window.innerWidth <= 767.98) {
                settingsSidebar.style.removeProperty('width');
                return;
            }

            const currentWidth = parseInt(settingsSidebar.style.width || settingsSidebar.offsetWidth, 10);
            if (!Number.isNaN(currentWidth)) {
                setSettingsSidebarWidth(currentWidth);
            }
        });

        document.querySelectorAll('[data-group-card]').forEach(function (groupCard) {
            initializeGroupCard(groupCard, false);
        });

        syncSlugFromTitle(false);
        reindexGroups();
        syncGroupEmptyState();
        syncSidebarButtons();
        refreshPlugins();
    })();
</script>
