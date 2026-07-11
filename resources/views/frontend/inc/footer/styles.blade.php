<!-- CSS Variables injection block -->
<style>
    :root {
        --foot-bg-color: {{ $foot_bg_color }};
        --foot-border-color: {{ $foot_border_color }};
        --foot-head-color: {{ $foot_head_color }};
        --foot-text-color: {{ $foot_text_color }};
        --foot-hover-color: {{ $foot_hover_color }};
        --foot-pad-top: {{ $foot_pad_top }};
        --foot-pad-bot: {{ $foot_pad_bot }};
        --foot-pad-left: {{ $foot_pad_left }};
        --foot-pad-right: {{ $foot_pad_right }};
        --foot-mob-pad-top: {{ $foot_mob_pad_top }};
        --foot-mob-pad-bot: {{ $foot_mob_pad_bot }};
        --foot-mob-pad-left: {{ $foot_mob_pad_left }};
        --foot-mob-pad-right: {{ $foot_mob_pad_right }};
        --foot-copy-bg: {{ $foot_copy_bg }};
        --foot-copy-text: {{ $foot_copy_text }};
        --foot-news-bg: {{ $foot_news_bg }};
        --foot-news-border: {{ $foot_news_border }};
        --foot-news-btn_bg: {{ $foot_news_btn_bg }};
        --foot-social-radius: {{ $foot_social_radius }};
        --foot-news-btn-tx: {{ $foot_news_btn_tx }};
        --foot-news-border-top: {{ $news_border_top }};
        --foot-news-border-bottom: {{ $news_border_bottom }};
        --foot-news-border-left: {{ $news_border_left }};
        --foot-news-border-right: {{ $news_border_right }};
        --foot-news-pad-top: {{ $foot_news_pad_top }};
        --foot-news-pad-bot: {{ $foot_news_pad_bot }};
        --foot-news-pad-left: {{ $foot_news_pad_left }};
        --foot-news-pad-right: {{ $foot_news_pad_right }};
        --foot-news-mob-pad-top: {{ $foot_news_mob_pad_top }};
        --foot-news-mob-pad-bot: {{ $foot_news_mob_pad_bot }};
        --foot-news-mob-pad-left: {{ $foot_news_mob_pad_left }};
        --foot-news-mob-pad-right: {{ $foot_news_mob_pad_right }};
        --foot-bar-pad-top: {{ $foot_bar_pad_top }};
        --foot-bar-pad-bot: {{ $foot_bar_pad_bot }};
        --foot-bar-pad-left: {{ $foot_bar_pad_left }};
        --foot-bar-pad-right: {{ $foot_bar_pad_right }};
        --foot-bar-mob-pad-top: {{ $foot_bar_mob_pad_top }};
        --foot-bar-mob-pad-bot: {{ $foot_bar_mob_pad_bot }};
        --foot-bar-mob-pad-left: {{ $foot_bar_mob_pad_left }};
        --foot-bar-mob-pad-right: {{ $foot_bar_mob_pad_right }};
        --foot-head-font-size: {{ get_setting('foot_head_font_size', '16px') }};
        --foot-body-font-size: {{ get_setting('foot_body_font_size', '13px') }};
        --foot-mob-head-font-size: {{ $foot_mob_head_font_size }};
        --foot-mob-body-font-size: {{ $foot_mob_body_font_size }};
        --foot-body-line-height: {{ get_setting('foot_body_line_height', '1.8') }};
        --foot-col-spacing: {{ get_setting('foot_col_spacing', '20px') }};
        --foot-head-margin-bottom: {{ get_setting('foot_head_margin_bottom', '18px') }};
        @if (!empty($foot_bg_img) && $foot_bg_img != 'none')
            --foot-bg-img: url("{{ uploaded_asset($foot_bg_img) }}");
        @else
            --foot-bg-img: none;
        @endif
        @if (!empty($foot_mob_bg_img) && $foot_mob_bg_img != 'none')
            --foot-mob-bg-img: url("{{ uploaded_asset($foot_mob_bg_img) }}");
        @else
            --foot-mob-bg-img: none;
        @endif
        @if (!empty($foot_bg_pattern_left))
            --foot-bg-pattern-left: url("{{ uploaded_asset($foot_bg_pattern_left) }}");
        @else
            --foot-bg-pattern-left: none;
        @endif
        @if (!empty($foot_bg_pattern_right))
            --foot-bg-pattern-right: url("{{ uploaded_asset($foot_bg_pattern_right) }}");
        @else
            --foot-bg-pattern-right: none;
        @endif
        @if (!empty($foot_news_highlight_img))
            --foot-news-highlight-img: url("{{ uploaded_asset($foot_news_highlight_img) }}");
        @endif
    }
</style>
<link rel="stylesheet" href="{{ static_asset('assets/css/custom-footer.css') }}">
