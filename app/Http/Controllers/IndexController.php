<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\User;


class IndexController extends Controller
{
    public function index()
    {
        $userManga = \DB::table('users')
            ->select('manga.title','manga.number_of_works','manga.url','users.pen_name')
            ->join('manga', 'users.id', '=', 'manga.user_id')
            ->where('published_flag','1')
            ->get()->toArray();
        $userManga = json_decode(json_encode($userManga), true);
        return view("index",compact("userManga"));
    }

    public function info()
    {
        $userAll = User::All();
        print($userAll);
        exit;
    }

    public function view($name, Request $request)
    {
        $manga_url = $request->m;
        $manga = \DB::table('users')
        ->select('users.name','manga.title','manga.number_of_paper','users.pen_name')
        ->join('manga', 'users.id', '=', 'manga.user_id')
        ->where('pen_name',$name)
        ->where('url', $manga_url)
        ->get()->toArray();
        $manga = json_decode(json_encode($manga[0]), true);

        return view("view",compact("manga"));
    }

}