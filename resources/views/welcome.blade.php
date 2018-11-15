<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>MuSync</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
<<<<<<< HEAD
        <link href="{{ asset('css/welcome-page.css') }}" rel="stylesheet" type="text/css">

        <!-- Javascript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script type="text/javascript">
            
            (function($) {
                setTimeout(function() {
                    $('.title').removeClass('hidden');
                }, 500);
            })(jQuery);
            
        </script>
=======
        <!-- <link href="{{ asset('css/welcome.css') }}" rel="stylesheet" type=t"ext/css"> -->
        <link href="{{ asset('css/welcome-page.css') }}" rel="stylesheet" type="text/css">

>>>>>>> Removed home.css and room.css files and replaced with central app.scss
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <ul class="title hidden neon-heading first">
                    <li><span class="flickering-3">M</span></li>
                    <li>U</li>
                    <li><span class="flickering-1">S</span></li>
                    <li><span class="flickering-3">Y</span></li>                    
                    <li>N</li>
                    <li><span class="flickering-2">C</span></li>
                </ul>
            </div>
            <div class="links">
                @auth
                    <a href="{{ url('/home') }}">Home</a>
                @else
                    <a href="/login/spotify"  class="neon-heading second">Login with Spotify</a>
                @endauth
            </div>
        </div>
    </body>
</html>
