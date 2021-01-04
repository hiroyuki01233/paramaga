<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use Illuminate\Support\Facades\Auth;

class EditController extends Controller
{
    public function edit($id){
        $manga = new Manga;

        $mangaAll = Manga::where('user_id',Auth::user()->id)->where('number_of_works',$id)->get();

        return $mangaAll->toArray();
    }
}
