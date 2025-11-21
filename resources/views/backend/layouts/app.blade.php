<!DOCTYPE html>
<html lang="{{ htmlLang() }}" data-theme="light">
<head>
    <meta charset="utf-8"/>
    <title>@yield('title') &lsaquo; {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('icon.png') }}"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"/>
    <link rel="preload" href="{{ asset('plugins/global/plugins.bundle.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'" type="text/css">
    <link rel="preload" href="{{ asset('plugins/global/plugins-custom.bundle.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'" type="text/css">
    <link href="{{ mix('css/style.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ mix('css/backend.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="preload" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" as="style" type="text/css" />
    <link rel="preload" href="{{ asset('anticms/css/custom.css')}}?id=63a17e68f3a561a8734fec8ac9cb523b" as="style" onload="this.onload=null;this.rel='stylesheet'" type="text/css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <livewire:styles />
    @stack('headers')
</head>
<body id="kt_body" class="header-tablet-and-mobile-fixed aside-enabled">
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            @include('backend.includes.sidebar')
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                @include('backend.includes.header')
                <div class="content d-flex flex-column flex-column-fluid mx-4" id="kt_content">
                    <div class="post d-flex flex-column-fluid w-100 " id="kt_post">
                        <div id="kt_content_container" class="w-100 ">

                            @include('includes.partials.read-only')
                            @include('includes.partials.logged-in-as')
                            {{-- @include('includes.partials.announcements') --}}
                            @include('includes.partials.messages')

                            @include('includes.partials.toolbar')
                            @stack('toast')

                            @yield('content')
                        </div>
                    </div>
                </div>

                @include('backend.includes.footer')
            </div>
        </div>
    </div>

    <script src="{{ mix('plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ mix('js/scripts.bundle.js') }}"></script>
    <script src="{{ mix('js/custom/widgets.js') }}"></script>

    <script>
        var token = $('meta[name="csrf-token"]').attr('content');
    </script>

    <livewire:scripts />
    <script src="//cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ asset('js/backend.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        var token = $('meta[name="csrf-token"]').attr('content');
        var thememode = "light";
        var storedtheme = localStorage.getItem("data-theme");
        var defaultthememode;

        if (document.documentElement.hasAttribute("data-theme-mode")) {
            defaultThemeMode = document.documentElement.getAttribute("data-theme-mode");
        }

        if (storedtheme !== null) {
          thememode = storedtheme;
        } else if (defaultthememode === "system") {
          thememode = window.matchmedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
        }

        document.documentElement.setAttribute("data-theme", thememode);
    </script>
    @stack('scripts')
</body>
</html>

