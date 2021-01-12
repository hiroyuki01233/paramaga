<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal-default-theme.min.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal.min.js"></script>


        @livewireStyles
        <script src="{{ asset('/js/play.js') }}"></script>
        <script>
            const penName = @json($manga["pen_name"]);
            const url = @json($manga["url"]);
        </script>
        <style>
            .images {
                margin: 5%;
                text-align: center;
            }
            .detail {
                text-align: center;
            }
            .add_comment {
                margin: 1%;
            }
        </style>

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
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
                        <span><?php echo $manga["name"]?></span>
                        <span>@<?php echo $manga["pen_name"]?></span>
                        <span>作品 : <?php echo $manga["title"]?></span>
                        <div class="add_comment">
                            <textarea name="comment" rows="1" cols="100"></textarea>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
