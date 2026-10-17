{{--
    Global admin filter bar (pairs with <x-admin.table>).

    <x-admin.filter-bar>
        <x-slot name="bulk"> …bulk-action dropdown… </x-slot>      (optional, left)
        <x-slot name="search"> <input name="search" …> </x-slot>   (optional, always visible)
        …filter fields (selects, date ranges)…                      (default slot)
        <x-slot name="actions"> …buttons… </x-slot>                (optional, right)
    </x-admin.filter-bar>

    Desktop: search + a "Filters" button (with active count) that opens the fields in a panel.
    Mobile: the same button opens the fields in a bottom drawer.
    Wrap each field in <div class="ttf-filter-field" data-label="Category"> for a label.
--}}
@props(['open' => false])

@php $hasFilters = trim((string) $slot) !== ''; @endphp

<div class="ttf-filterbar {{ $open ? 'is-open' : '' }}" data-ttf-filterbar>
    <div class="ttf-filterbar__top">
        @isset($bulk)
            <div class="ttf-filterbar__bulk">{{ $bulk }}</div>
        @endisset

        @isset($search)
            <div class="ttf-filterbar__search">
                <i class="las la-search"></i>
                {{ $search }}
            </div>
        @endisset

        @if ($hasFilters)
            <button type="button" class="ttf-filterbar__toggle" data-ttf-filter-toggle aria-expanded="{{ $open ? 'true' : 'false' }}">
                <i class="las la-sliders-h"></i>
                <span>{{ translate('Filters') }}</span>
                <span class="ttf-filterbar__count" data-ttf-filter-count hidden>0</span>
            </button>
        @endif

        @isset($actions)
            <div class="ttf-filterbar__actions">{{ $actions }}</div>
        @endisset
    </div>

    @if ($hasFilters)
        <div class="ttf-filterbar__backdrop" data-ttf-filter-close></div>
        <div class="ttf-filterbar__panel" data-ttf-filter-panel>
            <div class="ttf-filterbar__panel-head">
                <strong>{{ translate('Filters') }}</strong>
                <button type="button" class="ttf-filterbar__close" data-ttf-filter-close aria-label="{{ translate('Close') }}">&times;</button>
            </div>
            <div class="ttf-filterbar__fields">
                {{ $slot }}
            </div>
            <div class="ttf-filterbar__panel-foot">
                <a href="{{ url()->current() }}" class="ttf-filterbar__reset">{{ translate('Clear all') }}</a>
                <button type="button" class="ttf-filterbar__done" data-ttf-filter-close>{{ translate('Done') }}</button>
            </div>
        </div>
    @endif
</div>
