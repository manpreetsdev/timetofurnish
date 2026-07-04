@php
    $group = array_merge(\App\Support\CustomPageTemplate::sectionGroupTemplate(), $group);
    $showBackground = ($group['show_background'] ?? '0') === '1';
    $showBorder = ($group['show_border'] ?? '0') === '1';
    $usePadding = ($group['use_padding'] ?? '0') === '1';
    $backgroundColor = !empty($group['background_color']) ? $group['background_color'] : 'var(--ttf-card-bg)';
    $borderColor = !empty($group['border_color']) ? $group['border_color'] : 'var(--ttf-card-border)';
    $visibilityClasses = collect([
        ($group['show_on_desktop'] ?? '1') === '0' ? 'ttf-hide-desktop' : null,
        ($group['show_on_ipad_pro'] ?? '1') === '0' ? 'ttf-hide-ipad-pro' : null,
        ($group['show_on_ipad'] ?? '1') === '0' ? 'ttf-hide-ipad' : null,
        ($group['show_on_phone'] ?? '1') === '0' ? 'ttf-hide-phone' : null,
    ])->filter()->implode(' ');
@endphp

<section class="ttf-page-group {{ $visibilityClasses }}" style="--group-bg: {{ $showBackground ? $backgroundColor : 'transparent' }}; --group-border: {{ $showBorder ? $borderColor : 'transparent' }}; --group-radius: {{ ($showBackground || $showBorder) ? (int) ($group['border_radius'] ?? 24) : 0 }}px; --group-padding: {{ $usePadding ? (int) ($group['section_padding'] ?? 28) : 0 }}px; --group-gap: {{ (int) ($group['section_gap'] ?? 18) }}px;">
    @foreach (($group['widgets'] ?? []) as $widget)
        @includeIf('frontend.custom-pages.sections.' . ($widget['type'] ?? 'rich_text'), ['section' => $widget])
    @endforeach
</section>
