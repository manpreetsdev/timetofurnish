<div class="modal fade" id="aizUploaderModal" data-backdrop="static" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-adaptive" role="document">
        <div class="modal-content h-100 uploader-modal">
            <style>
                :root {
                    --uploader-accent: #f8fafc; /* Slate-50 */
                    --uploader-white: #ffffff;
                    --uploader-primary: #685b4e; /* brand */
                    --uploader-primary-light: #f5f2ef; /* brand light accent */
                    --uploader-border: #e2e8f0; /* Slate-200 */
                    --uploader-muted: #64748b; /* Slate-500 */
                    --uploader-text: #0f172a; /* Slate-900 */
                }
                    .modal-content .modal-body{
                            max-height: 100vh !important;
                    }
                    .aiz-file-box .card-file .card-body{
                        bottom:0 !important;
                    }
                /* Modal flex layout to prevent overflow and enable perfect scrolling */
                .modal-dialog.modal-adaptive {
                    height: calc(100vh - 60px);
                    margin: 30px auto !important;
                    display: flex;
                    align-items: center;
                }
                .uploader-modal {
                    display: flex !important;
                    flex-direction: column !important;
                    height: 100% !important;
                    width: 100% !important;
                    overflow: hidden !important;
                    background: var(--uploader-white);
                    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                    border-radius: 12px;
                    border: none;
                }
                .uploader-modal .modal-header {
                    padding: 1.25rem 1.5rem;
                    border-bottom: 1px solid var(--uploader-border);
                    background: var(--uploader-white);
                    border-top-left-radius: 12px;
                    border-top-right-radius: 12px;
                    flex: 0 0 auto !important;
                }
                .uploader-modal .modal-body {
                    flex: 1 1 auto !important;
                    display: flex !important;
                    flex-direction: column !important;
                    overflow: hidden !important;
                    padding: 1.25rem 1.5rem !important;
                    height: 0 !important; /* allows flexbox to calculate height properly */
                }
                .uploader-modal .tab-content {
                    flex: 1 1 auto !important;
                    display: flex !important;
                    flex-direction: column !important;
                    overflow: hidden !important;
                    height: 100% !important;
                }
                .uploader-modal .tab-pane {
                    display: none !important;
                    height: 100% !important;
                }
                .uploader-modal .tab-pane.active {
                    display: flex !important;
                    flex-direction: column !important;
                    overflow: hidden !important;
                }

                /* Navigation Tabs styled as modern segment control */
                .uploader-modal .nav-tabs {
                    background: #f1f5f9;
                    padding: 4px;
                    border-radius: 8px;
                    display: inline-flex;
                    gap: 2px;
                }
                .uploader-modal .nav-tabs .nav-link {
                    padding: 0.5rem 1rem;
                    border: 1px solid transparent;
                    color: var(--uploader-muted);
                    background: transparent;
                    border-radius: 6px;
                    font-weight: 500;
                    font-size: 14px;
                    transition: all 0.15s ease;
                    margin-right: 0;
                }
                .uploader-modal .nav-tabs .nav-link:hover {
                    color: var(--uploader-text);
                }
                .uploader-modal .nav-tabs .nav-link.active {
                    color: var(--uploader-text);
                    background: var(--uploader-white);
                    border-color: var(--uploader-border);
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.03);
                    font-weight: 600;
                }

                /* Modern close button styling */
                .uploader-modal .close {
                    font-size: 1.5rem;
                    color: var(--uploader-muted);
                    opacity: 0.7;
                    transition: opacity 0.15s ease, transform 0.15s ease;
                    padding: 0.5rem;
                    margin: -0.5rem;
                    outline: none;
                    font-weight: 300;
                }
                .uploader-modal .close:hover {
                    opacity: 1;
                    color: var(--uploader-text);
                    transform: scale(1.1);
                }

                /* Controls row styling */
                .uploader-top-controls {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 1rem;
                    padding-top: 1rem;
                    margin-top: 0.5rem;
                    border-top: 1px solid var(--uploader-border);
                    flex: 0 0 auto !important;
                }
                .uploader-top-controls .btn,
                .uploader-top-controls select,
                .uploader-top-controls .uploader-search,
                .uploader-top-controls .btn-primary {
                    white-space: nowrap !important;
                }

                /* Selected status text */
                .aiz-uploader-selected {
                    font-weight: 600;
                    font-size: 14px;
                    color: var(--uploader-text);
                    margin-right: 0.5rem;
                }

                /* Controls buttons common style */
                .uploader-top-controls .btn-outline-secondary {
                    border: 1px solid var(--uploader-border);
                    background-color: var(--uploader-white);
                    color: var(--uploader-text);
                    font-weight: 500;
                    font-size: 13px;
                    padding: 0.375rem 0.75rem;
                    border-radius: 8px;
                    transition: all 0.2s ease;
                }
                .uploader-top-controls .btn-outline-secondary:hover:not(:disabled) {
                    background-color: #f8fafc;
                    border-color: #cbd5e1;
                    color: var(--uploader-text);
                }

                /* Prev/Next buttons disabled state */
                #uploader_prev_btn:disabled, #uploader_next_btn:disabled {
                    opacity: 0.4;
                    cursor: not-allowed;
                    background-color: var(--uploader-white) !important;
                    border-color: var(--uploader-border) !important;
                    color: var(--uploader-muted) !important;
                }

                /* Neutral Delete Button (No red color) */
                .delete-btn {
                    border: 1px solid var(--uploader-border) !important;
                    background-color: var(--uploader-white) !important;
                    color: var(--uploader-text) !important;
                    font-weight: 500;
                    border-radius: 8px !important;
                }
                .delete-btn:hover {
                    background-color: #f8fafc !important;
                    border-color: #cbd5e1 !important;
                }

                /* Multi select button wrapping fix */
                #aiz-multi-select-toggle {
                    white-space: nowrap !important;
                }

                /* Dropdown list styling */
                .uploader-modal select.form-control {
                    appearance: none;
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
                    background-repeat: no-repeat;
                    background-position: right 12px center;
                    background-size: 16px;
                    padding-right: 36px;
                    background-color: var(--uploader-white);
                    border: 1px solid var(--uploader-border);
                    border-radius: 8px;
                    color: var(--uploader-text);
                    font-weight: 500;
                    font-size: 13px;
                    height: 38px;
                    transition: border-color 0.15s ease, box-shadow 0.15s ease;
                }
                .uploader-modal select.form-control:focus {
                    border-color: var(--uploader-primary);
                    box-shadow: 0 0 0 3px rgba(104,91,78,0.15);
                    outline: none;
                }

                /* Bootstrap select width helper */
                .uploader-modal .bootstrap-select,
                .uploader-modal .bootstrap-select .dropdown-toggle {
                    width: 100% !important;
                }

                /* Search input styling */
                .uploader-search input {
                    background-color: var(--uploader-white);
                    border: 1px solid var(--uploader-border);
                    border-radius: 8px;
                    color: var(--uploader-text);
                    padding: 8px 12px;
                    height: 38px;
                    font-size: 13px;
                    width: 100%;
                    transition: border-color 0.15s ease, box-shadow 0.15s ease;
                }
                .uploader-search input:focus {
                    border-color: var(--uploader-primary);
                    box-shadow: 0 0 0 3px rgba(104,91,78,0.15);
                    outline: none;
                }

                /* Primary brand buttons ("Upload Files", "Use Selected File") */
                .uploader-modal .btn-primary {
                    background-color: var(--uploader-primary) !important;
                    border-color: var(--uploader-primary) !important;
                    color: var(--uploader-white) !important;
                    font-weight: 600;
                    font-size: 13px;
                    padding: 8px 16px;
                    height: 38px;
                    border-radius: 8px;
                    transition: all 0.2s ease;
                    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
                }
                .uploader-modal .btn-primary:hover {
                    background-color: #54493e !important;
                    border-color: #54493e !important;
                    box-shadow: 0 4px 12px rgba(104,91,78,0.15);
                }
                .uploader-modal .btn-primary:active {
                }

                /* Filter row */
                .aiz-uploader-filter {
                    flex: 0 0 auto !important;
                }

                /* File Grid scrollable area */
                .uploader-modal .aiz-uploader-all {
                    flex: 1 1 auto !important;
                    height: auto !important;
                    overflow-y: auto !important;
                    margin-left: -10px;
                    margin-right: -10px;
                    padding: 0 10px;
                }

                /* Grid layout */
                .uploader-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
                    gap: 12px;
                    padding: 10px 4px;
                }
                @media (min-width: 576px) {
                    .uploader-grid {
                        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                        gap: 16px;
                        padding: 16px 8px;
                    }
                }
                @media (min-width: 768px) {
                    .uploader-grid {
                        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
                        gap: 20px;
                    }
                }

                /* Fix double nested card borders */
                .uploader-grid .aiz-file-box-wrap {
                    background: transparent !important;
                    border: none !important;
                    padding: 0 !important;
                    box-shadow: none !important;
                    transform: none !important;
                    position: relative;
                    width: 100% !important;
                    float: none !important;
                }
                .uploader-grid .card-file {
                    border: 1px solid var(--uploader-border) !important;
                    border-radius: 12px !important;
                    background: var(--uploader-white) !important;
                    padding: 10px !important;
                    margin-bottom: 0 !important;
                    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.2s ease !important;
                    box-shadow: none !important;
                }
                .uploader-grid .aiz-file-box-wrap:hover .card-file {
                    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.04), 0 4px 8px rgba(0, 0, 0, 0.02) !important;
                    border-color: #cbd5e1 !important;
                }

                /* Thumbnail image styling */
                .uploader-grid .card-file .card-file-thumb {
                    background-color: var(--uploader-accent) !important;
                    border-radius: 8px;
                    overflow: hidden;
                    width: calc(100% - 20px);
                    top: 10px;
                    left: 10px;
                    height: calc(100% - 60px);
                }
                .uploader-grid .thumb-img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    border-radius: 8px;
                }
                .uploader-grid .card-file .card-body {
                    width: calc(100% - 20px);
                    bottom: 8px;
                    left: 10px;
                    padding: 0 !important;
                }

                .uploader-grid .file-meta {
                    margin-top: 4px;
                    font-size: 11px;
                    font-weight: 500;
                    color: var(--uploader-muted);
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    white-space: nowrap !important;
                    overflow: hidden !important;
                    text-overflow: ellipsis !important;
                    max-width: 100% !important;
                }
                .uploader-grid .file-name {
                    display: block;
                    font-weight: 600;
                    font-size: 13px;
                    color: var(--uploader-text);
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    max-width: 100%;
                }

                /* Selected thumbnail card overlays and checkmark indicator relative to card */
                .uploader-grid .aiz-file-box-wrap[data-selected="true"] .card-file {
                    border: 2px solid var(--uploader-primary) !important;
                    box-shadow: 0 8px 24px rgba(104,91,78,0.12) !important;
                }
                .uploader-grid .aiz-file-box-wrap[data-selected="true"] .thumb-img {
                    filter: brightness(0.85);
                }
                .uploader-grid .aiz-file-box-wrap[data-selected="true"] .card-file-thumb::before {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: var(--uploader-primary);
                    opacity: 0.25;
                    border-radius: 8px;
                    z-index: 2;
                    pointer-events: none;
                }
                .uploader-grid .aiz-file-box-wrap[data-selected="true"] .card-file::after {
                    content: "✓";
                    position: absolute;
                    right: 14px;
                    top: 14px;
                    width: 24px;
                    height: 24px;
                    background: var(--uploader-primary);
                    color: var(--uploader-white);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 12px;
                    font-weight: bold;
                    z-index: 3;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
                }

                .uploader-empty {
                    padding: 48px 8px;
                    text-align: center;
                    color: var(--uploader-muted);
                    width: 100%;
                    grid-column: 1 / -1 !important;
                }
                .uploader-empty h5 {
                    font-size: 16px;
                    font-weight: 500;
                }

                /* Scrollbar customization */
                .c-scrollbar-light {
                    scrollbar-width: thin;
                    scrollbar-color: rgba(104,91,78,0.2) transparent;
                }
                .c-scrollbar-light::-webkit-scrollbar {
                    width: 6px;
                    height: 6px;
                }
                .c-scrollbar-light::-webkit-scrollbar-track {
                    background: transparent;
                }
                .c-scrollbar-light::-webkit-scrollbar-thumb {
                    background: rgba(104,91,78,0.2);
                    border-radius: 10px;
                }
                .c-scrollbar-light::-webkit-scrollbar-thumb:hover {
                    background: rgba(104,91,78,0.4);
                }

                 /* Responsive Toolbar for Mobile Devices */
                 @media (max-width: 991px) {
                     .modal-dialog.modal-adaptive {
                         height: 100vh !important;
                         height: 100dvh !important;
                         margin: 0 !important;
                         max-width: 100% !important;
                         width: 100% !important;
                     }
                     .uploader-modal {
                         border-radius: 0 !important;
                     }
                     .uploader-modal .modal-header {
                         border-radius: 0 !important;
                         padding: 0.75rem 1rem !important;
                     }
                     .uploader-modal .modal-body {
                         padding: 0.75rem !important;
                     }
                     .uploader-top-controls {
                         flex-direction: column !important;
                         align-items: stretch !important;
                         gap: 0.5rem !important;
                         border-top: none !important;
                         padding-top: 0 !important;
                         margin-top: 0 !important;
                         margin-bottom: 0.5rem !important;
                     }
                     
                     /* Hide duplicate top upload button on mobile */
                     .uploader-top-controls [data-toggle="aizUploaderAddSelected"] {
                         display: none !important;
                     }
                     
                     /* Hide text labels inside toolbar on mobile, leaving icons only */
                     .uploader-top-controls .button-text {
                         display: none !important;
                     }

                     /* First Row: horizontal alignment of select count, clear, prev, next, delete */
                     .uploader-top-controls > div:first-child {
                         display: flex !important;
                         flex-direction: row !important;
                         justify-content: space-between !important;
                         align-items: center !important;
                         width: 100% !important;
                         gap: 0.375rem !important;
                     }
                     .uploader-top-controls > div:first-child > * {
                         width: auto !important;
                         flex: 1 1 auto !important;
                         margin: 0 !important;
                         height: 36px !important;
                         display: inline-flex !important;
                         align-items: center !important;
                         justify-content: center !important;
                     }
                     .uploader-top-controls .aiz-uploader-selected {
                         flex: 1.5 1 auto !important;
                         font-size: 13px !important;
                         font-weight: 700 !important;
                         text-align: left !important;
                         justify-content: flex-start !important;
                         white-space: nowrap !important;
                         margin-right: 0.25rem !important;
                     }
                     .uploader-top-controls > div:first-child .btn {
                         padding: 0.375rem 0.5rem !important;
                         font-size: 14px !important;
                     }
                     .uploader-top-controls > div:first-child .delete-btn {
                         grid-column: auto !important;
                     }
                     
                     /* Second and Third Rows: grid alignment */
                     .uploader-top-controls > div:last-child {
                         display: grid !important;
                         grid-template-columns: 1fr 1fr !important;
                         width: 100% !important;
                         gap: 0.5rem !important;
                     }
                     .uploader-top-controls > div:last-child > * {
                         width: 100% !important;
                         max-width: 100% !important;
                         margin: 0 !important;
                     }
                     
                     /* Second Row: Search bar full width */
                     .uploader-top-controls .uploader-search {
                         grid-column: span 2 !important;
                         grid-row: 1 !important;
                     }
                     
                     /* Third Row: Multi-select & Sort picker in columns */
                     #aiz-multi-select-toggle {
                         grid-column: 1 !important;
                         grid-row: 2 !important;
                         height: 38px !important;
                     }
                     .uploader-top-controls select[name="aiz-uploader-sort"],
                     .uploader-top-controls .bootstrap-select {
                         grid-column: 2 !important;
                         grid-row: 2 !important;
                         height: 38px !important;
                     }
                     
                     /* Adjust aspect ratio of card boxes on mobile to be rectangular rather than square, saving height */
                     .uploader-grid .aiz-file-box:before {
                         padding-top: 85% !important;
                     }
                     
                     /* Compact filter margin */
                     .aiz-uploader-filter {
                         margin-bottom: 0.5rem !important;
                     }
                     
                     /* Modal footer styling for mobile */
                     .uploader-modal .modal-footer {
                         padding: 0.75rem 1rem !important;
                     }
                     .uploader-modal .modal-footer .container-fluid {
                         flex-direction: column !important;
                         gap: 0.5rem !important;
                         padding: 0 !important;
                     }
                     .uploader-modal .modal-footer .text-muted {
                         display: none !important;
                     }
                     .uploader-modal .modal-footer .btn {
                         width: 100% !important;
                         margin: 0 !important;
                     }
                 }
            </style>            <div class="modal-header bg-white d-flex align-items-center justify-content-between">
                <ul class="nav nav-tabs border-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#aiz-select-file">{{ translate('Select File') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#aiz-upload-new">{{ translate('Upload New') }}</a>
                    </li>
                </ul>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="tab-content h-100">
                    <div class="tab-pane active h-100" id="aiz-select-file">
                        
                        <div class="d-flex align-items-center justify-content-between uploader-top-controls mb-3">
                            <div class="d-flex align-items-center" style="gap:.5rem">
                                <div class="aiz-uploader-selected">0 <span class="button-text">{{ translate('File selected') }}</span></div>
                                <button type="button" class="btn btn-sm btn-outline-secondary aiz-uploader-selected-clear" title="{{ translate('Clear Selection') }}"><i class="las la-times-circle"></i><span class="button-text">&nbsp;{{ translate('Clear') }}</span></button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="uploader_prev_btn" title="{{ translate('Prev') }}"><i class="las la-angle-left"></i><span class="button-text">&nbsp;{{ translate('Prev') }}</span></button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="uploader_next_btn" title="{{ translate('Next') }}"><i class="las la-angle-right"></i><span class="button-text">&nbsp;{{ translate('Next') }}</span></button>
                                <button type="button" class="btn btn-sm delete-btn" data-toggle="aizUploaderDeleteSelected" title="{{ translate('Delete Selected') }}"><i class="las la-trash-alt"></i><span class="button-text">&nbsp;{{ translate('Delete') }}</span></button>
                            </div>

                            <div class="d-flex align-items-center" style="gap:.5rem">
                                <button type="button" id="aiz-multi-select-toggle" class="btn btn-sm btn-outline-secondary">{{ translate('Multi select') }}</button>
                                <select class="form-control form-control-sm aiz-selectpicker" name="aiz-uploader-sort">
                                    <option value="newest" selected>{{ translate('Sort by newest') }}</option>
                                    <option value="oldest">{{ translate('Sort by oldest') }}</option>
                                    <option value="smallest">{{ translate('Sort by smallest') }}</option>
                                    <option value="largest">{{ translate('Sort by largest') }}</option>
                                </select>
                                <div class="uploader-search">
                                    <input type="text" class="form-control form-control-sm" name="aiz-uploader-search" placeholder="{{ translate('Search your files...') }}">
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="aizUploaderAddSelected">{{ translate('Upload Files') }}</button>
                            </div>
                        </div>

                        <div class="aiz-uploader-filter mb-3">
                            <div class="d-flex align-items-center">
                                <label class="mb-0 d-flex align-items-center cursor-pointer" style="color:var(--uploader-muted); font-weight: 500;">
                                    <input type="checkbox" name="aiz-show-selected" id="aiz-show-selected" class="mr-2" style="width:16px; height:16px; accent-color:var(--uploader-primary)">&nbsp;{{ translate('Selected only') }}
                                </label>
                            </div>
                        </div>

                        <div class="aiz-uploader-all clearfix c-scrollbar-light">
                            <div class="uploader-grid" id="aiz-uploader-grid">
                                    <!-- Thumbnails injected by JS -->
                                    <div class="uploader-empty">
                                        <h5>{{ translate('No files found') }}</h5>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane h-100" id="aiz-upload-new">
                        <div id="aiz-upload-files" class="h-100">
                            <!-- Uppy / upload area handled by JS -->
                        </div>
                        <div id="aiz-upload-errors" class="mt-3"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="background:var(--uploader-white); border-top:1px solid var(--uploader-border);">
                <div class="container-fluid d-flex justify-content-between align-items-center" style="padding: 0.5rem 0;">
                    <div class="text-muted" style="font-size: 13px;">{{ translate('Select files from your library or upload new images.') }}</div>
                    <button type="button" class="btn btn-primary" id="aiz-uploader-use-selected">{{ translate('Use Selected File') }}</button>
                </div>
            </div>

        </div>
    </div>
</div>
