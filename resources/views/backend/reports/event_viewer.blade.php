@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="align-items-center">
        <h1 class="h3">{{ translate('Event Viewer') }}</h1>
        <p class="mb-0 text-muted">{{ translate('Track recent uploads, updates, deletes, and login events from across the admin and customer side.') }}</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form action="{{ route('event-viewer.index') }}" method="GET">
                <div class="card-header row gutters-5">
                    <div class="col-12 col-lg-3 mb-2 mb-lg-0">
                        <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="{{ translate('Search description, subject, url') }}">
                    </div>
                    <div class="col-12 col-lg-2 mb-2 mb-lg-0">
                        <select class="form-control form-control-sm aiz-selectpicker" name="action" data-live-search="true">
                            <option value="">{{ translate('All Actions') }}</option>
                            @foreach (['created', 'updated', 'deleted', 'restored', 'permissions_updated', 'login', 'logout'] as $action)
                                <option value="{{ $action }}" @selected(request('action') === $action)>{{ translate(ucwords(str_replace('_', ' ', $action))) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 mb-2 mb-lg-0">
                        <select class="form-control form-control-sm aiz-selectpicker" name="user_id" data-live-search="true">
                            <option value="">{{ translate('All Users') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>{{ $user->name }} @if($user->user_type) ({{ $user->user_type }}) @endif</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 mb-2 mb-lg-0">
                        <select class="form-control form-control-sm aiz-selectpicker" name="subject_type" data-live-search="true">
                            <option value="">{{ translate('All Modules') }}</option>
                            @foreach ($subjectTypes as $subjectType)
                                <option value="{{ $subjectType }}" @selected(request('subject_type') === $subjectType)>{{ class_basename($subjectType) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-lg-2 mb-2 mb-lg-0">
                        <input type="text" class="form-control form-control-sm aiz-date-range" name="date_range" value="{{ request('date_range') }}" placeholder="{{ translate('Date range') }}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                    </div>
                    <div class="col-12 col-lg-1">
                        <button class="btn btn-primary btn-sm btn-block" type="submit">{{ translate('Filter') }}</button>
                    </div>
                </div>
            </form>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Time') }}</th>
                                <th>{{ translate('User') }}</th>
                                <th>{{ translate('Action') }}</th>
                                <th>{{ translate('Subject') }}</th>
                                <th>{{ translate('Summary') }}</th>
                                <th>{{ translate('Source') }}</th>
                                <th class="text-right">{{ translate('Details') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $key => $log)
                                @php
                                    $summary = $log->change_summary;
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 + ($logs->currentPage() - 1) * $logs->perPage() }}</td>
                                    <td>{{ $log->created_at ? $log->created_at->format('d-m-Y H:i') : '-' }}</td>
                                    <td>
                                        @if ($log->user)
                                            <div class="fw-500">{{ $log->user->name }}</div>
                                            <small class="text-muted">{{ $log->user->user_type }}</small>
                                        @else
                                            <span class="text-muted">{{ translate('System / Guest') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-inline badge-soft-info">{{ translate(ucwords(str_replace('_', ' ', $log->action))) }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-500">{{ $log->subject_label }}</div>
                                        <small class="text-muted">#{{ $log->subject_id ?? '-' }}</small>
                                    </td>
                                    <td style="min-width: 280px;">
                                        @if (!empty($summary))
                                            <ul class="mb-0 pl-3">
                                                @foreach (array_slice($summary, 0, 3) as $line)
                                                    <li>{{ $line }}</li>
                                                @endforeach
                                                @if (count($summary) > 3)
                                                    <li class="text-muted">{{ translate('More changes available in details') }}</li>
                                                @endif
                                            </ul>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td style="min-width: 220px;">
                                        <div class="text-truncate" style="max-width: 220px;" title="{{ $log->url }}">{{ $log->url ?? '-' }}</div>
                                        <small class="text-muted d-block">{{ $log->ip_address ?? '-' }}</small>
                                    </td>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-soft-primary btn-icon btn-circle btn-sm" data-toggle="modal" data-target="#activity-log-modal-{{ $log->id }}">
                                            <i class="las la-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">{{ translate('No events found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @foreach ($logs as $log)
                    <div class="modal fade" id="activity-log-modal-{{ $log->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ translate('Event Details') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-4">
                                        <h6 class="mb-2">{{ translate('Event Information') }}</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                                <tbody>
                                                    @foreach ($log->detailRows() as $row)
                                                        <tr>
                                                            <th style="width: 220px;">{{ translate($row['label']) }}</th>
                                                            <td class="text-break">{{ $row['value'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    @if ($log->hasChangeTable())
                                        <div class="mb-4">
                                            <h6 class="mb-2">{{ translate('Updated Fields') }}</h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ translate('Field') }}</th>
                                                            <th>{{ translate('Previous Value') }}</th>
                                                            <th>{{ translate('New Value') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($log->propertyRows() as $row)
                                                            <tr>
                                                                <td>{{ $row['field'] }}</td>
                                                                <td class="text-break">{{ $row['old'] }}</td>
                                                                <td class="text-break">{{ $row['new'] }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @elseif ($log->hasAttributeTable())
                                        <div class="mb-4">
                                            <h6 class="mb-2">{{ translate('Captured Values') }}</h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ translate('Field') }}</th>
                                                            <th>{{ translate('Value') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($log->propertyRows() as $row)
                                                            <tr>
                                                                <td>{{ $row['field'] }}</td>
                                                                <td class="text-break">{{ $row['value'] }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @elseif (!empty($log->propertyRows()))
                                        <div class="mb-0">
                                            <h6 class="mb-2">{{ translate('Additional Data') }}</h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ translate('Field') }}</th>
                                                            <th>{{ translate('Value') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($log->propertyRows() as $row)
                                                            <tr>
                                                                <td>{{ $row['field'] }}</td>
                                                                <td class="text-break">{{ $row['value'] }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="aiz-pagination mt-4">
                    {{ $logs->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
