/*
 * Time To Furnish – global admin table (<x-admin.table>)
 * Click-and-drag (hand tool) horizontal scrolling + overflow indicators.
 */
(function () {
    'use strict';

    var INTERACTIVE = 'a, button, input, select, textarea, label, .dropdown-menu, .bootstrap-select, [contenteditable="true"]';
    var DRAG_THRESHOLD = 5;

    function updateState(wrapper, scroller) {
        var maxScroll = scroller.scrollWidth - scroller.clientWidth;
        var scrollable = maxScroll > 1;

        wrapper.classList.toggle('is-scrollable', scrollable);
        wrapper.classList.toggle('can-scroll-left', scrollable && scroller.scrollLeft > 1);
        wrapper.classList.toggle('can-scroll-right', scrollable && scroller.scrollLeft < maxScroll - 1);
    }

    function init(wrapper) {
        if (wrapper.dataset.ttfTableReady) {
            return;
        }
        wrapper.dataset.ttfTableReady = '1';

        var scroller = wrapper.querySelector('[data-ttf-drag-scroll]');
        if (!scroller) {
            return;
        }

        var pointerDown = false;
        var dragging = false;
        var startX = 0;
        var startScroll = 0;

        scroller.addEventListener('mousedown', function (event) {
            if (event.button !== 0 || !wrapper.classList.contains('is-scrollable')) {
                return;
            }
            if (event.target.closest(INTERACTIVE)) {
                return;
            }

            pointerDown = true;
            dragging = false;
            startX = event.pageX;
            startScroll = scroller.scrollLeft;
        });

        window.addEventListener('mousemove', function (event) {
            if (!pointerDown) {
                return;
            }

            var delta = event.pageX - startX;

            if (!dragging && Math.abs(delta) > DRAG_THRESHOLD) {
                dragging = true;
                wrapper.classList.add('is-dragging');
            }

            if (dragging) {
                event.preventDefault();
                scroller.scrollLeft = startScroll - delta;
            }
        });

        window.addEventListener('mouseup', function () {
            if (!pointerDown) {
                return;
            }
            pointerDown = false;

            if (dragging) {
                // Swallow the click that follows a drag so rows/links aren't triggered
                var swallow = function (event) {
                    event.stopPropagation();
                    event.preventDefault();
                };
                scroller.addEventListener('click', swallow, { capture: true, once: true });
                setTimeout(function () {
                    scroller.removeEventListener('click', swallow, { capture: true });
                }, 0);
            }

            dragging = false;
            wrapper.classList.remove('is-dragging');
        });

        scroller.addEventListener('scroll', function () {
            updateState(wrapper, scroller);
        }, { passive: true });

        if (window.ResizeObserver) {
            new ResizeObserver(function () {
                updateState(wrapper, scroller);
            }).observe(scroller);
        } else {
            window.addEventListener('resize', function () {
                updateState(wrapper, scroller);
            });
        }

        updateState(wrapper, scroller);
    }

    function initAll(root) {
        (root || document).querySelectorAll('[data-ttf-table]').forEach(init);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initAll();
        });
    } else {
        initAll();
    }

    // ---- Row menus (three dots) ----------------------------------------
    var openMenu = null;

    function closeMenu() {
        if (!openMenu) {
            return;
        }
        openMenu.panel.hidden = true;
        openMenu.toggle.classList.remove('is-open');
        // put the panel back next to its button
        openMenu.toggle.parentNode.insertBefore(openMenu.panel, openMenu.toggle.nextSibling);
        openMenu = null;
    }

    function showMenu(toggle) {
        var panel = toggle.nextElementSibling;
        if (!panel || !panel.classList.contains('ttf-menu')) {
            return;
        }

        // Move to <body> so no scroll container can clip it
        document.body.appendChild(panel);
        panel.hidden = false;
        toggle.classList.add('is-open');
        openMenu = { toggle: toggle, panel: panel };

        var rect = toggle.getBoundingClientRect();
        var width = panel.offsetWidth;
        var height = panel.offsetHeight;
        var left = Math.min(Math.max(8, rect.right - width), window.innerWidth - width - 8);
        var top = rect.bottom + 6;
        if (top + height > window.innerHeight - 8) {
            top = Math.max(8, rect.top - height - 6);
        }
        panel.style.left = left + 'px';
        panel.style.top = top + 'px';
    }

    document.addEventListener('click', function (event) {
        var toggle = event.target.closest('[data-ttf-menu]');

        if (toggle) {
            event.preventDefault();
            var wasOpen = openMenu && openMenu.toggle === toggle;
            closeMenu();
            if (!wasOpen) {
                showMenu(toggle);
            }
            return;
        }

        if (openMenu && !openMenu.panel.contains(event.target)) {
            closeMenu();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeMenu();
        }
    });
    window.addEventListener('scroll', closeMenu, true);
    window.addEventListener('resize', closeMenu);

    // ---- Mobile: one line per row + details drawer ----------------------
    var MOBILE = window.matchMedia('(max-width: 767.98px)');
    var drawer = null;
    var drawerState = null;

    function headerCells(table) {
        var row = table.tHead ? table.tHead.rows[0] : null;
        return row ? Array.prototype.slice.call(row.cells) : [];
    }

    function isCheckCell(th) {
        return !!th.querySelector('input[type="checkbox"]');
    }

    function labelOf(th) {
        return (th.textContent || '').replace(/\s+/g, ' ').trim();
    }

    function setupMobile(wrapper) {
        var table = wrapper.querySelector('table');
        var heads = headerCells(table);
        if (!heads.length || table.dataset.ttfMobileReady) {
            return;
        }
        table.dataset.ttfMobileReady = '1';

        // Main column: explicit, else the first one that isn't a checkbox / "#" / empty
        var primary = wrapper.hasAttribute('data-ttf-primary') ? parseInt(wrapper.getAttribute('data-ttf-primary'), 10) : -1;
        if (primary < 0) {
            heads.some(function (th, index) {
                var label = labelOf(th);
                if (!isCheckCell(th) && label !== '' && label !== '#') {
                    primary = index;
                    return true;
                }
                return false;
            });
        }
        if (primary < 0) { primary = 0; }

        var checkIndex = -1;
        heads.forEach(function (th, index) { if (checkIndex < 0 && isCheckCell(th)) { checkIndex = index; } });

        table.dataset.ttfPrimary = primary;
        table.dataset.ttfCheck = checkIndex;

        // Extra "details" column, only visible on phones
        var moreHead = document.createElement('th');
        moreHead.className = 'ttf-col-more';
        heads[0].parentNode.appendChild(moreHead);

        Array.prototype.forEach.call(table.tBodies, function (body) {
            Array.prototype.forEach.call(body.rows, function (row) {
                if (row.cells.length < heads.length) {
                    return; // "no data" rows with colspan
                }
                var cell = document.createElement('td');
                cell.className = 'ttf-col-more';
                cell.innerHTML = '<button type="button" class="ttf-more-btn" data-ttf-details>' +
                    (wrapper.getAttribute('data-ttf-details-label') || 'Details') + ' <i class="las la-angle-up"></i></button>';
                row.appendChild(cell);
            });
        });

        heads.forEach(function (th, index) {
            if (index === primary) { th.classList.add('ttf-col-primary'); }
            if (index === checkIndex) { th.classList.add('ttf-col-check'); }
        });
        Array.prototype.forEach.call(table.tBodies, function (body) {
            Array.prototype.forEach.call(body.rows, function (row) {
                if (row.cells[primary]) { row.cells[primary].classList.add('ttf-col-primary'); }
                if (checkIndex >= 0 && row.cells[checkIndex]) { row.cells[checkIndex].classList.add('ttf-col-check'); }
            });
        });

        function apply() {
            wrapper.classList.toggle('is-mobile', MOBILE.matches);
            if (!MOBILE.matches) { closeDrawer(); }
        }
        if (MOBILE.addEventListener) { MOBILE.addEventListener('change', apply); } else if (MOBILE.addListener) { MOBILE.addListener(apply); }
        apply();
    }

    function buildDrawer() {
        drawer = document.createElement('div');
        drawer.className = 'ttf-drawer';
        drawer.innerHTML = '<div class="ttf-drawer__backdrop" data-ttf-drawer-close></div>' +
            '<div class="ttf-drawer__panel" role="dialog" aria-modal="true">' +
                '<div class="ttf-drawer__grip"></div>' +
                '<div class="ttf-drawer__head"><div class="ttf-drawer__title"></div>' +
                '<button type="button" class="ttf-drawer__close" data-ttf-drawer-close aria-label="Close">&times;</button></div>' +
                '<div class="ttf-drawer__body"></div>' +
            '</div>';
        document.body.appendChild(drawer);
        drawer.addEventListener('click', function (event) {
            if (event.target.closest('[data-ttf-drawer-close]')) { closeDrawer(); return; }

            // Any action inside the drawer (edit, delete, view, invoices…) closes it.
            // Switches and form fields keep it open. Closing waits a tick so the
            // action's own click handler (e.g. a delete confirmation) runs first.
            var action = event.target.closest('.ttf-drawer__body a, .ttf-drawer__body button');
            if (action && !action.closest('.aiz-switch')) {
                setTimeout(closeDrawer, 0);
            }
        });
        enableSwipeToClose(drawer.querySelector('.ttf-drawer__panel'), closeDrawer);
    }

    // Drag a bottom drawer down to close it (touch devices)
    function enableSwipeToClose(panel, onClose) {
        if (!panel) { return; }
        var startY = null;
        var delta = 0;

        panel.addEventListener('touchstart', function (event) {
            // only start a swipe from the top of the sheet, or when its content is scrolled to the top
            var scroller = panel.querySelector('.ttf-drawer__body') || panel;
            var fromHandle = event.target.closest('.ttf-drawer__grip, .ttf-drawer__head, .ttf-filterbar__panel-head');
            if (!fromHandle && scroller.scrollTop > 0 && panel.scrollTop > 0) { return; }
            startY = event.touches[0].clientY;
            delta = 0;
            panel.style.transition = 'none';
        }, { passive: true });

        panel.addEventListener('touchmove', function (event) {
            if (startY === null) { return; }
            delta = Math.max(0, event.touches[0].clientY - startY);
            if (delta > 0) { panel.style.transform = 'translateY(' + delta + 'px)'; }
        }, { passive: true });

        panel.addEventListener('touchend', function () {
            if (startY === null) { return; }
            panel.style.transition = '';
            panel.style.transform = '';
            if (delta > 90) { onClose(); }
            startY = null;
            delta = 0;
        });
    }

    function openDrawer(row) {
        if (!drawer) { buildDrawer(); }
        closeDrawer();

        var table = row.closest('table');
        var heads = headerCells(table);
        var primary = parseInt(table.dataset.ttfPrimary, 10);
        var checkIndex = parseInt(table.dataset.ttfCheck, 10);
        var body = drawer.querySelector('.ttf-drawer__body');
        var moved = [];

        drawer.querySelector('.ttf-drawer__title').innerHTML = row.cells[primary] ? row.cells[primary].innerHTML : '';
        body.innerHTML = '';

        heads.forEach(function (th, index) {
            var cell = row.cells[index];
            if (!cell || index === primary || index === checkIndex || cell.classList.contains('ttf-col-more')) {
                return;
            }
            var line = document.createElement('div');
            line.className = 'ttf-drawer__row';
            var label = document.createElement('div');
            label.className = 'ttf-drawer__label';
            label.textContent = labelOf(th) || '—';
            var value = document.createElement('div');
            value.className = 'ttf-drawer__value';

            // Move the real content in (keeps event handlers, form inputs and state)
            while (cell.firstChild) { value.appendChild(cell.firstChild); }
            moved.push({ cell: cell, value: value });

            line.appendChild(label);
            line.appendChild(value);
            body.appendChild(line);
        });

        drawerState = { moved: moved };
        drawer.classList.add('is-open');
        document.body.classList.add('ttf-drawer-open');
        requestAnimationFrame(function () {
            requestAnimationFrame(function () { drawer.classList.add('is-visible'); });
        });
    }

    function closeDrawer() {
        if (!drawer || !drawerState) { return; }
        // Put every cell's content back where it came from
        drawerState.moved.forEach(function (item) {
            while (item.value.firstChild) { item.cell.appendChild(item.value.firstChild); }
        });
        drawerState = null;
        drawer.classList.remove('is-visible', 'is-open');
        document.body.classList.remove('ttf-drawer-open');
    }

    document.addEventListener('click', function (event) {
        if (!MOBILE.matches) { return; }

        var button = event.target.closest('[data-ttf-details]');
        var row = button ? button.closest('tr') : null;

        // Tapping the row itself also opens it, unless the tap was on a control
        if (!row) {
            var cell = event.target.closest('.ttf-table.is-mobile td.ttf-col-primary');
            if (cell && !event.target.closest('a, button, input, select, label, textarea')) {
                row = cell.parentNode;
            }
        }

        if (row) {
            event.preventDefault();
            openDrawer(row);
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') { closeDrawer(); }
    });

    function initMobile(root) {
        (root || document).querySelectorAll('[data-ttf-table][data-ttf-mobile]').forEach(setupMobile);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { initMobile(); });
    } else {
        initMobile();
    }

    // ---- Filter bar --------------------------------------------------------
    function countFilters(bar) {
        var panel = bar.querySelector('[data-ttf-filter-panel]');
        if (!panel) { return; }
        var count = 0;
        panel.querySelectorAll('select, input').forEach(function (field) {
            if (field.type === 'hidden' || field.type === 'checkbox' || field.type === 'radio' || field.type === 'button') { return; }
            if (field.closest('.bs-searchbox')) { return; }
            if (field.value !== null && String(field.value).trim() !== '') { count++; }
        });
        var badge = bar.querySelector('[data-ttf-filter-count]');
        if (badge) {
            badge.textContent = count;
            badge.hidden = count === 0;
        }
    }

    function setFilterOpen(bar, open) {
        bar.classList.toggle('is-open', open);
        var toggle = bar.querySelector('[data-ttf-filter-toggle]');
        if (toggle) { toggle.setAttribute('aria-expanded', open ? 'true' : 'false'); }
        document.body.classList.toggle('ttf-filter-open', open && MOBILE.matches);
    }

    document.addEventListener('click', function (event) {
        var toggle = event.target.closest('[data-ttf-filter-toggle]');
        if (toggle) {
            var bar = toggle.closest('[data-ttf-filterbar]');
            setFilterOpen(bar, !bar.classList.contains('is-open'));
            return;
        }
        var close = event.target.closest('[data-ttf-filter-close]');
        if (close) {
            setFilterOpen(close.closest('[data-ttf-filterbar]'), false);
        }
    });

    document.addEventListener('change', function (event) {
        var bar = event.target.closest && event.target.closest('[data-ttf-filterbar]');
        if (bar) { countFilters(bar); }
    });

    function initFilterBars(root) {
        (root || document).querySelectorAll('[data-ttf-filterbar]').forEach(function (bar) {
            var sheet = bar.querySelector('[data-ttf-filter-panel]');
            if (sheet && !sheet.dataset.ttfSwipe) {
                sheet.dataset.ttfSwipe = '1';
                enableSwipeToClose(sheet, function () { if (MOBILE.matches) { setFilterOpen(bar, false); } });
            }
            countFilters(bar);
            // Open the panel on desktop when filters are already applied
            var badge = bar.querySelector('[data-ttf-filter-count]');
            if (!MOBILE.matches && badge && !badge.hidden) { setFilterOpen(bar, true); }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { initFilterBars(); });
    } else {
        initFilterBars();
    }

    // Allow pages that inject tables via AJAX to re-run the initialiser
    window.TTFAdminTable = { init: function (root) { initAll(root); initMobile(root); } };
})();
