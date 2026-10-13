{{-- Conversation bubbles. Expects $messages and $viewer ('admin' or 'customer'). --}}
<div class="om-thread" id="om-thread">
    @forelse ($messages as $message)
        @php
            $mine = $message->sender_type === $viewer;
            $fromTeam = $message->sender_type === 'admin';
        @endphp
        <div class="om-row {{ $mine ? 'is-mine' : '' }}">
            <div class="om-bubble {{ $fromTeam ? 'from-team' : 'from-customer' }} {{ $message->is_alert ? 'is-alert' : '' }}">
                <div class="om-meta">
                    <strong>
                        @if ($fromTeam)
                            {{ $viewer === 'admin' ? (optional($message->sender)->name ?: 'Admin') : 'Time To Furnish' }}
                        @else
                            {{ $viewer === 'customer' ? translate('You') : (optional($message->sender)->name ?: translate('Customer')) }}
                        @endif
                    </strong>
                    @if ($message->is_alert)
                        <span class="om-tag"><i class="las la-exclamation-circle"></i> {{ translate('Important') }}</span>
                    @endif
                    <span class="om-time">{{ $message->created_at->format('j M, Y g:i A') }}</span>
                </div>

                @if ($message->body)
                    <div class="om-body">{!! nl2br(e($message->body)) !!}</div>
                @endif

                @if ($message->files->isNotEmpty())
                    <div class="om-files">
                        @foreach ($message->files as $file)
                            @php $url = route('order-messages.file', $file->id); @endphp
                            @if ($file->kind === 'image')
                                <a href="{{ $url }}" target="_blank" class="om-file om-file-image" title="{{ $file->original_name }}">
                                    <img src="{{ $url }}" alt="{{ $file->original_name }}" loading="lazy">
                                </a>
                            @elseif ($file->kind === 'video')
                                <div class="om-file om-file-video">
                                    <video controls preload="metadata" src="{{ $url }}"></video>
                                    <a href="{{ $url }}" target="_blank" class="om-file-name">{{ $file->original_name }} · {{ $file->size_label }}</a>
                                </div>
                            @else
                                <a href="{{ $url }}" target="_blank" class="om-file om-file-doc">
                                    <i class="las la-file"></i>
                                    <span>{{ $file->original_name }}<small>{{ $file->size_label }}</small></span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif

                @if ($viewer === 'admin' && $fromTeam)
                    <div class="om-receipt">
                        @if ($message->read_by_customer_at)
                            <i class="las la-check-double"></i> {{ translate('Seen by customer') }} {{ $message->read_by_customer_at->format('j M, g:i A') }}
                        @else
                            <i class="las la-check"></i> {{ translate('Not seen yet') }}
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="om-empty">
            <i class="las la-comments"></i>
            <div>{{ $viewer === 'admin' ? translate('No messages yet. Send the first message to this customer below.') : translate('No messages yet.') }}</div>
        </div>
    @endforelse
</div>
