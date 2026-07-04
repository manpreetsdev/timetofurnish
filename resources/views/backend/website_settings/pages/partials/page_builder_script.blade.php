<script>
    (function () {
        const sectionGroups = document.getElementById('ttf-section-groups');
        const groupEmptyState = document.querySelector('[data-group-empty-state]');
        const groupTemplate = document.querySelector('template[data-group-template]');
        const pageForm = document.querySelector('.ttf-admin-page-form');
        const pageBuilderLayout = document.querySelector('[data-page-builder-layout]');
        let draggedGroupCard = null;
        let draggedWidgetCard = null;

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

        function syncGroupEmptyState() {
            if (!sectionGroups || !groupEmptyState) {
                return;
            }

            groupEmptyState.classList.toggle('d-none', sectionGroups.querySelector('[data-group-card]') !== null);
        }

        function syncWidgetEmptyState(groupCard) {
            const widgetEmptyState = groupCard.querySelector('[data-widget-empty-state]');
            const widgetContainer = groupCard.querySelector('[data-widget-container]');

            if (!widgetEmptyState || !widgetContainer) {
                return;
            }

            widgetEmptyState.classList.toggle('d-none', widgetContainer.querySelector('[data-widget-card]') !== null);
        }

        function clearWidgetDropState() {
            document.querySelectorAll('[data-widget-container].is-drop-zone').forEach(function (container) {
                container.classList.remove('is-drop-zone');
            });
        }

        function setExpanded(card, bodySelector, buttonSelector, expanded) {
            const body = card.querySelector(bodySelector);
            const button = card.querySelector(buttonSelector);

            if (body) {
                body.classList.toggle('d-none', !expanded);
            }

            card.classList.toggle('is-expanded', expanded);

            if (button) {
                button.textContent = expanded
                    ? (button.getAttribute('data-label-close') || 'Hide')
                    : (button.getAttribute('data-label-open') || 'Edit');
            }
        }

        function syncAppearance(card) {
            const backgroundToggle = card.querySelector('[data-style-toggle="background"]');
            const borderToggle = card.querySelector('[data-style-toggle="border"]');
            const paddingToggle = card.querySelector('[data-style-toggle="padding"]');
            const backgroundEnabled = backgroundToggle ? backgroundToggle.checked : false;
            const borderEnabled = borderToggle ? borderToggle.checked : false;
            const paddingEnabled = paddingToggle ? paddingToggle.checked : false;

            card.querySelectorAll('[data-style-target="background"]').forEach(function (element) {
                element.classList.toggle('d-none', !backgroundEnabled);
            });
            card.querySelectorAll('[data-style-target="border"]').forEach(function (element) {
                element.classList.toggle('d-none', !borderEnabled);
            });
            card.querySelectorAll('[data-style-target="padding"]').forEach(function (element) {
                element.classList.toggle('d-none', !paddingEnabled);
            });
            card.querySelectorAll('[data-style-target="radius"]').forEach(function (element) {
                element.classList.toggle('d-none', !(backgroundEnabled || borderEnabled));
            });
        }

        function syncVisibilitySummary(card, summarySelector) {
            const summary = card.querySelector(summarySelector);
            if (!summary) {
                return;
            }

            const labels = [];
            const devices = {
                desktop: 'Desktop',
                ipad_pro: 'iPad Pro',
                ipad: 'iPad',
                phone: 'Phone'
            };

            Object.keys(devices).forEach(function (deviceKey) {
                const toggle = card.querySelector('[data-visibility-toggle="' + deviceKey + '"]');
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
            const input = groupCard.querySelector('[data-group-name-input]');
            const label = groupCard.querySelector('[data-group-label]');

            if (label) {
                label.textContent = input && input.value.trim() !== '' ? input.value.trim() : 'Untitled Section';
            }
        }

        function syncWidgetLabel(widgetCard) {
            const headingInput = widgetCard.querySelector('[data-widget-heading-input]');
            const subheadingInput = widgetCard.querySelector('[data-widget-subheading-input]');
            const label = widgetCard.querySelector('[data-widget-label]');
            const preview = widgetCard.querySelector('[data-widget-preview]');
            const typeInput = widgetCard.querySelector('input[name*="[type]"]');
            const labels = {
                rich_text: 'Text Editor',
                split: 'Two Column',
                full_width: 'Full Width',
                image_grid: 'Grid Cards',
                full_image: 'Image Showcase',
                toc_content: 'TOC + Content'
            };
            const fallback = labels[typeInput ? typeInput.value : 'rich_text'] || 'Widget';

            if (label) {
                label.textContent = fallback;
            }

            if (preview) {
                const heading = headingInput ? headingInput.value.trim() : '';
                const subheading = subheadingInput ? subheadingInput.value.trim() : '';
                preview.textContent = heading || subheading || fallback;
            }
        }

        function syncWidgetCount(groupCard) {
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

        function reindexWidgets(groupCard) {
            const widgetContainer = groupCard.querySelector('[data-widget-container]');
            if (!widgetContainer) {
                return;
            }

            widgetContainer.querySelectorAll('[data-widget-card]').forEach(function (widgetCard, widgetIndex) {
                const oldIndex = widgetCard.getAttribute('data-widget-index');
                replaceWidgetIndex(groupCard, widgetCard, oldIndex, widgetIndex);
                widgetCard.setAttribute('data-widget-index', String(widgetIndex));
            });

            widgetContainer.setAttribute('data-next-widget-index', String(widgetContainer.querySelectorAll('[data-widget-card]').length));
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
            setExpanded(widgetCard, '[data-widget-body]', '[data-toggle-widget]', expanded);
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

        function addWidget(groupCard, widgetType) {
            const widgetContainer = groupCard.querySelector('[data-widget-container]');
            const widgetTemplate = groupCard.querySelector('template[data-widget-template="' + widgetType + '"]');

            if (!widgetContainer || !widgetTemplate) {
                return;
            }

            const widgetIndex = Number(widgetContainer.getAttribute('data-next-widget-index') || 0);
            const html = widgetTemplate.innerHTML.split('__WIDGET_INDEX__').join(widgetIndex);
            widgetContainer.insertAdjacentHTML('beforeend', html);
            widgetContainer.setAttribute('data-next-widget-index', String(widgetIndex + 1));

            const widgetCard = widgetContainer.querySelector('[data-widget-card]:last-child');
            initializeWidgetCard(widgetCard, true);
            reindexWidgets(groupCard);
            refreshPlugins();
        }

        function addItem(button, itemType) {
            const widgetCard = button.closest('[data-widget-card]');
            const target = widgetCard ? widgetCard.querySelector('[data-item-target="' + itemType + '"]') : null;
            const template = widgetCard ? widgetCard.querySelector('template[data-item-template="' + itemType + '"]') : null;

            if (!target || !template) {
                return;
            }

            const itemIndex = Number(target.getAttribute('data-next-index') || 0);
            const html = template.innerHTML.split('__ITEM_INDEX__').join(itemIndex);
            target.insertAdjacentHTML('beforeend', html);
            target.setAttribute('data-next-index', String(itemIndex + 1));
            refreshPlugins();
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
            if (event.target.matches('[data-group-name-input]')) {
                syncGroupLabel(event.target.closest('[data-group-card]'));
            }

            if (event.target.matches('[data-widget-heading-input], [data-widget-subheading-input]')) {
                syncWidgetLabel(event.target.closest('[data-widget-card]'));
            }
        });

        document.addEventListener('change', function (event) {
            const groupCard = event.target.closest('[data-group-card]');
            const widgetCard = event.target.closest('[data-widget-card]');

            if (event.target.matches('[data-style-toggle]')) {
                syncAppearance(widgetCard || groupCard);
            }

            if (event.target.matches('[data-visibility-toggle]')) {
                if (widgetCard) {
                    syncVisibilitySummary(widgetCard, '[data-widget-visibility-summary]');
                } else if (groupCard) {
                    syncVisibilitySummary(groupCard, '[data-group-visibility-summary]');
                }
            }
        });

        document.addEventListener('click', function (event) {
            const toggleSidebar = event.target.closest('[data-toggle-page-sidebar]');
            if (toggleSidebar) {
                pageBuilderLayout.classList.toggle('is-sidebar-hidden');
                const label = toggleSidebar.querySelector('[data-sidebar-toggle-label]');
                if (label) {
                    label.textContent = pageBuilderLayout.classList.contains('is-sidebar-hidden') ? 'Show Page Settings' : 'Hide Page Settings';
                }
                return;
            }

            const addGroupButton = event.target.closest('[data-add-group]');
            if (addGroupButton) {
                addGroup();
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
                    groupCard.remove();
                    reindexGroups();
                }
                return;
            }

            const addWidgetButton = event.target.closest('[data-add-widget]');
            if (addWidgetButton) {
                addWidget(addWidgetButton.closest('[data-group-card]'), addWidgetButton.getAttribute('data-add-widget'));
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

            const removeItemButton = event.target.closest('[data-remove-item]');
            if (removeItemButton) {
                const itemRow = removeItemButton.closest('[data-item-row]');
                if (itemRow) {
                    itemRow.remove();
                }
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
        });

        if (sectionGroups) {
            sectionGroups.addEventListener('dragstart', function (event) {
                const widgetCard = event.target.closest('[data-widget-card]');
                if (widgetCard) {
                    draggedWidgetCard = widgetCard;
                    widgetCard.classList.add('is-dragging');
                    event.dataTransfer.effectAllowed = 'move';
                    return;
                }

                const groupCard = event.target.closest('[data-group-card]');
                if (!groupCard) {
                    return;
                }

                draggedGroupCard = groupCard;
                groupCard.classList.add('is-dragging');
                event.dataTransfer.effectAllowed = 'move';
            });

            sectionGroups.addEventListener('dragover', function (event) {
                if (draggedWidgetCard) {
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

                    const afterElement = getDragAfterWidget(widgetContainer, event.clientY);

                    if (!afterElement) {
                        widgetContainer.appendChild(draggedWidgetCard);
                    } else {
                        widgetContainer.insertBefore(draggedWidgetCard, afterElement);
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

            sectionGroups.addEventListener('dragend', function () {
                if (draggedWidgetCard) {
                    draggedWidgetCard.classList.remove('is-dragging');
                    draggedWidgetCard.setAttribute('draggable', 'false');
                    draggedWidgetCard = null;
                    clearWidgetDropState();
                    reindexGroups();
                    return;
                }

                if (!draggedGroupCard) {
                    return;
                }

                draggedGroupCard.classList.remove('is-dragging');
                draggedGroupCard.setAttribute('draggable', 'false');
                draggedGroupCard = null;
                reindexGroups();
            });
        }

        if (pageForm) {
            pageForm.addEventListener('submit', function () {
                reindexGroups();
            });
        }

        document.querySelectorAll('[data-group-card]').forEach(function (groupCard) {
            initializeGroupCard(groupCard, false);
        });
        reindexGroups();
        syncGroupEmptyState();
        refreshPlugins();
    })();
</script>
