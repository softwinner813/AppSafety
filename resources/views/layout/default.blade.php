<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {{ Metronic::printAttrs('html') }} {{ Metronic::printClasses('html') }}>
    <head>
        <meta charset="utf-8"/>

        {{-- Title Section --}}
        <title>{{ config('app.name') }} | @yield('title', $page_title ?? '')</title>

        {{-- Meta Data --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="@yield('page_description', $page_description ?? '')"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
        
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- Favicon --}}
        <!-- <link rel="shortcut icon" href="{{ asset('media/logos/favicon.ico') }}" /> -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/logos/apple-touch-icon.png') }}/">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('media/logos/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('media/logos/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('media/logos/site.webmanifest') }}">
        <link rel="mask-icon" href="{{ asset('media/logos/safari-pinned-tab.svg') }}" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        {{-- Fonts --}}
        {{ Metronic::getGoogleFontsInclude() }}

        {{-- Global Theme Styles (used by all pages) --}}
        @foreach(config('layout.resources.css') as $style)
            <link href="{{ config('layout.self.rtl') ? asset(Metronic::rtlCssPath($style)) : asset($style) }}" rel="stylesheet" type="text/css"/>
        @endforeach


        {{-- Includable CSS --}}
        @yield('styles')
    </head>

    <body id="kt_body" style="background-image: url(/media/bg/bg-10.jpg); overflow: hidden;" class="quick-panel-right demo-panel-right offcanvas-right header-fixed subheader-enabled page-loading" >
        <input type="hidden" value="{{csrf_token()}}" id="csrf_token">

        @if (config('layout.page-loader.type') != '')
            @include('layout.partials._page-loader')
        @endif

        @include('layout.base._layout')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script>
            var HOST_URL = "{{ route('quick-search') }}";
            var CSRF_TOKEN = "{{csrf_token()}}"
        </script>

        {{-- Global Config (global config for global JS scripts) --}}
        <script>
            var KTAppSettings = {!! json_encode(config('layout.js'), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) !!};
        </script>

        {{-- Global Theme JS Bundle (used by all pages)  --}}
        @foreach(config('layout.resources.js') as $script)
            <script src="{{ asset($script) }}" type="text/javascript"></script>
        @endforeach
        <script src="/js/pages/features/miscellaneous/toastr.js"></script>
        {{-- Includable JS --}}
        @yield('scripts')

    </body>

    <script>
        toastr.options = {
          "closeButton": true,
          "debug": false,
          "newestOnTop": false,
          "progressBar": true,
          "positionClass": "toast-top-right",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        };
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    </script>
</html>

