<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="utf-8" />
  <title>@yield('title') &lsaquo; {{ config('app.name') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{ asset('icon.png') }}" />
  <meta name="csrf-token" content="snWnqMsESXMuMoRyY91ztPnBllVOz0fbLCm4jjO5">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
  <link rel="preload" href="{{ asset('plugins/global/plugins.bundle.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'" type="text/css"><noscript>
    <link rel="stylesheet" href="http://127.0.0.1:8000/demo1/plugins/global/plugins.bundle.css">
  </noscript>
  <link rel="preload" href="{{ asset('plugins/global/plugins-custom.bundle.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'" type="text/css"><noscript>
    <link rel="stylesheet" href="http://127.0.0.1:8000/demo1/plugins/global/plugins-custom.bundle.css">
  </noscript>
  <link href="{{ asset('css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
  <!-- overwite -->
  <link rel="preload" href="{{ asset('anticms/css/custom.css')}}" as="style" onload="this.onload=null;this.rel='stylesheet'" type="text/css">
  <!-- end -->
  <livewire:styles />
  @stack('headers')
</head>
<body id="kt_body" class="header-tablet-and-mobile-fixed aside-enabled">
  <div class="auth-container">
    @yield('content')
    @stack('footer')
  </div>
  <script src="{{ asset('plugins/global/plugins.bundle.js') }}"></script>
  <script src="{{ asset('js/scripts.bundle.js') }}"></script>
  <script src="{{ asset('js/custom/widgets.js') }}"></script>
  <script>
    var token = $('meta[name="csrf-token"]').attr('content');
  </script>
  <script src="{{ mix('js/vendor.js') }}"></script>
  <livewire:scripts />


  <script>
    var themeMode = "light";
    var storedTheme = localStorage.getItem("data-theme");
    var defaultThemeMode;

    if (document.documentElement.hasAttribute("data-theme-mode")) {
      defaultThemeMode = document.documentElement.getAttribute("data-theme-mode");
    }

    if (storedTheme !== null) {
      themeMode = storedTheme;
    } else if (defaultThemeMode === "system") {
      themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
    }

    document.documentElement.setAttribute("data-theme", themeMode);
  </script>
  @stack('scripts')
</body>

</html>