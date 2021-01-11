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

        @livewireStyles

        <style>
            .images {
                margin: 5%;
                text-align: center;
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
                        <button type="button" onclick="location.href='/'">
                            <img id="/storage/loading.jpeg" src="/storage/loading.jpeg" width="800px" >
                        </button>
                    </div>
                    <div>
                        <span><?php echo $manga["name"]?></span>
                        <span>@<?php echo $manga["pen_name"]?></span>
                        <span>作品 : <?php echo $manga["title"]?></span>
                    </div>
                    <div>
                        <textarea name="comment" rows="1" cols="100"></textarea>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
