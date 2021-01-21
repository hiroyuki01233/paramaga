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
        <script src="{{ asset('/js/play.js') }}"></script>
        <script>
            const penName = @json($manga["pen_name"]);
            const url = @json($manga["url"]);
            var myPenName = @json($myPenName);
            var liked = @json($liked);
            const HOST_NAME = "{{config('const.HOST_NAME')}}";
        </script>
        <style>
            .images {
                margin-top: 3%;
                margin-bottom: 1%;
                text-align: center;
            }
            .detail {
                text-align: center;
            }
            .add_comment {
                margin: 1%;
                margin-bottom: 2%;
            }
            .user_comment {
                margin-left: 13%;
                margin-right: 13%;
                padding-right: 7%;
                margin-top: 1%;
            }
            .comment {
                margin-left: 2%;
                word-wrap: break-word;
                /* display: inline; */
            }
            .add_comment_btn {
                margin-left: 2%;
            }
            .textarea {
                width: 50%;
                height: 2em;
            }
            .delete_btn {
                /* margin: 0 0 0 auto;     */
                margin-left: 100%;
                margin-right: 10%;
            }
            .commentCount {
                font-size: 20px;
                border-bottom: 1px solid;
                margin-bottom: 3%;
            }
            .like_btn {
                font-size: 30px;
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
                        <div id="flame">0</div>
                        <button type="button" id="playScreen" onclick="playManga()">
                            <img src="/storage/play.jpeg" width="1100px" >
                        </button>
                    </div>
                    <div class="detail">
                        @if ( $manga["profile_photo_path"] )
                            <a href="/profile?u={{$manga["pen_name"]}}"><img class="h-8 w-8 rounded-full object-cover" src="/storage/{{ $manga["profile_photo_path"] }}" alt="{{$manga["pen_name"]}}" /></a>
                        @else
                            <a href="/profile?u={{$manga["pen_name"]}}"><img class="h-8 w-8 rounded-full object-cover" src="/storage/UserIcon.png" alt="{{$manga["pen_name"]}}" /></a>
                        @endif
                        <span>{{ $manga["name"] }}</span>
                        <span>{{ "@" }}{{ $manga["pen_name"] }}</span>
                        <span>作品 : {{ $manga["title"] }}</span>
                        @if($liked)
                            <input id="like_btn" type="button" value="❤️" class="like_btn" onclick="changeLike()">
                        @else
                            <input id="like_btn" type="button" value="♡" class="like_btn" onclick="changeLike()">
                        @endif
                        <div class="add_comment">
                            <input type="textarea" id="comment_text" name="comment" value="" class="textarea">
                            <input id="add_comment_btn" type="button" value="投稿" class="add_comment_btn" onclick="addComment()">
                        </div>
                        <span style="font-size: 20px">いいね数 : {{ $likeCount }}</span>
                    </div>
                    <div class="commentCount user_comment">
                        <p>コメント数 : {{ $commentCount }}</p>
                    </div>
                    <div id="comments">
                    </div>
                </div>
            </main>
            @livewire('footer')
        </div>
    </body>
</html>
