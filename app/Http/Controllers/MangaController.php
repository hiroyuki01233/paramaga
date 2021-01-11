<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MangaController extends Controller
{
    public function index()
    {
        $mangaAll = \DB::table('users')
        ->select('manga.title','manga.number_of_works','manga.url','manga.published_flag','users.pen_name')
        ->join('manga', 'users.id', '=', 'manga.user_id')
        ->get()->toArray();
        $mangaAll = json_decode(json_encode($mangaAll), true);

        return view('manga',compact("mangaAll"));
    }
}
