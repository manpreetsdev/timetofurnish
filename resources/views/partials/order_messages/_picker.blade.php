{{--
    Searchable dropdown used by the order message screens.

    <div class="ttfp" id="x" data-placeholder="Choose..." data-search="Search..."></div>
    var picker = TTFPicker(element, onChange);
    picker.setOptions([{ value, title, subtitle, meta, search }]);  picker.setValue(v);  picker.setDisabled(bool, placeholder);
--}}
<style>
    .ttfp { position: relative; width: 100%; }
    .ttfp-toggle { display: flex; align-items: center; justify-content: space-between; gap: 10px; width: 100%; min-height: 44px; padding: 7px 12px; border: 1px solid #dcd4c9; border-radius: 8px; background: #fff; color: #39322a; font-size: 13px; text-align: left; cursor: pointer; }
    .ttfp-toggle:focus { outline: 0; border-color: #685b4e; box-shadow: 0 0 0 3px rgba(104, 91, 78, .12); }
    .ttfp.is-disabled .ttfp-toggle { background: #f6f5f3; color: #a59c91; cursor: not-allowed; }
    .ttfp-current { flex: 1 1 auto; min-width: 0; }
    .ttfp-current strong { display: block; font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ttfp-current small { display: block; color: #8c7e70; font-size: 11.5px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ttfp-current.is-placeholder strong { color: #8c7e70; font-weight: 400; }
    .ttfp-arrow { flex: 0 0 auto; color: #8c7e70; font-size: 14px; transition: transform .15s ease; }
    .ttfp.is-open .ttfp-arrow { transform: rotate(180deg); }
    .ttfp-panel { position: absolute; z-index: 1060; left: 0; right: 0; top: calc(100% + 4px); display: none; border: 1px solid #dcd4c9; border-radius: 10px; background: #fff; box-shadow: 0 14px 34px rgba(57, 50, 42, .16); overflow: hidden; }
    .ttfp.is-open .ttfp-panel { display: block; }
    .ttfp.opens-up .ttfp-panel { top: auto; bottom: calc(100% + 4px); }
    .ttfp-search { position: relative; padding: 8px; border-bottom: 1px solid #f0ece7; background: #faf8f5; }
    .ttfp-search i { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: #8c7e70; }
    .ttfp-search input { display: block; width: 100%; height: 38px; padding: 0 12px 0 34px; border: 1px solid #dcd4c9; border-radius: 8px; background: #fff; font-size: 13px; color: #39322a; box-sizing: border-box; }
    .ttfp-search input:focus { outline: 0; border-color: #685b4e; }
    .ttfp-count { padding: 6px 12px 2px; color: #8c7e70; font-size: 10.5px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; }
    .ttfp-list { max-height: 280px; overflow-y: auto; padding: 4px; }
    .ttfp-option { display: flex; align-items: center; justify-content: space-between; gap: 12px; width: 100%; padding: 9px 10px; border: 0; border-radius: 8px; background: transparent; color: #39322a; font-size: 13px; text-align: left; cursor: pointer; }
    .ttfp-option:hover, .ttfp-option.is-active { background: #f3efe9; }
    .ttfp-option.is-selected { background: #ece5db; }
    .ttfp-option-main { flex: 1 1 auto; min-width: 0; }
    .ttfp-option-main strong { display: block; font-weight: 600; line-height: 1.35; white-space: normal; word-break: break-word; }
    .ttfp-option-main small { display: block; margin-top: 1px; color: #8c7e70; font-size: 11.5px; line-height: 1.4; white-space: normal; }
    .ttfp-option-meta { flex: 0 0 auto; color: #685b4e; font-size: 11.5px; font-weight: 600; text-align: right; white-space: nowrap; }
    .ttfp-empty { padding: 16px 12px; color: #8c7e70; font-size: 12.5px; text-align: center; }
</style>

<script>
    window.TTFPicker = window.TTFPicker || function (root, onChange) {
        var options = [];
        var value = null;
        var placeholder = root.getAttribute('data-placeholder') || '';

        root.innerHTML =
            '<button type="button" class="ttfp-toggle" aria-haspopup="listbox">' +
                '<span class="ttfp-current is-placeholder"><strong></strong><small></small></span>' +
                '<i class="las la-angle-down ttfp-arrow"></i>' +
            '</button>' +
            '<div class="ttfp-panel">' +
                '<div class="ttfp-search"><i class="las la-search"></i><input type="text" autocomplete="off"></div>' +
                '<div class="ttfp-count"></div>' +
                '<div class="ttfp-list" role="listbox"></div>' +
            '</div>';

        var toggle = root.querySelector('.ttfp-toggle');
        var current = root.querySelector('.ttfp-current');
        var search = root.querySelector('.ttfp-search input');
        var count = root.querySelector('.ttfp-count');
        var list = root.querySelector('.ttfp-list');
        search.placeholder = root.getAttribute('data-search') || '';

        function text(node, content) { node.textContent = content == null ? '' : content; }

        function renderCurrent() {
            var selected = options.filter(function (o) { return String(o.value) === String(value); })[0];
            current.classList.toggle('is-placeholder', !selected);
            text(current.querySelector('strong'), selected ? selected.title : placeholder);
            text(current.querySelector('small'), selected ? (selected.subtitle || '') : '');
            current.querySelector('small').style.display = selected && selected.subtitle ? '' : 'none';
        }

        function renderList() {
            var term = search.value.trim().toLowerCase();
            var matches = options.filter(function (o) {
                return !term || (o.search || (o.title + ' ' + (o.subtitle || ''))).toLowerCase().indexOf(term) !== -1;
            });

            list.innerHTML = '';
            text(count, matches.length + ' ' + (root.getAttribute('data-label') || ''));

            if (!matches.length) {
                var empty = document.createElement('div');
                empty.className = 'ttfp-empty';
                text(empty, root.getAttribute('data-empty') || 'Nothing found');
                list.appendChild(empty);
                return;
            }

            matches.forEach(function (o) {
                var button = document.createElement('button');
                button.type = 'button';
                button.className = 'ttfp-option' + (String(o.value) === String(value) ? ' is-selected' : '');
                button.innerHTML = '<span class="ttfp-option-main"><strong></strong><small></small></span><span class="ttfp-option-meta"></span>';
                text(button.querySelector('strong'), o.title);
                text(button.querySelector('small'), o.subtitle || '');
                text(button.querySelector('.ttfp-option-meta'), o.meta || '');
                button.addEventListener('click', function () {
                    api.setValue(o.value, true);
                    close();
                });
                list.appendChild(button);
            });
        }

        function open() {
            if (root.classList.contains('is-disabled')) { return; }
            root.classList.add('is-open');
            // open upwards when there is not enough room below
            var rect = root.getBoundingClientRect();
            root.classList.toggle('opens-up', window.innerHeight - rect.bottom < 360 && rect.top > 360);
            search.value = '';
            renderList();
            setTimeout(function () { search.focus(); }, 30);
        }

        function close() { root.classList.remove('is-open'); }

        toggle.addEventListener('click', function () { root.classList.contains('is-open') ? close() : open(); });
        search.addEventListener('input', renderList);
        search.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') { close(); toggle.focus(); }
            if (event.key === 'Enter') {
                event.preventDefault();
                var first = list.querySelector('.ttfp-option');
                if (first) { first.click(); }
            }
        });
        document.addEventListener('click', function (event) { if (!root.contains(event.target)) { close(); } });

        var api = {
            setOptions: function (items) { options = items || []; value = null; renderCurrent(); renderList(); },
            setValue: function (next, notify) {
                value = next;
                renderCurrent();
                if (notify && onChange) { onChange(options.filter(function (o) { return String(o.value) === String(value); })[0] || null); }
            },
            getValue: function () { return value; },
            setDisabled: function (disabled, text) {
                root.classList.toggle('is-disabled', !!disabled);
                if (text !== undefined) { placeholder = text; }
                close();
                renderCurrent();
            }
        };

        renderCurrent();
        return api;
    };
</script>
