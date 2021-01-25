<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ "お問い合わせフォーム！Paramaga / パラマガ" }}</title>
        <meta name="description" content="パラマガでパラパラ漫画を作ろう。絵が得意な方！パラパラ漫画を試しに作ってみたい方！ウェブサイト上で手軽にパラパラ漫画を作成してたくさんの人共有しましょう！あなたの素敵な作品をみたたくさんの人からいいねやコメントがもらえるかも！">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @livewireStyles

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal-default-theme.min.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.0.5/remodal.min.js"></script>
        <link rel="shortcut icon" href="{{ asset('storage/icon.ico') }}">

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-dropdown')
            <main align="center">
                @if(!empty($okFlg))
                    <h1 style="margin-top: 2%">送信完了</h1>
                @else
                    <div style="margin-top: 2%"><h1>Paramaga / パラマガ</h1></div>
                    <div><h2>お問い合わせ</h2></div>
                    <div>
                        <form action="form" method="post" name="form" onsubmit="">
                            @csrf
                            <div>
                                <div style="margin: 1%">
                                    <label>お名前 <span>(必須)</span></label>
                                    <input required type="text" name="name" placeholder="例）山田太郎" value="">
                                </div>
                                @if ($errors->first('name'))   <!-- ここ追加 -->
                                    <p class="validation" style="color: red">※{{$errors->first('name')}}</p>
                                @endif
                                <div style="margin: 1%">
                                    <label>メールアドレス <span>(任意)</span></label>
                                    <input type="text" name="email" size="40" placeholder="例）paramaga@paramaga.com" value="">
                                </div>
                                <div style="margin: 1%">
                                    <label>お問い合わせ内容 <span>(必須)</span></label>
                                    <textarea required name="content" rows="5" cols="50" placeholder="お問合せ内容を入力"></textarea>
                                </div>
                                @if ($errors->first('content'))   <!-- ここ追加 -->
                                    <p class="validation" style="color: red">※{{$errors->first('content')}}</p>
                                @endif
                            </div>
                            <button type="submit">送信</button>
                        </form>
                    </div>
                @endif
            </main>
            @livewire('footer')
        </div>
    </body>
</html>
