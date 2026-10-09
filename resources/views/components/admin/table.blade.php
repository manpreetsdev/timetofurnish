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
--}}
@props(['minWidth' => '900px', 'compact' => false])

<div class="ttf-table {{ $compact ? 'ttf-table--compact' : '' }}" data-ttf-table>
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
