@php
    $banner = $pageBuilderData['banner'] ?? [];
    $styles = $pageBuilderData['styles'] ?? [];
    $metaImageValue = $isEdit ? $page->meta_image : '';
    $sectionGroups = $pageBuilderData['sections'] ?? [];
@endphp

<div class="card-header px-0 pt-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h6 class="fw-600 mb-1">{{ translate('Content Builder') }}</h6>
            <p class="mb-0 text-muted">{{ translate('Create each page with sections first, then add widgets inside each section just like a visual page builder.') }}</p>
        </div>
    </div>
</div>

<div class="card-body px-0">
    <div class="ttf-builder-shell">
        <div class="ttf-editor-layout is-sidebar-hidden" data-page-builder-layout>
            <div class="ttf-editor-main">
                <input type="hidden" name="template_type" value="{{ \App\Support\CustomPageTemplate::TEMPLATE_STORY }}">

                <div class="ttf-editor-toolbar">
                    <div class="ttf-editor-toolbar__copy">
                        <h3>{{ translate('Section Builder') }}</h3>
                        <p>{{ translate('Build every custom page from the same widget library. Create a section first, then add or move widgets inside it just like a visual page builder.') }}</p>
                    </div>
                    <div class="ttf-editor-toolbar__actions">
                        <div class="ttf-editor-toolbar__cluster">
                            <button type="button" class="btn btn-soft-primary" data-add-group>
                                <i class="las la-plus"></i>
                                {{ translate('Add Section') }}
                            </button>
                            <button type="button" class="btn btn-soft-primary" data-toggle-page-sidebar>
                                <i class="las la-sliders-h"></i>
                                <span data-sidebar-toggle-label>{{ translate('Show Page Settings') }}</span>
                            </button>
                        </div>
                        <button type="submit" class="btn btn-primary ttf-toolbar-save">{{ $isEdit ? translate('Update Page') : translate('Save Page') }}</button>
                    </div>
                </div>

                <div class="ttf-sections-canvas">
                    <div class="ttf-sections-canvas__header">
                        <div>
                            <h6 class="mb-1">{{ translate('Page Sections') }}</h6>
                            <small class="text-muted">{{ translate('Each section is a clean container. Open a section to add text editor, image, grid, TOC, split layout or full width widgets.') }}</small>
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
                        <h6 class="mb-1">{{ translate('No Sections Added Yet') }}</h6>
                        <p class="mb-0 text-muted">{{ translate('Start with a section, then add text, image, grid, TOC or showcase widgets inside it.') }}</p>
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

            <aside class="ttf-editor-sidebar" data-page-sidebar>
                <div class="ttf-sidebar-stack">
                    <div class="card ttf-builder-card ttf-builder-card--sticky">
                        <div class="card-header">
                            <h6 class="mb-0">{{ translate('Publish') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="ttf-sidebar-note">
                                <strong>{{ translate('Workflow') }}:</strong>
                                {{ translate('Build sections on the left, then use this sidebar only for page-wide settings like banner, colors and SEO.') }}
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg">{{ $isEdit ? translate('Update Page') : translate('Save Page') }}</button>
                        </div>
                    </div>

                    <div class="card ttf-builder-card" data-page-settings-card>
                        <div class="card-header">
                            <h6 class="mb-0">{{ translate('Common Banner') }}</h6>
                        </div>
                        <div class="card-body">
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
                    </div>

                    <div class="card ttf-builder-card" data-page-settings-card>
                        <div class="card-header">
                            <h6 class="mb-0">{{ translate('Typography & Colors') }}</h6>
                        </div>
                        <div class="card-body">
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
                                <div class="col-6"><div class="form-group"><label>{{ translate('Page BG') }}</label><input type="color" class="form-control" name="builder[styles][page_background]" value="{{ $styles['page_background'] ?? '#fbf7f2' }}"></div></div>
                                <div class="col-6"><div class="form-group"><label>{{ translate('Card BG') }}</label><input type="color" class="form-control" name="builder[styles][card_background]" value="{{ $styles['card_background'] ?? '#fffdf9' }}"></div></div>
                                <div class="col-6"><div class="form-group"><label>{{ translate('Border') }}</label><input type="color" class="form-control" name="builder[styles][card_border_color]" value="{{ $styles['card_border_color'] ?? '#e3d6ca' }}"></div></div>
                                <div class="col-6"><div class="form-group"><label>{{ translate('Accent') }}</label><input type="color" class="form-control" name="builder[styles][accent_color]" value="{{ $styles['accent_color'] ?? '#c8883a' }}"></div></div>
                                <div class="col-6"><div class="form-group"><label>{{ translate('Heading') }}</label><input type="color" class="form-control" name="builder[styles][heading_color]" value="{{ $styles['heading_color'] ?? '#2c2218' }}"></div></div>
                                <div class="col-6"><div class="form-group"><label>{{ translate('Sub Heading') }}</label><input type="color" class="form-control" name="builder[styles][subheading_color]" value="{{ $styles['subheading_color'] ?? '#5b4839' }}"></div></div>
                                <div class="col-6"><div class="form-group"><label>{{ translate('Paragraph') }}</label><input type="color" class="form-control" name="builder[styles][paragraph_color]" value="{{ $styles['paragraph_color'] ?? '#564638' }}"></div></div>
                                <div class="col-6"><div class="form-group"><label>{{ translate('Muted') }}</label><input type="color" class="form-control" name="builder[styles][muted_color]" value="{{ $styles['muted_color'] ?? '#8a786a' }}"></div></div>
                                <div class="col-6"><div class="form-group"><label>{{ translate('Width') }}</label><input type="number" class="form-control" name="builder[styles][container_width]" value="{{ $styles['container_width'] ?? '1240' }}" min="960" max="1600"></div></div>
                                <div class="col-6"><div class="form-group"><label>{{ translate('Spacing') }}</label><input type="number" class="form-control" name="builder[styles][section_spacing]" value="{{ $styles['section_spacing'] ?? '54' }}" min="24" max="120"></div></div>
                                <div class="col-6"><div class="form-group"><label>{{ translate('TOC BG') }}</label><input type="color" class="form-control" name="builder[styles][toc_background]" value="{{ $styles['toc_background'] ?? '#fffdf9' }}"></div></div>
                                <div class="col-6"><div class="form-group mb-0"><label>{{ translate('TOC Border') }}</label><input type="color" class="form-control" name="builder[styles][toc_border_color]" value="{{ $styles['toc_border_color'] ?? '#d8cabd' }}"></div></div>
                            </div>
                        </div>
                    </div>

                    <div class="card ttf-builder-card" data-page-settings-card>
                        <div class="card-header">
                            <h6 class="mb-0">{{ translate('SEO Fields') }}</h6>
                        </div>
                        <div class="card-body">
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
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
