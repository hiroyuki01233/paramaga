<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use Illuminate\Support\Facades\Auth;

class MangaController extends Controller
{
    public function index()
    {
        $manga = new Manga;

        $mangaAll = Manga::where('user_id',Auth::user()->id)
        ->select("title","number_of_works","published_flag")
        ->get();

        return view('manga',compact('mangaAll'));
    }
}
