{{--
    Global admin data table.

    Usage:
        <x-admin.table>
            <thead>...</thead>
            <tbody>...</tbody>
        </x-admin.table>

    Props:
        minWidth  – minimum table width before horizontal scrolling kicks in (default 900px)
        compact   – tighter row padding
        primary   – (optional) index of the column shown on mobile; defaults to the first
                    column that is not a checkbox / "#" column
        mobile    – set to false to keep the plain scrolling table on phones

    On phones each row shows only its main column; a "details" button opens a bottom
    drawer with every other column (the real cells are moved in, so toggles, links and
    buttons keep working).
--}}
@props(['minWidth' => '900px', 'compact' => false, 'primary' => null, 'mobile' => true])

<div class="ttf-table {{ $compact ? 'ttf-table--compact' : '' }}" data-ttf-table
    @if ($mobile) data-ttf-mobile @endif
    @if ($primary !== null) data-ttf-primary="{{ (int) $primary }}" @endif
    data-ttf-details-label="{{ translate('Details') }}"
    data-ttf-close-label="{{ translate('Close') }}">
    <div class="ttf-table__scroll" data-ttf-drag-scroll>
        <table {{ $attributes->merge(['class' => 'table ttf-table__table mb-0']) }} style="min-width: {{ $minWidth }};">
            {{ $slot }}
        </table>
    </div>
    <div class="ttf-table__hint" aria-hidden="true">
        <i class="las la-hand-paper"></i>
        <span>{{ translate('Drag or scroll sideways to see more') }}</span>
    </div>
</div>
