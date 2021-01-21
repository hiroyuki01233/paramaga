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

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal-default-theme.min.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal.min.js"></script>
        <link rel="shortcut icon" href="{{ asset('storage/icon.ico') }}">

        @livewireStyles

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>

        <style>
            .user_detail {
                padding-top: 5%;
                text-align: center;
            }
            .allMargin {
                margin: 0.5%;
            }
            .display {
                display: inline-block;
            }
            .counts {
                margin-left: 8%;
            }
            .manga_all {
                padding-top: 4%;
                padding-left: 10%;
                padding-right: 10%;
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
    </head>
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

            const mangaAll = @json($mangaAll);                

            $.each(mangaAll, function(index, value){
                $.ajax({
                    url: '{{config('const.HOST_NAME')}}/v1/image/thumbnailPub',
                    type: 'GET',
                    dataType: 'json',
                    data: { 
                        "url": value["url"],
                    },
                    timeout: 5000,
                })
                .done(function(data1,textStatus,jqXHR) {
                    var data2 = JSON.stringify(data1);
                    data2 = data2.slice(1);
                    data2 = data2.slice(0, -1);
                    document.getElementById("image_" + value["pen_name"] + "_" + value["url"]).src = data2
                })
                .fail(function(data1,textStatus,jqXHR) {
                    var data2 = JSON.stringify(data1);
                    console.log(data2);
                });
            })
        };    
    </script>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-dropdown')
            <main>
                <div class="user_detail">
                    <div class="display">
                        @if ( $userInfo["profile_photo_path"] )
                            <img class="h-40 w-40 rounded-full object-cover" src="/storage/{{ $userInfo["profile_photo_path"] }}" alt="{{$userInfo["pen_name"]}}" />
                        @else
                            <img class="h-40 w-40 rounded-full object-cover" src="/storage/UserIcon.png" alt="{{$userInfo["pen_name"]}}" />
                        @endif
                        <p class="allMargin" style="font-size: 25px">{{ $userInfo["name"] }}</p>
                        <p class="allMargin" style="color: gray">{{ "@" }}{{ $userInfo["pen_name"] }}</p>
                    </div>
                    <div class="display counts">
                        <p>コメント数 : {{ $commentCount }}</p>
                        <p>いいね数 : {{ $likeCount }}</p>
                    </div>
                </div>
                <div class="manga_all">
                    @foreach ($mangaAll as $manga)
                        <div class="manga">
                            <button type="button" onclick="location.href='view/{{ $manga['pen_name'] }}?m= {{ $manga['url'] }}'">
                                <img id="{{ "image_".$manga['pen_name']."_".$manga['url'] }}" src="/storage/loading.jpeg" width="500px" >
                            </button>
                            <br>
                            <span>作品 : {{ $manga["title"] }}</span>
                        </div>
                    @endforeach
                </div>
            </main>
            @livewire('footer')
        </div>
    </body>
</html>
