<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'paramaga') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal-default-theme.min.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal.min.js"></script>

        @livewireStyles

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
        <script>
            var images;
            const penName = @json($manga["pen_name"]);
            const url = @json($manga["url"]);

            function playManga(){
                var count = 1;
                var playScreen = function(){
                    $('#playScreen').children('img').attr('src', images[count]);
                    var id = setTimeout(playScreen, 100);
                    if(typeof images[count + 1] == 'undefined'){
                        clearTimeout(id);
                    }
                    count++;
                }
                playScreen();
            }

            function setCSRF(){
                $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
                    if (!options.crossDomain) {
                        const token = $('meta[name="csrf-token"]').attr('content');
                        if (token) {
                            return jqXHR.setRequestHeader('X-CSRF-Token', token);
                        }
                    }
                });
            }

            $(document).ready( function(){

                setCSRF();
                
                $.ajax({
                    url: 'https://paramaga.com/v1/image/previewManga',
                    type: 'GET',
                    data: {
                        "url" : url
                    },
                    dataType: 'json',
                    timeout: 5000,
                })
                .done(function(result,textStatus,jqXHR) {    
                    images = result;
                })
                .fail(function(data1,textStatus,jqXHR) {
                    var data2 = JSON.stringify(data1);
                    console.log(data2);
                });

            });
        </script>

        <style>
            .images {
                margin: 5%;
                text-align: center;
            }
            .detail {
                text-align: center;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-dropdown')
            <main>
                <div>
                    <div class="images">
                        <button type="button" id="playScreen" onclick="playManga()">
                            <img src="/storage/play.jpeg" width="1100px" >
                        </button>
                    </div>
                    <div class="detail">
                        <span> {{ $manga["name"] }}</span>
                        <span>@ {{ $manga["pen_name"] }}</span>
                        <span>作品 : {{ $manga["title"] }}</span>
                    </div>
                </div>
            </main>
        </div>

    </body>
</html>
