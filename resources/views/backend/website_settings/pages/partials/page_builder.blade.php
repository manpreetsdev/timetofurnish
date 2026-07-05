@php
    $banner = $pageBuilderData['banner'] ?? [];
    $styles = $pageBuilderData['styles'] ?? [];
    $metaImageValue = $isEdit ? $page->meta_image : '';
    $sectionGroups = $pageBuilderData['sections'] ?? [];
    $widgetLibrary = [
        'header_widget' => [
            'label' => translate('Heading'),
            'icon' => 'las la-heading',
            'description' => translate('Standalone heading block'),
        ],
        'rich_text' => [
            'label' => translate('Text Editor'),
            'icon' => 'las la-align-left',
            'description' => translate('Rich text copy'),
        ],
        'image_widget' => [
            'label' => translate('Single Image'),
            'icon' => 'las la-image',
            'description' => translate('Responsive image'),
        ],
        'button_widget' => [
            'label' => translate('Action Button'),
            'icon' => 'las la-link',
            'description' => translate('Call to action'),
        ],
        'split' => [
            'label' => translate('Two Column'),
            'icon' => 'las la-columns',
            'description' => translate('Image + text split'),
        ],
        'full_width' => [
            'label' => translate('Full Width'),
            'icon' => 'las la-window-maximize',
            'description' => translate('Hero wide block'),
        ],
        'image_grid' => [
            'label' => translate('Grid Cards'),
            'icon' => 'las la-th-large',
            'description' => translate('Repeatable cards'),
        ],
        'full_image' => [
            'label' => translate('Image Showcase'),
            'icon' => 'las la-image',
            'description' => translate('Large visual showcase'),
        ],
        'toc_content' => [
            'label' => translate('TOC + Content'),
            'icon' => 'las la-list-alt',
            'description' => translate('Linked sidebar TOC'),
        ],
    ];
@endphp

<div class="ttf-builder-heading">
    <div>
        <span class="ttf-step-label">{{ translate('Step 2') }}</span>
        <h6 class="fw-600 mb-1">{{ translate('Content Builder') }}</h6>
        <p class="mb-0 text-muted">{{ translate('Build sections in the canvas, keep content editing inside each widget, and use the right sidebar only for settings.') }}</p>
    </div>
</div>

<div class="ttf-builder-shell">
    <input type="hidden" name="template_type" value="{{ \App\Support\CustomPageTemplate::TEMPLATE_STORY }}">

    <div class="ttf-builder-workspace" data-page-builder-layout>
        <div class="ttf-builder-rail ttf-builder-rail--left">
            <button type="button" class="ttf-rail-btn" data-sidebar-toggle="widgets" aria-expanded="false" title="{{ translate('Open Widgets') }}">
                <i class="las la-puzzle-piece"></i>
            </button>
        </div>

        <aside class="ttf-widget-sidebar" data-builder-sidebar="widgets" aria-hidden="true">
            <div class="ttf-sidebar-panel">
                <div class="ttf-sidebar-panel__header">
                    <div>
                        <h6>{{ translate('Widgets') }}</h6>
                        <p>{{ translate('Drag these elements into any section column.') }}</p>
                    </div>
                    <button type="button" class="btn btn-icon btn-sm btn-soft-primary" data-sidebar-close="widgets" title="{{ translate('Close Widgets') }}">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="ttf-sidebar-widgets-grid">
                    @foreach ($widgetLibrary as $widgetKey => $widgetMeta)
                        <div class="ttf-sidebar-widget-item" draggable="true" data-sidebar-widget="{{ $widgetKey }}">
                            <span class="ttf-sidebar-widget-item__icon"><i class="{{ $widgetMeta['icon'] }}"></i></span>
                            <span class="ttf-sidebar-widget-item__title">{{ $widgetMeta['label'] }}</span>
                            <small class="ttf-sidebar-widget-item__text">{{ $widgetMeta['description'] }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>

        <div class="ttf-builder-canvas">
            <div class="ttf-editor-toolbar">
                <div class="ttf-editor-toolbar__copy">
                    <h3>{{ translate('Section Builder') }}</h3>
                    <p>{{ translate('Create a section first, then place widgets inside it. Edit widget content inside the canvas and use the settings sidebar only for layout, styling, and visibility.') }}</p>
                </div>
                <div class="ttf-editor-toolbar__actions">
                    <button type="button" class="btn btn-soft-primary" data-add-group>
                        <i class="las la-plus"></i>
                        {{ translate('Add Section') }}
                    </button>
                    <button type="submit" class="btn btn-primary ttf-toolbar-save">{{ $isEdit ? translate('Update Page') : translate('Save Page') }}</button>
                </div>
            </div>

            <div class="ttf-canvas-panel">
                <div class="ttf-canvas-panel__header">
                    <div>
                        <h6 class="mb-1">{{ translate('Page Sections') }}</h6>
                        <small class="text-muted">{{ translate('Open a section to manage columns. Edit widget content in the canvas and use the settings sidebar for layout, styling, and visibility.') }}</small>
                    </div>
                </div>

                <div id="ttf-section-groups" data-next-group-index="{{ count($sectionGroups) }}">
                    @foreach ($sectionGroups as $groupIndex => $group)
                        @include('backend.website_settings.pages.partials.section_group', [
                            'group' => $group,
                            'groupIndex' => $groupIndex,
                            'fontFamilyOptions' => $fontFamilyOptions,
                        ])
                    @endforeach
                </div>

                <div class="ttf-sections-empty-state @if(!empty($sectionGroups)) d-none @endif" data-group-empty-state>
                    <h6 class="mb-1">{{ translate('No sections added yet') }}</h6>
                    <p class="mb-0 text-muted">{{ translate('Add a section, then drag in text editor, heading, image, TOC, button, or layout widgets.') }}</p>
                </div>

                <template data-group-template>
                    @include('backend.website_settings.pages.partials.section_group', [
                        'group' => \App\Support\CustomPageTemplate::sectionGroupTemplate(),
                        'groupIndex' => '__GROUP_INDEX__',
                        'fontFamilyOptions' => $fontFamilyOptions,
                    ])
                </template>
            </div>
        </div>

        <div class="ttf-builder-rail ttf-builder-rail--right">
            <button type="button" class="ttf-rail-btn" data-sidebar-toggle="settings" aria-expanded="false" title="{{ translate('Open Settings') }}">
                <i class="las la-sliders-h"></i>
            </button>
        </div>

        <aside class="ttf-settings-sidebar" data-page-sidebar data-builder-sidebar="settings" aria-hidden="true">
            <div class="ttf-sidebar-panel ttf-sidebar-panel--settings">
                <div class="ttf-sidebar-panel__header">
                    <div>
                        <h6>{{ translate('Settings') }}</h6>
                        <p>{{ translate('Select a section or widget to edit its settings here.') }}</p>
                    </div>
                    <button type="button" class="btn btn-icon btn-sm btn-soft-primary" data-sidebar-close="settings" title="{{ translate('Close Settings') }}">
                        <i class="las la-times"></i>
                    </button>
                </div>

                <div class="ttf-active-settings-header d-none" id="active-settings-header">
                    <span id="active-settings-title" class="ttf-active-settings-title"></span>
                    <button type="button" class="btn btn-xs btn-soft-secondary d-flex align-items-center gap-1" id="close-active-settings">
                        <i class="las la-times"></i> {{ translate('Back') }}
                    </button>
                </div>

                <div id="active-settings-portal-target" class="ttf-active-settings-portal-target d-none"></div>

                <div class="ttf-sidebar-stack" id="default-page-settings">
                    <div class="ttf-builder-card ttf-builder-card--sticky">
                        <div class="card-header">
                            <h6 class="mb-0">{{ translate('Publish') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="ttf-sidebar-note mt-0 mb-3">
                                <strong>{{ translate('Workflow') }}:</strong>
                                {{ translate('Add sections in the canvas, drag widgets from the left, then click the settings icon on any widget to edit everything from this sidebar.') }}
                            </div>
                            <button type="submit" class="btn btn-primary btn-block btn-lg">{{ $isEdit ? translate('Update Page') : translate('Save Page') }}</button>
                        </div>
                    </div>

                    <div class="ttf-builder-card">
                        <div class="card-header">
                            <h6 class="mb-0">{{ translate('Page Settings') }}</h6>
                        </div>
                        <div class="card-body">
                            <details class="ttf-setting-group" open>
                                <summary>{{ translate('Banner') }}</summary>
                                <div class="ttf-setting-group__body">
                                    <div class="form-group">
                                        <label>{{ translate('Banner Title') }}</label>
                                        <input type="text" class="form-control" name="builder[banner][title]" value="{{ $banner['title'] ?? '' }}" placeholder="{{ translate('About Us') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ translate('Breadcrumb Label') }}</label>
                                        <input type="text" class="form-control" name="builder[banner][breadcrumb_label]" value="{{ $banner['breadcrumb_label'] ?? '' }}" placeholder="{{ translate('About Us') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ translate('Subtitle') }}</label>
                                        <input type="text" class="form-control" name="builder[banner][subtitle]" value="{{ $banner['subtitle'] ?? '' }}" placeholder="{{ translate('Optional small text under the title') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ translate('Banner Background Image') }}</label>
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="builder[banner][background_image]" class="selected-files" value="{{ $banner['background_image'] ?? '' }}">
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>{{ translate('Overlay') }}</label>
                                                <input type="text" class="form-control" name="builder[banner][overlay_color]" value="{{ $banner['overlay_color'] ?? '' }}" placeholder="rgba(...)">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>{{ translate('Height') }}</label>
                                                <input type="number" class="form-control" name="builder[banner][height]" value="{{ $banner['height'] ?? '340' }}" min="220" max="700">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>{{ translate('Title Color') }}</label>
                                                <input type="color" class="form-control" name="builder[banner][title_color]" value="{{ $banner['title_color'] ?? '#ffffff' }}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>{{ translate('Subtitle Color') }}</label>
                                                <input type="color" class="form-control" name="builder[banner][subtitle_color]" value="{{ $banner['subtitle_color'] ?? '#f8f0e7' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ translate('Title Font Family') }}</label>
                                        <select class="form-control aiz-selectpicker" name="builder[banner][title_font_family]">
                                            @foreach ($fontFamilyOptions as $fontValue => $fontLabel)
                                                <option value="{{ $fontValue }}" @selected(($banner['title_font_family'] ?? '') === $fontValue)>{{ $fontLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ translate('Subtitle Font Family') }}</label>
                                        <select class="form-control aiz-selectpicker" name="builder[banner][subtitle_font_family]">
                                            @foreach ($fontFamilyOptions as $fontValue => $fontLabel)
                                                <option value="{{ $fontValue }}" @selected(($banner['subtitle_font_family'] ?? '') === $fontValue)>{{ $fontLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>{{ translate('Text Align') }}</label>
                                        <select class="form-control aiz-selectpicker" name="builder[banner][text_align]">
                                            <option value="left" @selected(($banner['text_align'] ?? '') === 'left')>{{ translate('Left') }}</option>
                                            <option value="center" @selected(($banner['text_align'] ?? '') === 'center')>{{ translate('Center') }}</option>
                                            <option value="right" @selected(($banner['text_align'] ?? '') === 'right')>{{ translate('Right') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </details>

                            <details class="ttf-setting-group">
                                <summary>{{ translate('Typography & Colors') }}</summary>
                                <div class="ttf-setting-group__body">
                                    <div class="form-group">
                                        <label>{{ translate('Heading Font Family') }}</label>
                                        <select class="form-control aiz-selectpicker" name="builder[styles][heading_font_family]">
                                            @foreach ($fontFamilyOptions as $fontValue => $fontLabel)
                                                <option value="{{ $fontValue }}" @selected(($styles['heading_font_family'] ?? '') === $fontValue)>{{ $fontLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ translate('Sub Heading Font Family') }}</label>
                                        <select class="form-control aiz-selectpicker" name="builder[styles][subheading_font_family]">
                                            @foreach ($fontFamilyOptions as $fontValue => $fontLabel)
                                                <option value="{{ $fontValue }}" @selected(($styles['subheading_font_family'] ?? '') === $fontValue)>{{ $fontLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ translate('Paragraph Font Family') }}</label>
                                        <select class="form-control aiz-selectpicker" name="builder[styles][paragraph_font_family]">
                                            @foreach ($fontFamilyOptions as $fontValue => $fontLabel)
                                                <option value="{{ $fontValue }}" @selected(($styles['paragraph_font_family'] ?? '') === $fontValue)>{{ $fontLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-6"><div class="form-group"><label>{{ translate('Heading Wt') }}</label><select class="form-control" name="builder[styles][heading_font_weight]"><option value="400" @selected(($styles['heading_font_weight'] ?? '700') === '400')>400</option><option value="500" @selected(($styles['heading_font_weight'] ?? '700') === '500')>500</option><option value="600" @selected(($styles['heading_font_weight'] ?? '700') === '600')>600</option><option value="700" @selected(($styles['heading_font_weight'] ?? '700') === '700')>700</option><option value="800" @selected(($styles['heading_font_weight'] ?? '700') === '800')>800</option></select></div></div>
                                        <div class="col-6"><div class="form-group"><label>{{ translate('Body Wt') }}</label><select class="form-control" name="builder[styles][body_font_weight]"><option value="300" @selected(($styles['body_font_weight'] ?? '400') === '300')>300</option><option value="400" @selected(($styles['body_font_weight'] ?? '400') === '400')>400</option><option value="500" @selected(($styles['body_font_weight'] ?? '400') === '500')>500</option><option value="600" @selected(($styles['body_font_weight'] ?? '400') === '600')>600</option></select></div></div>
                                        <div class="col-6"><div class="form-group"><label>{{ translate('Width') }}</label><input type="number" class="form-control" name="builder[styles][container_width]" value="{{ $styles['container_width'] ?? '1440' }}" min="960" max="1600"></div></div>
                                        <div class="col-6"><div class="form-group"><label>{{ translate('Spacing') }}</label><input type="number" class="form-control" name="builder[styles][section_spacing]" value="{{ $styles['section_spacing'] ?? '54' }}" min="24" max="120"></div></div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-6"><div class="form-group"><label>{{ translate('Page BG') }}</label><input type="color" class="form-control" name="builder[styles][page_background]" value="{{ $styles['page_background'] ?? '#FAF8F5' }}"></div></div>
                                        <div class="col-6"><div class="form-group"><label>{{ translate('Card BG') }}</label><input type="color" class="form-control" name="builder[styles][card_background]" value="{{ $styles['card_background'] ?? '#fffdf9' }}"></div></div>
                                        <div class="col-6"><div class="form-group"><label>{{ translate('Border') }}</label><input type="text" class="form-control" name="builder[styles][card_border_color]" value="{{ $styles['card_border_color'] ?? '#21252933' }}"></div></div>
                                        <div class="col-6"><div class="form-group"><label>{{ translate('Accent') }}</label><input type="color" class="form-control" name="builder[styles][accent_color]" value="{{ $styles['accent_color'] ?? '#C27325' }}"></div></div>
                                        <div class="col-6"><div class="form-group"><label>{{ translate('Heading') }}</label><input type="color" class="form-control" name="builder[styles][heading_color]" value="{{ $styles['heading_color'] ?? '#2c2218' }}"></div></div>
                                        <div class="col-6"><div class="form-group"><label>{{ translate('Sub Heading') }}</label><input type="color" class="form-control" name="builder[styles][subheading_color]" value="{{ $styles['subheading_color'] ?? '#5b4839' }}"></div></div>
                                        <div class="col-6"><div class="form-group"><label>{{ translate('Body Text') }}</label><input type="color" class="form-control" name="builder[styles][paragraph_color]" value="{{ $styles['paragraph_color'] ?? '#393939' }}"></div></div>
                                        <div class="col-6"><div class="form-group"><label>{{ translate('Muted') }}</label><input type="color" class="form-control" name="builder[styles][muted_color]" value="{{ $styles['muted_color'] ?? '#8a786a' }}"></div></div>
                                        <div class="col-6"><div class="form-group"><label>{{ translate('TOC BG') }}</label><input type="color" class="form-control" name="builder[styles][toc_background]" value="{{ $styles['toc_background'] ?? '#fffdf9' }}"></div></div>
                                        <div class="col-6"><div class="form-group mb-0"><label>{{ translate('TOC Border') }}</label><input type="text" class="form-control" name="builder[styles][toc_border_color]" value="{{ $styles['toc_border_color'] ?? '#21252933' }}"></div></div>
                                    </div>
                                </div>
                            </details>

                            <details class="ttf-setting-group">
                                <summary>{{ translate('SEO Fields') }}</summary>
                                <div class="ttf-setting-group__body">
                                    <div class="form-group">
                                        <label>{{ translate('Meta Title') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="meta_title" value="{{ $isEdit ? $page->meta_title : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ translate('Meta Description') }}</label>
                                        <textarea class="resize-off form-control" placeholder="{{ translate('Description') }}" name="meta_description">{!! $isEdit ? $page->meta_description : '' !!}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ translate('Keywords') }}</label>
                                        <textarea class="resize-off form-control" placeholder="{{ translate('Keyword, Keyword') }}" name="keywords">{!! $isEdit ? $page->keywords : '' !!}</textarea>
                                        <small class="text-muted">{{ translate('Separate with coma') }}</small>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>{{ translate('Meta Image') }}</label>
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="meta_image" class="selected-files" value="{{ $metaImageValue }}">
                                        </div>
                                        <div class="file-preview"></div>
                                    </div>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
