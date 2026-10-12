@extends('backend.layouts.app')

@section('content')
    @php
        // Templates are shared by services with the same name
        $serviceTemplateCounts = \App\Models\ServiceEmailTemplate::with('service')->get()
            ->filter(fn ($template) => $template->service)
            ->groupBy(fn ($template) => \App\Services\OrderEmailService::normaliseName($template->service->name))
            ->map->count();
    @endphp

    <div class="svc-hero">
        <div>
            <span class="svc-eyebrow">{{ translate('Products') }}</span>
            <h1>{{ translate('Product Services') }}</h1>
            <p>{{ translate('Extra services customers can add at checkout, such as room-of-choice delivery or assembly. Each service can have its own customer email.') }}</p>
        </div>
        <div class="svc-hero-actions">
            <a href="{{ route('order-emails.templates') }}" class="btn svc-btn"><i class="las la-envelope"></i> {{ translate('Service email templates') }}</a>
            @if (isset($service))
                <a href="{{ route('services.index') }}" class="btn svc-btn svc-btn-primary"><i class="las la-plus"></i> {{ translate('Add new service') }}</a>
            @endif
        </div>
    </div>

    <div class="row gutters-10">
        <div class="col-lg-8">
            <div class="svc-card">
                <div class="svc-card-head">
                    <h2>{{ translate('Services') }} <span>({{ $services->count() }})</span></h2>
                </div>
                <x-admin.table min-width="700px">
                    <thead>
                        <tr>
                            <th>{{ translate('Service') }}</th>
                            <th class="text-right">{{ translate('Price') }}</th>
                            <th>{{ translate('Categories') }}</th>
                            <th class="text-center">{{ translate('Customer email') }}</th>
                            <th class="text-center">{{ translate('Status') }}</th>
                            <th class="text-right">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $key => $serv)
                            @php
                                $templateCount = $serviceTemplateCounts[\App\Services\OrderEmailService::normaliseName($serv->name)] ?? 0;
                            @endphp
                            <tr @if (isset($service) && $service->id === $serv->id) class="svc-row-editing" @endif>
                                <td class="ttf-wrap" style="min-width: 180px; max-width: 240px;">
                                    <strong class="svc-name">{{ $serv->name }}</strong>
                                    @if ($serv->description)
                                        <div class="svc-desc">{{ \Illuminate\Support\Str::limit($serv->description, 90) }}</div>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <strong>{{ $serv->type === 'percent' ? rtrim(rtrim(number_format($serv->price, 2), '0'), '.') . '%' : single_price($serv->price) }}</strong>
                                    <div class="svc-desc">{{ ucfirst($serv->type) }}</div>
                                </td>
                                <td class="admin-table-categories ttf-wrap">
                                    <div class="admin-category-badges">
                                        @foreach ($serv->categories as $catIndex => $cat)
                                            <span class="svc-badge {{ $catIndex >= 4 ? 'd-none extra-category-badge' : '' }}">{{ $cat->getTranslation('name') }}</span>
                                        @endforeach
                                    </div>
                                    @if ($serv->categories->count() > 4)
                                        <button type="button" class="btn btn-link btn-xs p-0 admin-badges-read-more">
                                            +{{ $serv->categories->count() - 4 }} {{ translate('more') }}
                                        </button>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ $templateCount ? route('order-emails.templates', ['service' => $serv->id]) : route('order-emails.templates.create', ['service' => $serv->id]) }}"
                                        class="svc-email-btn {{ $templateCount ? 'has-templates' : '' }}"
                                        title="{{ $templateCount ? translate('View the email templates for this service') : translate('Create an email template for this service') }}">
                                        <i class="las la-envelope"></i>
                                        @if ($templateCount)
                                            {{ $templateCount }} {{ $templateCount > 1 ? translate('templates') : translate('template') }}
                                        @else
                                            {{ translate('Add email') }}
                                        @endif
                                    </a>
                                </td>
                                <td class="text-center">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox"
                                            onchange="update_service_status(this, {{ $serv->id }})"
                                            {{ $serv->status ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                        href="{{ route('services.edit', $serv->id) }}"
                                        title="{{ translate('Edit') }}">
                                        <i class="las la-edit"></i>
                                    </a>
                                    <form action="{{ route('services.destroy', $serv->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-soft-danger btn-icon btn-circle btn-sm" onclick="return confirm('{{ translate('Are you sure you want to delete this service?') }}');" title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-admin.table>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="svc-card svc-form">
                <div class="svc-card-head">
                    <h2>{{ isset($service) ? translate('Edit service') : translate('Add new service') }}</h2>
                    @if (isset($service))
                        <a href="{{ route('services.index') }}" class="btn svc-btn btn-sm">{{ translate('Cancel') }}</a>
                    @endif
                </div>
                <div>
                    <form
                        action="{{ isset($service) ? route('services.update', $service->id) : route('services.store') }}"
                        method="POST">
                        @csrf
                        @if (isset($service))
                            {{-- Laravel expects POST with _method PATCH/PUT for resource update --}}
                            @method('POST')
                        @endif

                        <div class="form-group mb-3">
                            <label for="name"><b>{{ translate('Name') }}</b></label>
                            <input
                                type="text"
                                placeholder="{{ translate('Name') }}"
                                id="name"
                                name="name"
                                class="form-control"
                                value="{{ old('name', isset($service) ? $service->name : '') }}"
                                required
                            >
                        </div>

                        <div class="form-group mb-3">
                            <label for="type"><b>{{ translate('Type') }}</b></label>
                            <select id="type" name="type" class="form-control" required>
                                <option value="fixed" {{ old('type', isset($service) ? $service->type : '') == 'fixed' ? 'selected' : '' }}>{{ translate('Fixed') }}</option>
                                <option value="percent" {{ old('type', isset($service) ? $service->type : '') == 'percent' ? 'selected' : '' }}>{{ translate('Percent') }}</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="price"><b>{{ translate('Price') }}</b></label>
                            <input
                                type="number" step="0.01" min="0"
                                id="price" name="price" class="form-control"
                                value="{{ old('price', isset($service) ? $service->price : '') }}"
                                required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description"><b>{{ translate('Description') }}</b></label>
                            <textarea name="description" id="description" class="form-control">{{ old('description', isset($service) ? $service->description : '') }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="mb-0"><b>{{ translate('Categories') }}</b></label>
                                <div>
                                    <button type="button" class="btn btn-soft-primary btn-xs" id="select-all-service-categories">{{ translate('Select All') }}</button>
                                    <button type="button" class="btn btn-soft-secondary btn-xs" id="deselect-all-service-categories">{{ translate('Deselect All') }}</button>
                                </div>
                            </div>
                            @php
                                $selectedCats = old('categories', isset($service) ? $service->categories->pluck('id')->toArray() : []);
                            @endphp
                            <div class="admin-category-picker" id="service-category-picker">
                                @foreach($categories as $category)
                                    @include('backend.productServices.partials.category-checkbox', [
                                        'category' => $category,
                                        'selectedCats' => $selectedCats,
                                        'level' => 0
                                    ])
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-link btn-sm px-0 admin-category-read-more" data-target="#service-category-picker">
                                {{ translate('Read more') }}
                            </button>
                        </div>

                        <div class="form-group mb-3">
                            <label for="sort_order"><b>{{ translate('Sort Order') }}</b></label>
                            <input
                                type="number" id="sort_order" name="sort_order"
                                class="form-control"
                                value="{{ old('sort_order', isset($service) ? $service->sort_order : 0) }}" min="0">
                        </div>

                        <div class="form-group mb-3">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="status" value="1"
                                    {{ old('status', isset($service) ? $service->status : 1) ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <span class="ml-2">{{ translate('Enable Service') }}</span>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn svc-btn svc-btn-primary px-4">{{ isset($service) ? translate('Update service') : translate('Save service') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        function update_service_status(el, id) {
            var payload = new URLSearchParams();
            payload.append('_token', '{{ csrf_token() }}');
            payload.append('id', id);
            payload.append('status', el.checked ? 1 : 0);

            fetch('{{ route('services.update_status') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'text/plain',
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
                },
                body: payload.toString()
            }).then(function(response) {
                return response.text();
            }).then(function(data) {
                var success = data.trim() == '1';
                AIZ.plugins.notify(success ? 'success' : 'danger', success
                    ? '{{ translate('Service status updated successfully') }}'
                    : '{{ translate('Something went wrong') }}');
            });
        }

        (function() {
            var checkboxSelector = '.service-category-checkbox';
            var childContainerSelector = '.service-category-children';
            var toggleSelector = '.service-category-toggle';

            function childCheckboxes(parentId) {
                return Array.prototype.filter.call(document.querySelectorAll(checkboxSelector), function(checkbox) {
                    return checkbox.getAttribute('data-parent-id') === String(parentId);
                });
            }

            function childContainers(parentId) {
                return Array.prototype.filter.call(document.querySelectorAll(childContainerSelector), function(container) {
                    return container.getAttribute('data-parent-id') === String(parentId);
                });
            }

            function setDescendants(parentId, checked) {
                childCheckboxes(parentId).forEach(function(checkbox) {
                    checkbox.checked = checked;
                    setDescendants(checkbox.getAttribute('data-category-id'), checked);
                });
            }

            document.addEventListener('click', function(event) {
                if (event.target.id === 'select-all-service-categories') {
                    document.querySelectorAll(checkboxSelector).forEach(function(checkbox) {
                        checkbox.checked = true;
                    });
                }

                if (event.target.id === 'deselect-all-service-categories') {
                    document.querySelectorAll(checkboxSelector).forEach(function(checkbox) {
                        checkbox.checked = false;
                    });
                }

                if (event.target.matches('.admin-category-read-more')) {
                    var picker = document.querySelector(event.target.getAttribute('data-target'));
                    if (picker) {
                        picker.classList.toggle('is-expanded');
                        event.target.textContent = picker.classList.contains('is-expanded')
                            ? '{{ translate('Show less') }}'
                            : '{{ translate('Read more') }}';
                    }
                }

                if (event.target.matches('.admin-badges-read-more')) {
                    var cell = event.target.closest('.admin-table-categories');
                    if (cell) {
                        var isExpanded = cell.classList.toggle('is-expanded');
                        cell.querySelectorAll('.extra-category-badge').forEach(function(badge) {
                            badge.classList.toggle('d-none', !isExpanded);
                        });
                        event.target.textContent = isExpanded
                            ? '{{ translate('Show less') }}'
                            : '+' + cell.querySelectorAll('.extra-category-badge').length + ' {{ translate('more') }}';
                    }
                }

                if (event.target.matches(toggleSelector)) {
                    var categoryId = event.target.getAttribute('data-category-id');
                    var isExpanded = event.target.getAttribute('aria-expanded') === 'true';
                    var nextExpanded = !isExpanded;

                    event.target.setAttribute('aria-expanded', nextExpanded ? 'true' : 'false');
                    event.target.textContent = nextExpanded ? '−' : '+';

                    childContainers(categoryId).forEach(function(container) {
                        container.style.display = nextExpanded ? '' : 'none';
                    });

                    if (nextExpanded) {
                        var parentCheckbox = document.querySelector(checkboxSelector + '[data-category-id="' + categoryId + '"]');
                        if (parentCheckbox) {
                            parentCheckbox.checked = true;
                        }
                        setDescendants(categoryId, true);
                    }
                }
            });

            document.addEventListener('change', function(event) {
                if (event.target.matches(checkboxSelector)) {
                    setDescendants(event.target.getAttribute('data-category-id'), event.target.checked);
                }
            });
        })();
    </script>
    <style>
        .svc-hero, .svc-card {
            background: #fff;
            border: 1px solid #e8e5e1;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(104, 91, 78, 0.05);
        }
        .svc-hero {
            display: flex; align-items: center; justify-content: space-between; gap: 20px;
            padding: 22px 24px; margin: 8px 0 14px;
        }
        .svc-eyebrow { display: block; color: #8c7e70; font-size: 11px; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; }
        .svc-hero h1 { margin: 3px 0 5px; font-size: 24px; font-weight: 700; color: #39322a; }
        .svc-hero p { margin: 0; max-width: 600px; color: #8c7e70; font-size: 13px; line-height: 1.5; }
        .svc-hero-actions { display: flex; flex-wrap: wrap; gap: 8px; }
        .svc-btn {
            display: inline-flex; align-items: center; gap: 6px; min-height: 38px; padding: 0 14px;
            border: 1px solid #dcd4c9; border-radius: 8px; background: #f3efe9; color: #685b4e;
            font-size: 13px; font-weight: 600; white-space: nowrap;
        }
        .svc-btn.btn-sm { min-height: 30px; padding: 0 10px; font-size: 12px; }
        .svc-btn:hover { background: #ece5db; color: #685b4e; }
        .svc-btn-primary { background: #685b4e; border-color: #685b4e; color: #fff; }
        .svc-btn-primary:hover { background: #4f453b; color: #fff; }
        .svc-card { padding: 18px; margin-bottom: 14px; }
        .svc-card-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px; }
        .svc-card-head h2 { margin: 0; font-size: 16px; font-weight: 700; color: #39322a; }
        .svc-card-head h2 span { color: #8c7e70; font-weight: 500; }
        .svc-name { color: #39322a; font-size: 13px; }
        .svc-desc { margin-top: 2px; color: #8c7e70; font-size: 11.5px; line-height: 1.4; }
        .svc-badge {
            display: inline-block; margin: 0 4px 5px 0; padding: 3px 8px; border-radius: 999px;
            background: #f3efe9; color: #685b4e; font-size: 11px; font-weight: 600; line-height: 1.35;
        }
        .svc-email-btn {
            display: inline-flex; align-items: center; gap: 5px; height: 30px; padding: 0 11px;
            border: 1px dashed #cfc5b8; border-radius: 8px; background: #fff; color: #8c7e70 !important;
            font-size: 12px; font-weight: 600; white-space: nowrap; text-decoration: none !important;
            transition: all .15s ease;
        }
        .svc-email-btn.has-templates { border-style: solid; border-color: #bfe3cc; background: #e8f7ee; color: #137333 !important; }
        .svc-email-btn:hover { border-color: #685b4e; background: #f3efe9; color: #685b4e !important; }
        .svc-row-editing td { background: #fffaf0 !important; }
        .svc-form label b { font-size: 12.5px; color: #4a4239; }
        .svc-form .form-control { border-radius: 8px; }
        @media (max-width: 991.98px) {
            .svc-hero { flex-direction: column; align-items: flex-start; }
        }

        .admin-category-picker {
            max-height: 260px;
            overflow: hidden;
            border: 1px solid #e8e8e8;
            border-radius: 6px;
            padding: 12px;
            background: #fff;
        }

        .admin-category-picker.is-expanded {
            max-height: none;
            overflow: visible;
        }

        .service-category-row label {
            line-height: 1.35;
        }

        .admin-table-categories {
            max-width: 190px;
        }

        .svc-card .ttf-table__table thead th,
        .svc-card .ttf-table__table tbody td {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        .admin-category-badges {
            overflow: hidden;
        }

        .admin-table-categories.is-expanded .admin-category-badges {
            max-height: none;
            overflow: visible;
        }

        .admin-category-badge {
            margin: 0 4px 6px 0;
            line-height: 1.4;
        }
    </style>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
