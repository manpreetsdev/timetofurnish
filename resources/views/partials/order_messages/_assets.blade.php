<style>
    .om-thread { display: flex; flex-direction: column; gap: 12px; max-height: 62vh; overflow-y: auto; padding: 16px; background: #faf8f5; border: 1px solid #e8e5e1; border-radius: 12px; }
    .om-row { display: flex; }
    .om-row.is-mine { justify-content: flex-end; }
    .om-bubble { max-width: min(640px, 86%); padding: 11px 14px; border: 1px solid #e8e5e1; border-radius: 12px; background: #fff; color: #39322a; box-shadow: 0 2px 8px rgba(104, 91, 78, .05); }
    .om-row.is-mine .om-bubble { background: #f3efe9; border-color: #dcd4c9; }
    .om-bubble.is-alert { border: 1px solid #f0c36d; border-left: 4px solid #e0a020; background: #fffaf0; }
    .om-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 4px; font-size: 12px; }
    .om-meta strong { color: #39322a; }
    .om-time { color: #8c7e70; font-size: 11px; }
    .om-tag { display: inline-flex; align-items: center; gap: 3px; padding: 2px 8px; border-radius: 999px; background: #e0a020; color: #fff; font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; }
    .om-body { font-size: 13.5px; line-height: 1.6; word-break: break-word; }
    .om-files { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 9px; }
    .om-file-image img { display: block; width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid #e8e5e1; }
    .om-file-video { width: 260px; max-width: 100%; }
    .om-file-video video { display: block; width: 100%; max-height: 200px; border-radius: 8px; background: #000; }
    .om-file-name { display: block; margin-top: 3px; color: #8c7e70; font-size: 11px; word-break: break-all; }
    .om-file-doc { display: flex; align-items: center; gap: 8px; padding: 8px 12px; border: 1px solid #e8e5e1; border-radius: 8px; background: #fff; color: #39322a; font-size: 12.5px; text-decoration: none; }
    .om-file-doc i { font-size: 22px; color: #685b4e; }
    .om-file-doc small { display: block; color: #8c7e70; }
    .om-receipt { margin-top: 6px; color: #8c7e70; font-size: 11px; text-align: right; }
    .om-empty { padding: 40px 16px; color: #8c7e70; text-align: center; font-size: 13px; }
    .om-empty i { display: block; font-size: 38px; margin-bottom: 6px; color: #cfc5b8; }

    .om-composer { margin-top: 14px; padding: 14px; background: #fff; border: 1px solid #e8e5e1; border-radius: 12px; }
    .om-input { border-radius: 8px; border-color: #dcd4c9; font-size: 13.5px; resize: vertical; }
    .om-input:focus { border-color: #685b4e; box-shadow: 0 0 0 3px rgba(104, 91, 78, .12); }
    .om-error { margin-bottom: 10px; padding: 9px 12px; border-radius: 8px; background: #fdecec; color: #b42318; font-size: 12.5px; }
    .om-options { display: grid; gap: 6px; margin-top: 10px; font-size: 12.5px; }
    .om-actions { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 10px; margin-top: 12px; }
    .om-btn { display: inline-flex; align-items: center; gap: 6px; min-height: 38px; padding: 0 14px; border: 1px solid #dcd4c9; border-radius: 8px; background: #f3efe9; color: #685b4e; font-size: 13px; font-weight: 600; cursor: pointer; }
    .om-btn:hover { background: #ece5db; }
    .om-btn-primary { background: #685b4e; border-color: #685b4e; color: #fff; padding: 0 22px; }
    .om-btn-primary:hover { background: #4f453b; }
    .om-btn:disabled { opacity: .55; cursor: not-allowed; }
    .om-hint { margin-left: 8px; color: #8c7e70; font-size: 11.5px; }
    .om-note { margin: 12px 0 0; padding: 9px 12px; border-radius: 8px; background: #faf8f5; color: #6b5a45; font-size: 12px; line-height: 1.5; }
    .om-uploads { display: grid; gap: 6px; margin-top: 10px; }
    .om-uploads:empty { display: none; }
    .om-upload { display: flex; align-items: center; gap: 10px; padding: 8px 10px; border: 1px solid #e8e5e1; border-radius: 8px; background: #faf8f5; font-size: 12.5px; }
    .om-upload-name { flex: 1 1 auto; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .om-upload-bar { flex: 0 0 110px; height: 6px; border-radius: 999px; background: #e8e5e1; overflow: hidden; }
    .om-upload-bar span { display: block; height: 100%; width: 0; background: #685b4e; transition: width .2s ease; }
    .om-upload-status { flex: 0 0 auto; min-width: 62px; color: #8c7e70; font-size: 11.5px; text-align: right; }
    .om-upload.is-done .om-upload-bar span { background: #2e9e5b; }
    .om-upload.is-done .om-upload-status { color: #137333; }
    .om-upload.is-error { border-color: #f5c2c0; background: #fdecec; }
    .om-upload.is-error .om-upload-status { color: #b42318; }
    .om-upload-remove { flex: 0 0 auto; border: 0; background: transparent; color: #8c7e70; font-size: 16px; line-height: 1; cursor: pointer; }
    @media (max-width: 575.98px) {
        .om-bubble { max-width: 94%; }
        .om-hint { display: block; margin: 6px 0 0; }
        .om-actions .om-btn-primary { width: 100%; justify-content: center; }
    }
</style>

<script>
    (function () {
        function ready(fn) {
            if (document.readyState !== 'loading') { fn(); } else { document.addEventListener('DOMContentLoaded', fn); }
        }

        ready(function () {
            var thread = document.getElementById('om-thread');
            if (thread) { thread.scrollTop = thread.scrollHeight; }

            var form = document.getElementById('om-composer');
            if (!form) { return; }

            var input = document.getElementById('om-file-input');
            var list = document.getElementById('om-uploads');
            var send = document.getElementById('om-send');
            var chunkSize = parseInt(form.dataset.chunk, 10);
            var maxBytes = parseInt(form.dataset.maxBytes, 10);
            var maxFiles = parseInt(form.dataset.maxFiles, 10);
            var blocked = form.dataset.blocked.split(',');
            var token = form.querySelector('input[name="_token"]').value;
            var active = 0;

            function refresh() {
                send.disabled = active > 0;
                send.lastChild.textContent = active > 0 ? ' {{ translate('Uploading...') }}' : ' {{ translate('Send') }}';
            }

            function randomId() {
                var chars = 'abcdefghijklmnopqrstuvwxyz0123456789', id = '';
                for (var i = 0; i < 24; i++) { id += chars.charAt(Math.floor(Math.random() * chars.length)); }
                return id;
            }

            function fail(row, message) {
                row.classList.add('is-error');
                row.querySelector('.om-upload-status').textContent = message;
            }

            function upload(file) {
                var row = document.createElement('div');
                row.className = 'om-upload';
                row.innerHTML = '<span class="om-upload-name"></span><span class="om-upload-bar"><span></span></span>' +
                    '<span class="om-upload-status">0%</span><button type="button" class="om-upload-remove" title="{{ translate('Remove') }}">&times;</button>';
                row.querySelector('.om-upload-name').textContent = file.name;
                list.appendChild(row);

                var cancelled = false;
                row.querySelector('.om-upload-remove').addEventListener('click', function () {
                    cancelled = true;
                    row.remove();
                });

                var ext = (file.name.split('.').pop() || '').toLowerCase();
                if (!ext || ext === file.name.toLowerCase() || blocked.indexOf(ext) !== -1) { return fail(row, '{{ translate('File type not allowed') }}'); }
                if (file.size > maxBytes) { return fail(row, '{{ translate('Too large (max 200 MB)') }}'); }
                if (file.size === 0) { return fail(row, '{{ translate('Empty file') }}'); }

                var id = randomId();
                var total = Math.ceil(file.size / chunkSize);
                var index = 0;
                var retries = 0;
                active++;
                refresh();

                function finish() { active--; refresh(); }

                function next() {
                    if (cancelled) { return finish(); }

                    var data = new FormData();
                    data.append('_token', token);
                    data.append('upload_id', id);
                    data.append('index', index);
                    data.append('total', total);
                    data.append('name', file.name);
                    data.append('chunk', file.slice(index * chunkSize, (index + 1) * chunkSize), 'chunk');

                    fetch(form.dataset.uploadUrl, { method: 'POST', body: data, credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
                        .then(function (response) {
                            if (!response.ok) { throw new Error(response.status === 422 ? 'rejected' : 'network'); }
                            return response.json();
                        })
                        .then(function (result) {
                            retries = 0;
                            index++;
                            var percent = Math.round((index / total) * 100);
                            row.querySelector('.om-upload-bar span').style.width = percent + '%';
                            row.querySelector('.om-upload-status').textContent = percent + '%';

                            if (result.done) {
                                if (cancelled) { return finish(); }
                                var hidden = document.createElement('input');
                                hidden.type = 'hidden';
                                hidden.name = 'tokens[]';
                                hidden.value = result.token;
                                row.appendChild(hidden);
                                row.classList.add('is-done');
                                row.querySelector('.om-upload-status').textContent = '{{ translate('Ready') }}';
                                return finish();
                            }
                            next();
                        })
                        .catch(function (error) {
                            // Retry a dropped connection a few times before giving up
                            if (error.message !== 'rejected' && retries < 3) {
                                retries++;
                                return setTimeout(next, 1500 * retries);
                            }
                            fail(row, '{{ translate('Upload failed') }}');
                            finish();
                        });
                }

                next();
            }

            document.getElementById('om-add-files').addEventListener('click', function () { input.click(); });

            input.addEventListener('change', function () {
                var existing = list.querySelectorAll('.om-upload:not(.is-error)').length;
                Array.prototype.slice.call(input.files).forEach(function (file) {
                    if (existing >= maxFiles) { return; }
                    existing++;
                    upload(file);
                });
                input.value = '';
            });

            form.addEventListener('submit', function (event) {
                if (active > 0) { event.preventDefault(); return; }
                // Failed rows are not sent
                list.querySelectorAll('.om-upload.is-error').forEach(function (row) { row.remove(); });
                send.disabled = true;
            });
        });
    })();
</script>
