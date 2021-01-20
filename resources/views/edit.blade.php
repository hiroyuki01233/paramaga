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

        @livewireStyles

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    <script> 
            const id = {{$id}};
            const HOST_NAME = "{{config('const.HOST_NAME')}}";
    </script>

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal-default-theme.min.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal.min.js"></script>
        <link rel="shortcut icon" href="{{ asset('storage/icon.ico') }}">

        <style>
            canvas {
                background-color: white;
                border: solid 5px #808080;
            } 

            #change_canvas_buttons{
                display: inline-block;
            }
            .manga_btn {
                border: 2px solid #4072B3;
                border-radius: 5px;
                width:100px;
                height:50px;
            }
            .change_buttons {
                padding-bottom: 1%;
                padding-top: 1%;
                padding-left: 5%;
                padding-right: 5%;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-dropdown')
            <main align="center">
                <br>
                <div>
                    <input type="button" style="background-color: white" value="white" onclick="buttonClick(this.value)">
                    <input type="button" style="background-color: black" value="black" onclick="buttonClick(this.value)">
                    <input type="button" style="background-color: red" value="red" onclick="buttonClick(this.value)">
                    <input type="button" style="background-color: blue" value="blue" onclick="buttonClick(this.value)">
                    <input type="button" style="background-color: yellow" value="yellow" onclick="buttonClick(this.value)">
                    <input type="range" id="volume" value="0" min="0" max="1000">
                    <input type="button" style="background-color: #808080" value="でかい" onclick="bigLine()">
                    <input type="button" style="" value="前のページをコピー" onclick="copyPage()">

                    <a href="#modal" onclick="saveNow()">保存</a>
                    <div class="remodal" data-remodal-id="modal">
                        <input type="text" id="title" name="title" required minlength="4" maxlength="100" size="50" placeholder="タイトルを入力">
                        <button data-remodal-action="close" class="remodal-close"></button>
                        <p id="test_text">保存しますか？</p>
                        <input type="button" value="保存" onclick="saveImages()">
                    </div>
                </div>
                <br>
                <canvas id="canvas" width="1280" height="720" style="background-color:white;"></canvas> <br>
                <div class="change_buttons">
                    <div id="change_canvas_buttons">
                        <input class="manga_btn" type="button" value="1" onclick="changeCanvas(this.value,window.location.hash.slice(1))">
                    </div><br>
                    <input class="manga_btn" style="margin-top: 1%" id="plus_button" type="button" value="+" onclick="canvasPlus(window.location.hash.slice(1))">
                </div>
            </main>
        </div>
        <script src="{{ asset('/js/paintForEdit.js') }}"></script>
    </body>
</html>
