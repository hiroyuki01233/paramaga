<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use Illuminate\Support\Facades\Auth;

class EditController extends Controller
{
    public function edit($id){
        return view('edit',compact('id'));
    }
}
