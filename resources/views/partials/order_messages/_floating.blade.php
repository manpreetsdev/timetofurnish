{{-- Floating message bubble (bottom right) for signed-in customers with unread messages from Time To Furnish --}}
<div class="omf" id="omf" data-url="{{ route('order-messages.unread') }}" data-index="{{ route('order-messages.index') }}" hidden>
    <div class="omf-panel" id="omf-panel" hidden>
        <div class="omf-panel-head">
            <div>
                <strong>{{ translate('Messages from Time To Furnish') }}</strong>
                <span id="omf-sub"></span>
            </div>
            <button type="button" class="omf-close" id="omf-close" aria-label="{{ translate('Close') }}">&times;</button>
        </div>
        <div class="omf-items" id="omf-items"></div>
        <a href="{{ route('order-messages.index') }}" class="omf-all">{{ translate('View all messages') }}</a>
    </div>

    <div class="omf-toast" id="omf-toast" hidden>
        <strong id="omf-toast-title"></strong>
        <span id="omf-toast-body"></span>
    </div>

    <button type="button" class="omf-bubble" id="omf-bubble" aria-label="{{ translate('You have new messages') }}">
        <i class="las la-comment-dots"></i>
        <span class="omf-count" id="omf-count">0</span>
    </button>
</div>

<style>
    .omf { position: fixed; right: 22px; bottom: 22px; z-index: 1045; font-family: inherit; }
    .omf[hidden], .omf-panel[hidden], .omf-toast[hidden] { display: none; }
    .omf-bubble { position: relative; display: flex; align-items: center; justify-content: center; width: 58px; height: 58px; margin-left: auto; border: 0; border-radius: 50%; background: #685b4e; color: #fff; font-size: 28px; cursor: pointer; box-shadow: 0 10px 26px rgba(57, 50, 42, .35); transition: transform .15s ease, background .15s ease; }
    .omf-bubble:hover { background: #4f453b; transform: scale(1.05); }
    .omf.has-alert .omf-bubble { background: #e0a020; }
    .omf.has-alert .omf-bubble:hover { background: #c98c12; }
    .omf.is-pulsing .omf-bubble { animation: omf-pulse 1.6s ease-out 3; }
    @keyframes omf-pulse {
        0% { box-shadow: 0 0 0 0 rgba(224, 160, 32, .55), 0 10px 26px rgba(57, 50, 42, .35); }
        70% { box-shadow: 0 0 0 18px rgba(224, 160, 32, 0), 0 10px 26px rgba(57, 50, 42, .35); }
        100% { box-shadow: 0 0 0 0 rgba(224, 160, 32, 0), 0 10px 26px rgba(57, 50, 42, .35); }
    }
    .omf-count { position: absolute; top: -3px; right: -3px; min-width: 22px; height: 22px; padding: 0 6px; border: 2px solid #fff; border-radius: 999px; background: #d9342b; color: #fff; font-size: 11.5px; font-weight: 700; line-height: 18px; text-align: center; }
    .omf-panel { position: absolute; right: 0; bottom: 70px; width: 340px; max-width: calc(100vw - 28px); border: 1px solid #e8e5e1; border-radius: 14px; background: #fff; box-shadow: 0 18px 46px rgba(57, 50, 42, .25); overflow: hidden; }
    .omf-panel-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; padding: 14px 14px 12px; background: #685b4e; color: #fff; }
    .omf-panel-head strong { display: block; font-size: 14px; }
    .omf-panel-head span { display: block; margin-top: 2px; font-size: 12px; opacity: .85; }
    .omf-close { border: 0; background: transparent; color: #fff; font-size: 22px; line-height: 1; cursor: pointer; opacity: .85; }
    .omf-items { max-height: 320px; overflow-y: auto; }
    .omf-item { display: block; padding: 11px 14px; border-bottom: 1px solid #f0ece7; color: #39322a; text-decoration: none !important; }
    .omf-item:hover { background: #faf8f5; color: #39322a; }
    .omf-item.is-alert { background: #fffaf0; border-left: 3px solid #e0a020; }
    .omf-item-top { display: flex; align-items: center; justify-content: space-between; gap: 8px; font-size: 12px; }
    .omf-item-top strong { font-size: 12.5px; }
    .omf-item-top > span { color: #8c7e70; font-size: 11px; white-space: nowrap; }
    .omf-item-body { margin-top: 3px; color: #5d544a; font-size: 12.5px; line-height: 1.45; }
    .omf-item-cta { display: inline-block; margin-top: 6px; color: #685b4e; font-size: 12px; font-weight: 700; }
    .omf-item-top .omf-tag { display: inline-block; margin-left: 4px; padding: 1px 7px; border-radius: 999px; background: #e0a020; color: #fff; font-size: 10px; font-weight: 700; text-transform: uppercase; }
    .omf-all { display: block; padding: 11px 14px; background: #faf8f5; color: #685b4e; font-size: 12.5px; font-weight: 700; text-align: center; text-decoration: none !important; }
    .omf-all:hover { background: #f3efe9; color: #685b4e; }
    .omf-toast { position: absolute; right: 70px; bottom: 6px; width: 260px; max-width: calc(100vw - 110px); padding: 10px 12px; border: 1px solid #e8e5e1; border-left: 3px solid #685b4e; border-radius: 10px; background: #fff; box-shadow: 0 10px 28px rgba(57, 50, 42, .2); cursor: pointer; }
    .omf.has-alert .omf-toast { border-left-color: #e0a020; }
    .omf-toast strong { display: block; color: #39322a; font-size: 12.5px; }
    .omf-toast span { display: block; margin-top: 2px; color: #5d544a; font-size: 12px; line-height: 1.4; }
    @media (max-width: 575.98px) {
        .omf { right: 14px; bottom: 76px; }
        .omf-bubble { width: 52px; height: 52px; font-size: 25px; }
    }
</style>

<script>
    (function () {
        var root = document.getElementById('omf');
        if (!root) { return; }

        var bubble = document.getElementById('omf-bubble');
        var panel = document.getElementById('omf-panel');
        var toast = document.getElementById('omf-toast');
        var items = document.getElementById('omf-items');
        var lastCount = null;
        var toastTimer = null;

        function el(tag, className, text) {
            var node = document.createElement(tag);
            if (className) { node.className = className; }
            if (text != null) { node.textContent = text; }
            return node;
        }

        function render(data) {
            root.hidden = data.count === 0;
            if (data.count === 0) { panel.hidden = true; toast.hidden = true; lastCount = 0; return; }
            place();

            document.getElementById('omf-count').textContent = data.count > 99 ? '99+' : data.count;
            document.getElementById('omf-sub').textContent = data.count + ' ' + (data.count === 1 ? '{{ translate('unread message') }}' : '{{ translate('unread messages') }}');
            root.classList.toggle('has-alert', data.alerts > 0);

            items.innerHTML = '';
            data.items.forEach(function (item) {
                var link = el('a', 'omf-item' + (item.is_alert ? ' is-alert' : ''));
                link.href = item.url;
                var top = el('div', 'omf-item-top');
                var title = el('strong', null, '{{ translate('Order') }} ' + item.code);
                if (item.is_alert) { title.appendChild(el('span', 'omf-tag', '{{ translate('Important') }}')); }
                top.appendChild(title);
                top.appendChild(el('span', null, item.time));
                link.appendChild(top);
                link.appendChild(el('div', 'omf-item-body', item.body));
                link.appendChild(el('span', 'omf-item-cta', {!! json_encode(translate('View & reply')) !!} + ' →'));
                items.appendChild(link);
            });

            // A message arrived since the last check (or this is the first page load with unread ones)
            if (lastCount === null || data.count > lastCount) {
                root.classList.remove('is-pulsing');
                void root.offsetWidth;
                root.classList.add('is-pulsing');

                if (panel.hidden && data.items.length) {
                    var newest = data.items[0];
                    document.getElementById('omf-toast-title').textContent = (newest.is_alert ? '{{ translate('Action needed') }}' : '{{ translate('New message') }}') + ' · ' + newest.code;
                    document.getElementById('omf-toast-body').textContent = newest.body;
                    toast.hidden = false;
                    clearTimeout(toastTimer);
                    toastTimer = setTimeout(function () { toast.hidden = true; }, 9000);
                }
            }
            lastCount = data.count;
        }

        // Sit above the cookie bar while it is on screen, so the bubble is never hidden behind it
        function place() {
            var banner = document.getElementById('cookie-banner');
            var height = 0;
            if (banner) {
                var rect = banner.getBoundingClientRect();
                var style = window.getComputedStyle(banner);
                if (style.display !== 'none' && style.visibility !== 'hidden' && rect.height > 0 && rect.bottom >= window.innerHeight - 2) {
                    height = rect.height;
                }
            }
            root.style.bottom = height ? (height + 16) + 'px' : '';
        }

        function check(force) {
            // background tabs skip the regular polling, but the first check always runs
            if (document.hidden && force !== true) { return; }
            fetch(root.dataset.url, { credentials: 'same-origin', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (response) { return response.ok ? response.json() : null; })
                .then(function (data) { if (data) { render(data); } })
                .catch(function () {});
        }

        bubble.addEventListener('click', function () {
            toast.hidden = true;
            // One conversation waiting: go straight to it. Several: show the list.
            var links = items.querySelectorAll('.omf-item');
            if (links.length === 1) { window.location.href = links[0].href; return; }
            panel.hidden = !panel.hidden;
        });
        toast.addEventListener('click', function () { bubble.click(); });
        document.getElementById('omf-close').addEventListener('click', function () { panel.hidden = true; });
        document.addEventListener('click', function (event) { if (!root.contains(event.target)) { panel.hidden = true; } });

        window.addEventListener('resize', place);
        window.addEventListener('load', place);
        check(true);
        setInterval(check, 30000);
        document.addEventListener('visibilitychange', function () { if (!document.hidden) { check(); } });
    })();
</script>
