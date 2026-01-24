@extends('backend.layouts.app')

@section('title', 'Home Banner Configuration')

@section('content')
    @if (session('flash_success'))
        <div class="alert alert-success">
            {{ session('flash_success') }}
        </div>
    @endif
    
    @if (session('flash_danger'))
        <div class="alert alert-danger">
            {{ session('flash_danger') }}
        </div>
    @endif

    <form class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework" 
          method="post" 
          action="{{ route('admin.home-banners.update') }}" 
          enctype="multipart/form-data">
        @csrf
        
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="d-flex flex-column gap-7 gap-lg-10">
                <div class="row">
                    <div class="col-md-12 pb-4">
                        <div class="card card-flush p-3">
                            <div class="card-header">
                                <h3 class="card-title p-5">Home Page Banner Configuration</h3>
                            </div>
                            <div class="card-body p-5">
                                <p class="text-muted mb-5">
                                    Configure banner groups for different positions on the home page. 
                                    Available positions: <strong>Navbar</strong>, <strong>Journey Growth</strong>, and <strong>Financial Reports</strong>.
                                </p>

                                <!-- Language Tabs -->
                                <ul class="nav nav-tabs" id="languageTab" role="tablist">
                                    @foreach ($languages as $lang)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link @if ($loop->first) active @endif"
                                                id="{{ $lang['value'] }}-tab" 
                                                data-bs-toggle="tab"
                                                data-bs-target="#{{ $lang['value'] }}" 
                                                type="button" 
                                                role="tab"
                                                aria-controls="{{ $lang['value'] }}" 
                                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                {{ strtoupper($lang['name']) }}
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content mt-5" id="languageTabContent">
                                    @foreach ($languages as $lang)
                                        @php
                                            $lang_code = $lang['value'];
                                        @endphp
                                        <div class="tab-pane fade @if ($loop->first) show active @endif"
                                            id="{{ $lang_code }}" 
                                            role="tabpanel"
                                            aria-labelledby="{{ $lang_code }}-tab">
                                            
                                            <div class="d-flex align-items-start">
                                                <!-- Position Pills -->
                                                <div class="nav flex-column nav-pills me-3" 
                                                     id="v-pills-tab-{{ $lang_code }}" 
                                                     role="tablist" 
                                                     aria-orientation="vertical">
                                                    @foreach($positions as $position)
                                                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                                                id="v-pills-{{ $position }}-{{ $lang_code }}-tab" 
                                                                data-bs-toggle="pill" 
                                                                data-bs-target="#v-pills-{{ $position }}-{{ $lang_code }}" 
                                                                type="button" 
                                                                role="tab" 
                                                                aria-controls="v-pills-{{ $position }}-{{ $lang_code }}" 
                                                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                            {{ ucwords(str_replace('-', ' ', $position)) }}
                                                        </button>
                                                    @endforeach
                                                </div>

                                                <!-- Position Content -->
                                                <div class="tab-content flex-grow-1" id="v-pills-tabContent-{{ $lang_code }}">
                                                    @foreach($positions as $position)
                                                        @php
                                                            $activeBanner = $homePage->activeBanners
                                                                ->where('location', $position)
                                                                ->where('language', $lang_code)
                                                                ->first();
                                                        @endphp
                                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }} p-4" 
                                                             id="v-pills-{{ $position }}-{{ $lang_code }}" 
                                                             role="tabpanel" 
                                                             aria-labelledby="v-pills-{{ $position }}-{{ $lang_code }}-tab">
                                                            
                                                            <div class="mb-5">
                                                                <label class="form-label">Banner Group</label>
                                                                <select name="banner_active[{{ $lang_code }}][{{ $position }}][group_id]" 
                                                                        class="form-select form-select-solid">
                                                                    <option value="">Select Banner Group</option>
                                                                    @foreach($bannerGroups as $group)
                                                                        <option value="{{ $group->id }}" 
                                                                                {{ $activeBanner && $activeBanner->banner_group_id == $group->id ? 'selected' : '' }}>
                                                                            {{ $group->title }} ({{ $group->items_count }} banners)
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6 mb-5">
                                                                    <label class="form-label">Start Date</label>
                                                                    <input type="datetime-local" 
                                                                           name="banner_active[{{ $lang_code }}][{{ $position }}][start_date]" 
                                                                           class="form-control form-control-solid"
                                                                           value="{{ $activeBanner && $activeBanner->start_date ? $activeBanner->start_date->format('Y-m-d\TH:i') : '' }}">
                                                                </div>
                                                                <div class="col-md-6 mb-5">
                                                                    <label class="form-label">End Date</label>
                                                                    <input type="datetime-local" 
                                                                           name="banner_active[{{ $lang_code }}][{{ $position }}][end_date]" 
                                                                           class="form-control form-control-solid"
                                                                           value="{{ $activeBanner && $activeBanner->end_date ? $activeBanner->end_date->format('Y-m-d\TH:i') : '' }}">
                                                                </div>
                                                            </div>

                                                            <div class="mt-3">
                                                                <button type="button" 
                                                                        style="font-size: 10px!important; padding: 0px 5px!important;" 
                                                                        class="btn btn-sm btn-light-danger clear-banner-btn">
                                                                    Clear Banner Config in this Position
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-5">
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        Save Configuration
                                    </button>
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    $(document).on('click', '.clear-banner-btn', function() {
        var container = $(this).closest('.tab-pane');
        container.find('select').val('').trigger('change');
        container.find('input[type="datetime-local"]').val('');
    });
</script>
@endpush
