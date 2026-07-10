@php
    $group = array_merge(\App\Support\CustomPageTemplate::sectionGroupTemplate(), $group);
    $showBackground = ($group['show_background'] ?? '0') === '1';
    $showBorder = ($group['show_border'] ?? '0') === '1';
    $usePadding = ($group['use_padding'] ?? '0') === '1';
    $backgroundColor = !empty($group['background_color']) ? $group['background_color'] : 'var(--ttf-card-bg)';
    $borderColor = !empty($group['border_color']) ? $group['border_color'] : 'var(--ttf-card-border)';
    $borderStyle = $group['border_style'] ?? 'solid';
    $borderWidth = !empty($group['border_width']) ? (int) $group['border_width'] : ($showBorder ? 1 : 0);
    $paddingTop = !empty($group['padding_top']) ? (int) $group['padding_top'] : ($usePadding ? (int) ($group['section_padding'] ?? 28) : 0);
    $paddingBottom = !empty($group['padding_bottom']) ? (int) $group['padding_bottom'] : ($usePadding ? (int) ($group['section_padding'] ?? 28) : 0);
    $defaultSidePadding = ($showBackground || $showBorder) ? 28 : 0;
    $paddingLeft = isset($group['padding_left']) && $group['padding_left'] !== ''
        ? (int) $group['padding_left']
        : ((isset($group['padding_left_right']) && $group['padding_left_right'] !== '') ? (int) $group['padding_left_right'] : $defaultSidePadding);
    $paddingRight = isset($group['padding_right']) && $group['padding_right'] !== ''
        ? (int) $group['padding_right']
        : ((isset($group['padding_left_right']) && $group['padding_left_right'] !== '') ? (int) $group['padding_left_right'] : $defaultSidePadding);
    
    $columnsCount = (int) ($group['columns'] ?? 1);
    if ($columnsCount < 1) $columnsCount = 1;
    $widgets = $group['widgets'] ?? [];
    
    // Group widgets by column_index
    $columnsWidgets = [];
    for ($i = 0; $i < $columnsCount; $i++) {
        $columnsWidgets[$i] = [];
    }
    foreach ($widgets as $widget) {
        $colIdx = (int) ($widget['column_index'] ?? 0);
        if ($colIdx >= $columnsCount) {
            $colIdx = $columnsCount - 1;
        }
        if ($colIdx < 0) {
            $colIdx = 0;
        }
        $columnsWidgets[$colIdx][] = $widget;
    }

    $visibilityClasses = collect([
        ($group['show_on_desktop'] ?? '1') === '0' ? 'ttf-hide-desktop' : null,
        ($group['show_on_ipad_pro'] ?? '1') === '0' ? 'ttf-hide-ipad-pro' : null,
        ($group['show_on_ipad'] ?? '1') === '0' ? 'ttf-hide-ipad' : null,
        ($group['show_on_phone'] ?? '1') === '0' ? 'ttf-hide-phone' : null,
    ])->filter()->implode(' ');
@endphp

<section class="ttf-page-group {{ $visibilityClasses }} ttf-grid-cols-{{ $columnsCount }}" style="--group-bg: {{ $showBackground ? $backgroundColor : 'transparent' }}; --group-border: {{ $showBorder ? $borderColor : 'transparent' }}; --group-border-width: {{ $borderWidth }}px; --group-border-style: {{ $borderStyle }}; --group-radius: {{ ($showBackground || $showBorder) ? (int) ($group['border_radius'] ?? 24) : 0 }}px; --group-padding-top: {{ $paddingTop }}px; --group-padding-bottom: {{ $paddingBottom }}px; --group-padding-left: {{ $paddingLeft }}px; --group-padding-right: {{ $paddingRight }}px; --group-gap: {{ (int) ($group['section_gap'] ?? 18) }}px;">
    @for ($col = 0; $col < $columnsCount; $col++)
        <div class="ttf-grid-column">
            @foreach ($columnsWidgets[$col] as $widget)
                @includeIf('frontend.custom-pages.sections.' . ($widget['type'] ?? 'rich_text'), ['section' => $widget])
            @endforeach
        </div>
    @endfor
</section>
