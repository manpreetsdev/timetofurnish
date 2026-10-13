{{-- Message box with photo / video upload. Expects $action and $viewer ('admin' or 'customer'). --}}
<form action="{{ $action }}" method="POST" class="om-composer" id="om-composer"
    data-upload-url="{{ route('order-messages.upload') }}"
    data-chunk="{{ \App\Services\OrderMessageService::CHUNK_BYTES }}"
    data-max-bytes="{{ \App\Services\OrderMessageService::MAX_FILE_BYTES }}"
    data-max-files="{{ \App\Services\OrderMessageService::MAX_FILES_PER_MESSAGE }}"
    data-blocked="{{ implode(',', \App\Services\OrderMessageService::BLOCKED) }}">
    @csrf

    @if ($errors->any())
        <div class="om-error">{{ $errors->first() }}</div>
    @endif

    <textarea name="body" rows="3" class="form-control om-input" maxlength="5000"
        placeholder="{{ $viewer === 'admin' ? translate('Write a message to the customer...') : translate('Write your message...') }}">{{ old('body') }}</textarea>

    <div class="om-uploads" id="om-uploads"></div>

    @if ($viewer === 'admin')
        <div class="om-options">
            <label class="aiz-checkbox mb-0">
                <input type="checkbox" name="is_alert" value="1">
                <span class="aiz-square-check"></span>
                <strong>{{ translate('Mark as important') }}</strong>
                <span class="text-muted">– {{ translate('highlighted on the customer\'s dashboard until they open it') }}</span>
            </label>
            <label class="aiz-checkbox mb-0">
                <input type="checkbox" name="notify_email" value="1" checked>
                <span class="aiz-square-check"></span>
                {{ translate('Also email the customer that a message is waiting') }}
            </label>
        </div>
    @endif

    <div class="om-actions">
        <div>
            <input type="file" id="om-file-input" multiple hidden>
            <button type="button" class="om-btn" id="om-add-files">
                <i class="las la-paperclip"></i> {{ translate('Attach files') }}
            </button>
            <span class="om-hint">{{ translate('Photos, videos, documents or any other file. Up to 200 MB each.') }}</span>
        </div>
        <button type="submit" class="om-btn om-btn-primary" id="om-send">
            <i class="las la-paper-plane"></i> {{ translate('Send') }}
        </button>
    </div>

    @if ($viewer === 'customer')
        <p class="om-note">
            <i class="las la-info-circle"></i>
            {{ translate('Messages and uploads cannot be edited or deleted after sending. If something is wrong, simply send a new message with the correct details or files.') }}
        </p>
    @endif
</form>
