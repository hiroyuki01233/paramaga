<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use Illuminate\Support\Facades\Auth;

class EditController extends Controller
{
    public function edit($id){
        $mangaInfo = Manga::where('user_id',Auth::user()->id)
            ->where('number_of_works',$id)
            ->select('number_of_works','title')
            ->first();
        if($mangaInfo == null) return \App::abort(404);
        return view('edit',compact('id',"mangaInfo"));
    }
}
