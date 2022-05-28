<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        #logo {
            /* This image is 687 wide by 1024 tall, similar to your aspect ratio */
            background-image: url('http://within.id8tr.com/logo.jpg');
            
            /* make a square container */
            width: 50px;
            height: 50px;

            /* fill the container, preserving aspect ratio, and cropping to fit */
            background-size: cover;

            /* center the image vertically and horizontally */
            background-position: top center;

            /* round the edges to a circle with border radius 1/2 container size */
            border-radius: 50%;
        }

        #email_icon {
            /*background-image: url('http://localhost/icon_email.png');*/
            padding: 5px;
            background-color: green;
            /* make a square container */
            width: 30px;
            height: 30px;

            /* fill the container, preserving aspect ratio, and cropping to fit */
            background-size: contain;

            /* center the image vertically and horizontally */
            background-position: top center;

            /* round the edges to a circle with border radius 1/2 container size */
            border-radius: 50%;
        }

    </style>

</head>
<body style="background-color: #101010f0;font-family: arial;">
    <center class="container" style="padding-top: 20px;padding-bottom: 20px;">
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}" style="text-decoration: none;">
                        <div id="logo" class="col-md-4">
                            <!-- <img width="150px;" height="150px;" id="avatar" src="/logo.jpg"></img> -->
                        </div>
                        <div class="col-md-8" style="color: white;font-size: 30px;font-weight: bold;">Within</div>
                    </a>
                    
                </div>
            </nav>
            <br>
            <main class="py-4">
                @yield('content')
            </main>
        </div>    
    </center>
    
</body>
</html>
