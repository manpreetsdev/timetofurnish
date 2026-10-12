{{-- Shared email history table. Expects $logs; set $showOrder to include the order column. --}}
<x-admin.table min-width="{{ !empty($showOrder) ? '1000px' : '820px' }}">
    <thead>
        <tr>
            <th>{{ translate('Sent') }}</th>
            @if (!empty($showOrder))
                <th>{{ translate('Order') }}</th>
            @endif
            <th>{{ translate('Type') }}</th>
            <th>{{ translate('Subject') }}</th>
            <th>{{ translate('To') }}</th>
            <th>{{ translate('From') }}</th>
            <th>{{ translate('Sent by') }}</th>
            <th>{{ translate('Status') }}</th>
            <th class="text-right">{{ translate('View') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($logs as $log)
            <tr>
                <td>
                    {{ $log->created_at->format('d M Y') }}
                    <div class="text-muted fs-12">{{ $log->created_at->format('h:i A') }}</div>
                </td>
                @if (!empty($showOrder))
                    <td>
                        @if ($log->order)
                            <a href="{{ route('order-emails.compose', $log->order_id) }}">{{ $log->order->code }}</a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                @endif
                <td>
                    @if ($log->type === 'service_email')
                        <span class="badge badge-inline badge-primary">{{ translate('Service email') }}</span>
                        @if ($log->service)
                            <div class="text-muted fs-12 ttf-wrap">{{ $log->service->name }}</div>
                        @endif
                    @else
                        <span class="badge badge-inline badge-secondary">{{ translate('Automatic') }}</span>
                    @endif
                </td>
                <td class="ttf-wrap" style="min-width: 200px;">{{ $log->subject }}</td>
                <td>
                    {{ $log->to_email }}
                    @if ($log->cc)
                        <div class="text-muted fs-12">cc: {{ $log->cc }}</div>
                    @endif
                </td>
                <td>{{ $log->from_email ?: '-' }}</td>
                <td>{{ optional($log->sender)->name ?: translate('System') }}</td>
                <td>
                    @if ($log->status === 'sent')
                        <span class="badge badge-inline badge-success">{{ translate('Sent') }}</span>
                    @else
                        <span class="badge badge-inline badge-danger" title="{{ $log->error }}">{{ translate('Failed') }}</span>
                    @endif
                </td>
                <td class="text-right">
                    <a href="{{ route('order-emails.show', $log->id) }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                        title="{{ translate('View email') }}">
                        <i class="las la-eye"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ !empty($showOrder) ? 9 : 8 }}" class="text-center text-muted py-4">{{ translate('No emails yet.') }}</td>
            </tr>
        @endforelse
    </tbody>
</x-admin.table>
