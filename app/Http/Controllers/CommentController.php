<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Models\Manga;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $comments = \DB::table('users')
            ->select('comments.id','users.pen_name','comments.comment','users.profile_photo_path')
            ->join('comments', 'users.id', '=', 'comments.user_id')
            ->where('comments.url', $request->url)
            ->orderBy('comments.id', 'desc')
            ->paginate(50);
        // $comments = Comment::where("url",$request->url)->select("id","pen_name","comment")->orderBy('id', 'desc')->paginate(50);
        return $comments;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user() || !Auth::user()->email_verified_at) return "plz login";
        $requestAll = $request->all();
        if(Manga::where('url',$request->url)->where("published_flag", 1)->exists()){
            $comment = new Comment;
            $comment->user_id = Auth::user()->id;
            $comment->url = $request->url;
            $comment->comment = $request->comment;
            $comment->save();
            return $comment->id;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!Auth::user()) return \App::abort(404);
        if(Comment::where('id',$id)->where("user_id", Auth::user()->id)->exists()){
            Comment::where("id",$id)->delete();
            return true;
        }
        return false;
    }
}
