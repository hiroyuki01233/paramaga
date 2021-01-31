<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\User;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;


class IndexController extends Controller
{
    public function index()
    {
        $userManga = \DB::table('users')
            ->select('manga.title','manga.url','users.pen_name','users.profile_photo_path','users.name')
            ->join('manga', 'users.id', '=', 'manga.user_id')
            ->where('published_flag','1')
            ->orderBy('manga.id', 'desc')
            ->limit(10)
            ->get()->toArray();
        $userManga = json_decode(json_encode($userManga), true);
        return view("index",compact("userManga"));
    }

    public function info()
    {
        if(!Auth::user() || Auth::user()->id !== "1") return \App::abort(404);
        // $userManga = \DB::table('users')
        //     ->select('manga.title','manga.url','users.pen_name','users.profile_photo_path','users.email')
        //     ->join('manga', 'users.id', '=', 'manga.user_id')
        //     ->where('published_flag','1')
        //     ->orderBy('manga.id', 'desc')
        //     ->get()->toArray();
        // $userManga = json_decode(json_encode($userManga), true);
        // dump(Auth::user()->email_verified_at);
        // if(Auth::user()->email_verified_at) dd("test");
        $userAll = User::select('email','name','email_verified_at')->get();
        // print($userAll);
        foreach($userAll as $user){
            print($user);
            print("\n");
        }
        exit;
    }

    public function view($name, Request $request)
    {
        $manga_url = $request->m;
        $manga = \DB::table('users')
            ->select('users.name','manga.title','manga.number_of_paper','users.pen_name','manga.url','users.profile_photo_path')
            ->join('manga', 'users.id', '=', 'manga.user_id')
            ->where('url', $manga_url)
            ->where('published_flag', 1)
            ->get()->toArray();
        $manga = json_decode(json_encode($manga[0]), true);
        $liked = false;
        if(Auth::user()) $liked = Like::where("url",$manga_url)->where("user_id",Auth::user()->id)->exists();
        $myPenName = "";
        $commentCount = Comment::where("url",$manga_url)->count();
        $likeCount = Like::where("url",$manga_url)->count();
        if(Auth::user()) $myPenName = Auth::user()->pen_name;
        return view("view",compact("manga","myPenName","liked","commentCount","likeCount"));
    }

    public function preview($name, Request $request){
        if(!Auth::user()) return \App::abort(404);
        $manga_url = $request->m;
        $manga = \DB::table('users')
            ->select('users.name','manga.title','manga.number_of_paper','users.pen_name','manga.url')
            ->join('manga', 'users.id', '=', 'manga.user_id')
            ->where('url', $manga_url)
            ->get()->toArray();
        $manga = json_decode(json_encode($manga[0]), true);

        return view("preview",compact("manga"));
    }

    public function userProfile(Request $request){
        if(empty($penName = $request->input("u"))) return \App::abort(404);
        $userInfo = User::where("pen_name",$penName)->select("id","pen_name","name","profile_photo_path")->get()->toArray();
        $userInfo = $userInfo[0];
        $mangaAll = \DB::table('users')
            ->select('manga.title','manga.url','users.pen_name','users.profile_photo_path','users.name')
            ->join('manga', 'users.id', '=', 'manga.user_id')
            ->where('pen_name', $penName)
            ->where("published_flag", 1)
            ->get();
        $mangaList = [];
        foreach($mangaAll as $manga) $mangaList[] = $manga->url;
        $commentCount = Comment::whereIn("url",$mangaList)->count();
        $likeCount = Like::whereIn("url",$mangaList)->count();
        $mangaAll = json_decode(json_encode($mangaAll), true);
        return view("userDetail",compact("userInfo","commentCount","likeCount","mangaAll"));
    }

    public function user(){
        if(!Auth::user()) return \App::abort(404);
        $userInfo = User::where("id",Auth::user()->id)->get()->toArray();
        $userInfo = $userInfo[0];
        $mangaAll = \DB::table('users')
            ->select('manga.title','manga.url','users.pen_name','users.profile_photo_path','users.name')
            ->join('manga', 'users.id', '=', 'manga.user_id')
            ->where('pen_name', Auth::user()->pen_name)
            ->where("published_flag", 1)
            ->get();
        $mangaUrls = Like::where("user_id",Auth::user()->id)->select("url")->get();
        $likeAll = \DB::table('users')
            ->select('manga.title','manga.url','users.pen_name','users.profile_photo_path','users.name')
            ->join('manga', 'users.id', '=', 'manga.user_id')
            ->whereIn('manga.url', $mangaUrls)
            ->where("published_flag", 1)
            ->get();
        $mangaList = [];
        foreach($mangaAll as $manga) $mangaList[] = $manga->url;
        $commentCount = Comment::whereIn("url",$mangaList)->count();
        $likeCount = Like::whereIn("url",$mangaList)->count();
        $mangaAll = json_decode(json_encode($mangaAll), true);
        $likeAll = json_decode(json_encode($likeAll), true);
        return view("user",compact("userInfo","commentCount","likeCount","mangaAll","likeAll"));

    }

    public function article($id){
        if(!$id) return \App::abort(404);
        return view("articles/".$id);
    }

    public function form(Request $request){

        $validator = $request->validate([     
            'name' => 'required|max:100',
            'email' => 'max:50',
            'content' => 'required|min:8|max:1800',
        ]);

        //処理内容を定義
        function send_to_slack($message) {
            $webhook_url = 'https://discord.com/api/webhooks/801674454010691604/Z8Vl8Sk8Rijq8pdQSFQuaYiS2Svca91nDV4JV7XwuFMfnN_vHRyMKZ1em7yIi13GIaIQ';
            $options = array(
                'http' => array(
                    'method' => 'POST',
                    'header' => 'Content-Type: application/json',
                    'content' => json_encode($message),
                )
            );
            $response = file_get_contents($webhook_url, false, stream_context_create($options)); //要求を$webhook_urlのURLに投げて結果を受け取る
            return $response === 'ok'; //$responseの値がokならtrueを返す
        }
        
        $message = array(
            'username' => "ボット-あきこ",
            'content' => "name : ".$request->input("name")."\nemail : ".$request->input("email")."\n内容 : ".$request->input("content")
        );
        
        $completeFlg = send_to_slack($message); //処理を実行
        $okFlg = true;
        return view("form",compact("okFlg"));
    }

}
