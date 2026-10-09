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

    // Allow pages that inject tables via AJAX to re-run the initialiser
    window.TTFAdminTable = { init: initAll };
})();
