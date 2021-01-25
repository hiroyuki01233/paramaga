<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ "パラパラ漫画を作成して共有！Paramaga / パラマガ" }}</title>
        <meta name="description" content="パラマガでパラパラ漫画を作ろう。絵が得意な方！パラパラ漫画を試しに作ってみたい方！ウェブサイト上で手軽にパラパラ漫画を作成してたくさんの人共有しましょう！あなたの素敵な作品をみたたくさんの人からいいねやコメントがもらえるかも！">

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
            .main {
                padding-top: 5%;
                padding-left: 20%;
                padding-right: 20%;
            }
            .title {
                border-bottom: 1px solid;
            }
            .margin {
                margin-top: 5%;
            }
            .detailed {
                margin-left: 4%;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-dropdown')
            <main>
                <div class="main">
                    <h1 class="title">
                        パラマガでのパラパラ漫画の作り方！
                    </h1>
                    <h2 class="margin">まずは自分の漫画リストにいきましょう</h2>
                    <img class="margin" src="/storage/article_1_1.png" alt="リストボタンの場所">
                    <h3>この画像の「リスト」ボタンを押してね！↑</h3>
                    <p class="detailed"><a href="/" style="color: rgb(115, 193, 219);">一番最初のページ</a>の左上に同じようなものがあるよ ※ログインした状態で表示されます</p>
                    <img width="300" height="200" src="/storage/article_1_2.png" alt="プラスボタン">
                    <h3>このプラスボタンを押すことで新しいパラパラ漫画を作成できるよ！↑</h3>
                    <p class="detailed">最大十個まで漫画を作成できます。</p>
                </div>
            </main>
            @livewire('footer')
        </div>
    </body>
</html>
