<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ "パラパラ漫画を作成して共有！Paramaga / パラマガ" }}</title>
        <meta name=”description” content=”パラマガでパラパラ漫画を作ろう。絵が得意な方！パラパラ漫画を試しに作ってみたい方！ウェブサイト上で手軽にパラパラ漫画を作成してたくさんの人共有しましょう！あなたの素敵な作品をみたたくさんの人からいいねやコメントがもらえるかも！“>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal-default-theme.min.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal.min.js"></script>

        <link rel="shortcut icon" href="{{ asset('storage/icon.ico') }}">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @livewireStyles

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>

        <style>
            .main_div {
                text-align: center;
            }
            .manga {
                display: inline-block;
                border: 2px solid #4072B3;
                border-radius: 5px;
                margin: 1%;
                text-align: center;
            }

        </style>

        <script>
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

            window.onload = function () {
                setCSRF();

                const userManga = @json($userManga);                

                $.ajax({
                        url: '{{config('const.HOST_NAME')}}/v1/image/thumbnailPub',
                        type: 'GET',
                        dataType: 'json',
                        data: { 
                            "url": userManga,
                        },
                        timeout: 5000,
                    })
                    .done(function(data1,textStatus,jqXHR) {
                        $.each(data1, function(index, value){
                            document.getElementById("image_" + index).src = value["image"];
                        })
                    })
                    .fail(function(data1,textStatus,jqXHR) {
                        var data2 = JSON.stringify(data1);
                        console.log(data2);
                    });
            };    
        </script>

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-dropdown')
            <main>
                @foreach ($userManga as $manga)
                <div class="main_div">
                    <div class="manga">
                        <button type="button" onclick="location.href='view/{{ $manga['pen_name'] }}?m={{ $manga['url'] }}'">
                            <img id="{{ "image_".$manga['url'] }}" src="/storage/loading.jpeg" width="800px" >
                        </button>
                        <br>
                        @if ( $manga["profile_photo_path"] )
                            <a href="/profile?u={{$manga["pen_name"]}}"><img class="h-10 w-10 rounded-full object-cover" src="/storage/{{ $manga["profile_photo_path"] }}" alt="{{$manga["pen_name"]}}" /></a>
                        @else
                            <a href="/profile?u={{$manga["pen_name"]}}"><img class="h-10 w-10 rounded-full object-cover" src="/storage/UserIcon.png" alt="{{$manga["pen_name"]}}" /></a>
                        @endif
                        <span style="font-weight: bold">{{ $manga["name"] }}</span>
                        <span>{{ "@" }}{{ $manga["pen_name"] }}</span>
                        <span>作品 : {{ $manga["title"] }}</span>
                    </div>
                </div>
                @endforeach
            </main>
            @livewire('footer')
        </div>
    </body>
</html>
