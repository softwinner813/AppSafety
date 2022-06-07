@if(config('layout.self.layout') == 'blank')
    <div class="d-flex flex-column flex-root">
        @yield('content')
    </div>
@else

    @include('layout.base._header-mobile')

    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                @include('layout.base._alert')

                @include('layout.base._header')

                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    @if(!isset($noneSubheader))
                        @include('layout.partials.subheader._subheader_new')
                    @endif

                    @include('layout.base._content')
                </div>

                @include('layout.base._footer')
            </div>
        </div>
    </div>

@endif

@if (config('layout.self.layout') != 'blank')


    @include('layout.partials.extras._scrolltop')

@endif
