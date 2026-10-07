@inject('model', '\App\Models\Option')

@extends('backend.layouts.app')

@section('title', 'General Setting')

@section('content')
    <x-forms.post id="general-form" :action="route('admin.general.store')" class="card mb-5 mb-xxl-8">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title ">
                General Setting
            </h3>
        </div>
        <div class="card-body">
            <x-forms.text-input name="app[name]" label="Site Title" value="{{ $model->getOption('app.name') }}" isSide="1" text="The site title" required="1" />
            <x-forms.text-input name="app[tagline]" label="Tagline" value="{{ $model->getOption('app.tagline') }}" isSide="1" text="In a few words, explain what this site is about." required="1"/>
            <x-forms.text-input name="app[mail][admin]" label="Administration Email Address" value="{{ $model->getOption('app.mail.admin') }}" isSide="1" required="1" text="This address is used for admin purposes." type="email"/>
            <x-forms.checkbox.single-checkbox name="app[register][user]" label="User Registration" checked="{{ ($model->getOption('app.register.user') != null) ? $model->getOption('app.register.user') : '0'}}" text="User can register"/>
            <x-forms.checkbox.single-checkbox name="app[register][admin]" label="Admin Registration" checked="{{ ($model->getOption('app.register.admin') != null) ? $model->getOption('app.register.admin') : '0'}}" text="Admin can register"/>

            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">SEO</span>
            </h3>
            <x-forms.text-input name="app[meta_title]" label="Meta Title" value="{{ $model->getOption('app.meta_title') }}" isSide="1" text="The Meta title" required="1"/>
            <x-forms.text-input name="app[meta_description]" label="Meta Description" value="{{ $model->getOption('app.meta_description') }}" isSide="1" text="The Meta description" required="0"/>
            <x-forms.text-input name="app[meta_keywords]" label="Meta Keywords" value="{{ $model->getOption('app.meta_keywords') }}" isSide="1" text="The Meta keywords" required="0"/>

            @php
                $savedRobotsTxt = $model->getOption('app.robots_txt');
                if (empty(trim((string) $savedRobotsTxt))) {
                    $savedRobotsTxt = \App\Http\Controllers\Api\RobotsController::generateDefaultContent();
                }

                $savedLlmsTxt = $model->getOption('app.llms_txt');
                $savedLlmsFullTxt = $model->getOption('app.llms_full_txt');
                if (empty(trim((string) $savedLlmsTxt)) || empty(trim((string) $savedLlmsFullTxt))) {
                    $defaultLlms = \App\Http\Controllers\Api\LlmsController::generateDefaultContent();
                    $savedLlmsTxt = !empty(trim((string) $savedLlmsTxt)) ? $savedLlmsTxt : $defaultLlms['llms_txt'];
                    $savedLlmsFullTxt = !empty(trim((string) $savedLlmsFullTxt)) ? $savedLlmsFullTxt : $defaultLlms['llms_full_txt'];
                }
            @endphp

            <div class="d-flex justify-content-between align-items-center mt-10 mb-5">
                <div>
                    <h3 class="card-title align-items-start flex-column mb-0">
                        <span class="card-label fw-bold fs-3 mb-1">Search Engine &amp; Crawler Access (robots.txt)</span>
                    </h3>
                    <div class="text-muted fs-7">
                        Controls crawler indexing rules served dynamically at <code>/robots.txt</code>.
                    </div>
                </div>
                <button id="generate-robots-button" type="button" class="btn btn-light-primary btn-sm" style="display:none">
                    Reset to Default robots.txt
                </button>
            </div>

            <div class="row mb-8">
                <div class="col-xl-3">
                    <div class="fs-6 fw-semibold mt-2 mb-1">robots.txt</div>
                    <div class="text-muted fs-7">Exclusion rules served at <code>/robots.txt</code></div>
                </div>
                <div class="col-xl-9 fv-row">
                    <textarea
                        id="robots-txt-input"
                        name="app[robots_txt]"
                        class="form-control font-monospace fs-7"
                        rows="14"
                        placeholder="User-agent: *&#10;Disallow: /...">{{ old('app.robots_txt', $savedRobotsTxt) }}</textarea>
                    @error('app.robots_txt')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-10 mb-5">
                <div>
                    <h3 class="card-title align-items-start flex-column mb-0">
                        <span class="card-label fw-bold fs-3 mb-1">AI Search / GEO (llms.txt &amp; llms-full.txt)</span>
                    </h3>
                    <div class="text-muted fs-7">
                        Structured Markdown files served at <code>/llms.txt</code> and <code>/llms-full.txt</code> for AI crawlers (ChatGPT, Claude, Perplexity, Gemini).
                    </div>
                </div>
                <button id="generate-llms-button" type="button" class="btn btn-light-primary btn-sm" style="display:none">
                    Sync / Generate Default from CMS
                </button>
            </div>

            <div class="row mb-8">
                <div class="col-xl-3">
                    <div class="fs-6 fw-semibold mt-2 mb-1">llms.txt</div>
                    <div class="text-muted fs-7">Concise index &amp; key links served at <code>/llms.txt</code></div>
                </div>
                <div class="col-xl-9 fv-row">
                    <textarea
                        id="llms-txt-input"
                        name="app[llms_txt]"
                        class="form-control font-monospace fs-7"
                        rows="12"
                        placeholder="# Site Title&#10;&#10;> Summary description...">{{ old('app.llms_txt', $savedLlmsTxt) }}</textarea>
                    @error('app.llms_txt')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-8">
                <div class="col-xl-3">
                    <div class="fs-6 fw-semibold mt-2 mb-1">llms-full.txt</div>
                    <div class="text-muted fs-7">Comprehensive documentation served at <code>/llms-full.txt</code></div>
                </div>
                <div class="col-xl-9 fv-row">
                    <textarea
                        id="llms-full-txt-input"
                        name="app[llms_full_txt]"
                        class="form-control font-monospace fs-7"
                        rows="16"
                        placeholder="# Site Title — Full Context Documentation (llms-full.txt)...">{{ old('app.llms_full_txt', $savedLlmsFullTxt) }}</textarea>
                    @error('app.llms_full_txt')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button id="edit-button" type="button" class="btn btn-primary">Edit</button>
            <button id="save-button" type="submit" class="btn btn-primary" style="display:none">Save Changes</button>
        </div>
    </x-forms.post>
    @push('scripts')
    <script>
        $(document).ready(function() {
            // Set initial disabled attribute value
            $('#general-form input[name^="app"], #general-form textarea[name^="app"]').prop('disabled', true);
            $('#general-form input[type="checkbox"]').prop('disabled', true);

            // Enable form fields on edit button click
            $('#edit-button').click(function() {
                $('#general-form input[name^="app"], #general-form textarea[name^="app"]').prop('disabled', false);
                $('#general-form input[type="checkbox"]').prop('disabled', false);
                $('#edit-button').hide();
                $('#save-button').show();
                $('#generate-robots-button').show();
                $('#generate-llms-button').show();
            });

            // Reset default robots.txt
            $('#generate-robots-button').click(function() {
                var $btn = $(this);
                var originalText = $btn.text();
                $btn.prop('disabled', true).text('Loading...');

                $.ajax({
                    url: "{{ route('admin.general.generate-robots') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response && response.data && response.data.robots_txt) {
                            $('#robots-txt-input').val(response.data.robots_txt);
                        }
                    },
                    error: function() {
                        alert('Failed to generate default robots.txt content.');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text(originalText);
                    }
                });
            });

            // Auto-generate default llms.txt & llms-full.txt from CMS content
            $('#generate-llms-button').click(function() {
                var $btn = $(this);
                var originalText = $btn.text();
                $btn.prop('disabled', true).text('Generating...');

                $.ajax({
                    url: "{{ route('admin.general.generate-llms') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response && response.data) {
                            $('#llms-txt-input').val(response.data.llms_txt || '');
                            $('#llms-full-txt-input').val(response.data.llms_full_txt || '');
                        }
                    },
                    error: function() {
                        alert('Failed to generate default llms.txt content from CMS.');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text(originalText);
                    }
                });
            });
        });
    </script>
@endpush

@endsection



