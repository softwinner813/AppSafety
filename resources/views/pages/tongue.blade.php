
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8"/>

        
        <title>Tongue Analyze</title>

        {{-- Meta Data --}}
        <meta name="description" content="@yield('page_description', $page_description ?? '')"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

        {{-- Favicon --}}
        <link rel="shortcut icon" href="{{ asset('media/logos/favicon.ico') }}" />

        {{-- Fonts --}}
        {{ Metronic::getGoogleFontsInclude() }}

        {{-- Global Theme Styles (used by all pages) --}}
        @foreach(config('layout.resources.css') as $style)
            <link href="{{ config('layout.self.rtl') ? asset(Metronic::rtlCssPath($style)) : asset($style) }}" rel="stylesheet" type="text/css"/>
        @endforeach

        {{-- Layout Themes (used by all pages) --}}
        @foreach (Metronic::initThemes() as $theme)
            <link href="{{ config('layout.self.rtl') ? asset(Metronic::rtlCssPath($theme)) : asset($theme) }}" rel="stylesheet" type="text/css"/>
        @endforeach

        <style type="text/css">
            .sample-color {
                height: 80px!important;
                width: 100%;
            }
        </style>
        
    </head>

    <body {{ Metronic::printAttrs('body') }} {{ Metronic::printClasses('body') }}>


        <div class="container" style="margin-top: 50px;">
            <h1 class="text-center">Tongue Analysis</h1>
            <div class="row" >
                <div class="col-md-2">
                    <ul class="list-group">
                      <li class="list-group-item">
                          <div class="sample-color" style="background-color: #CD5D60;">
                              <center>Red : #CD5D60</center>
                          </div>
                      </li>
                      <li class="list-group-item">     
                        <div class="sample-color" style="background-color: rgb(174,58,70);">
                              <center>Dark red:  #AE3A46</center>
                          </div>
                    </li>
                      <li class="list-group-item">    
                        <div class="sample-color" style="background-color: rgb(201,156,161);">
                                                  <center>light purple:  #C99CA1</center>
                                              </div>
                                           </li>
                                          <li class="list-group-item">     
                        <div class="sample-color" style="background-color: #E3AA8D;">
                                                  <center>white :  #E3AA8D</center>
                                              </div>
                                           </li>
                                          <li class="list-group-item">    
                        <div class="sample-color" style="background-color: rgb(214,146,90);">
                                                  <center>yellow :  #D6925A</center>
                                              </div>
                       </li>

                        <li class="list-group-item">    
                            <div class="sample-color" style="background-color: rgb(243,168,180);">
                              <center> pink #F3A8B4</center>
                          </div>
                       </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <center>
                        <img src="{{$imagePath}}" height="200" width="auto" >
                    </center>
                </div>
                <div class="col-md-4">
                     <li class="list-group-item">    
                        <div class="sample-color" style="background-color: rgb({{$selectedColor[0]}},{{$selectedColor[1]}},{{$selectedColor[2]}});">
                          <center> Result Color ==>  {{$title}} </center>
                      </div>
                   </li>
                </div>
            </div>
        </div>
        

        <script>var HOST_URL = "{{ route('quick-search') }}";</script>

        {{-- Global Config (global config for global JS scripts) --}}
        <script>
            var KTAppSettings = {!! json_encode(config('layout.js'), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) !!};
        </script>

        {{-- Global Theme JS Bundle (used by all pages)  --}}
        @foreach(config('layout.resources.js') as $script)
            <script src="{{ asset($script) }}" type="text/javascript"></script>
        @endforeach


    </body>
</html>

