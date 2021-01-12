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

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
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
            
            var deleteId;
            function changeDeleteId(id){
                deleteId = id;
            }


            $(document).ready( function(){
                setCSRF();

                const mangaAll = @json($mangaAll);
                
                $.ajax({
                    url: 'http://localhost:8000/v1/image/myMangaThumbnaiAll',
                    type: 'GET',
                    dataType: 'json',
                    timeout: 5000,
                })
                .done(function(result,textStatus,jqXHR) {
                    $.each(mangaAll,function(index,value){
                        document.getElementById("manga_" + value["number_of_works"]).src = result[value["number_of_works"]];
                    })
                })
                .fail(function(data1,textStatus,jqXHR) {
                    var data2 = JSON.stringify(data1);
                    console.log(data2);
                });
            });
            

            function deleteManga(){
                setCSRF();

                $('#test_text').text('通信中...');

                // Ajax通信を開始
                $.ajax({
                url: 'http://localhost:8000/v1/image/'+deleteId,
                type: 'DELETE',
                dataType: 'json',
                timeout: 5000,
                })
                .done(function(data1,textStatus,jqXHR) {
                    var data2 = JSON.stringify(data1);
                    $('#test_text').text("成功");
                    window.location.href = '/manga';
                })
                .fail(function(data1,textStatus,jqXHR) {
                    var data2 = JSON.stringify(data1);
                    $('#test_text').text(data2);
                });
            };

            function changePublished(id){
                setCSRF();
                
                // Ajax通信を開始
                $.ajax({
                url: 'http://localhost:8000/v1/image/'+id,
                type: 'PATCH',
                dataType: 'json',
                data: {
                    "thisPublishedFlag" : "1",
                },
                timeout: 5000,
                })
                .done(function(data1,textStatus,jqXHR) {
                    var data2 = JSON.stringify(data1);
                    window.location.href = '/manga';
                })
                .fail(function(data1,textStatus,jqXHR) {
                    var data2 = JSON.stringify(data1);
                    console.log("失敗"+data2);
                });

            };
        </script>

        <style>
            canvas {
                background-color: white;
                border: solid 5px #808080;
            } 
            .inline-block_test {
                display: inline-block;
                border: 2px solid #4072B3;
                border-radius: 5px;
                margin: 1%;
            }
            .plus_button{
                padding: 50px;
                border-radius: 100px;
            }
            .main-div {
                margin-top: 5%;
                margin-left: 10%;
                margin-right: 10%;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-dropdown')
            <main align="center">
                <div class="main-div">
                    @foreach ($mangaAll as $manga)
                        <div class="inline-block_test">
                            <a href="edit/<?php echo $manga['number_of_works']?>">編集</a>
                            <a href="#modal" onclick="changeDeleteId(<?php echo $manga['number_of_works']?>)">削除</a>
                            <div class="remodal" data-remodal-id="modal">
                                <button data-remodal-action="close" class="remodal-close"></button>
                                <p id="test_text">削除しますか？</p>
                                <input type="button" value="削除" onclick="deleteManga()">
                            </div>
                            @if($manga["published_flag"])
                                <button type="button" onclick="location.href='view/<?php echo $manga['pen_name']?>?m=<?php echo $manga['url']?>'">
                                    <img id="manga_<?php echo $manga["number_of_works"]; ?>" src="Storage/loading.jpeg" width="300px">
                                </button>
                            @else
                                <button type="button" onclick="location.href='preview/<?php echo $manga['pen_name']?>?m=<?php echo $manga['url']?>'">
                                    <img id="manga_<?php echo $manga["number_of_works"]; ?>" src="Storage/loading.jpeg" width="300px">
                                </button>
                            @endif
                            <button type="button" onclick="changePublished(<?php echo $manga['number_of_works']?>)">
                                @if($manga["published_flag"])
                                    <p>非公開に</p>
                                @else
                                    <p>公開する</p>
                                @endif
                            </button>
                            <p>作品 : <?php echo $manga["title"]?></p>
                        </div>
                    @endforeach
                    <br>
                    <button type="button" onclick="location.href='/create'" width="300px">
                        <div class="inline-block_test plus_button">
                            +
                        </div>
                    </button>
                </div>
            </main>
        </div>

    </body>
</html>
