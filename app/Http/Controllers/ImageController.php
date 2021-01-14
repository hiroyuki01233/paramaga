<?php

namespace App\Http\Controllers;
use App\Models\Image;
use App\Models\Manga;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mangaAll = Manga::all();

        return $mangaAll->toArray();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $image = new Image;

        $imageAll = Image::all();

        return $imageAll->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()) return \App::abort(404);

        $manga = new Manga;
        $mangaWorks = Manga::where('user_id',Auth::user()->id)->select("number_of_works")->get();

        $validated = $request->validate([
            'title' => 'required|max:100',
            'image_1' => 'required',
        ]);

        $existFlag = 0;
        for($i = 1; $i < 11; $i++){
            foreach($mangaWorks as $works){
                if($i == $works["number_of_works"]) $existFlag = 1;
            }
            if($existFlag == 0) {
                $number = $i;
                break;
            }
            $existFlag = 0;
        }

        $manga->number_of_works =  $number;
        $manga->user_id = Auth::user()->id;
        $manga->title = $request->input("title");
        $manga->published_flag = 0;
        // $manga->url = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 30);

        $manga->url = md5(uniqid(rand(),1));

        if(!Storage::exists('private/image/'.Auth::user()->id)) Storage::makeDirectory('private/image/'.Auth::user()->id);
        if(!Storage::exists('private/image/'.Auth::user()->id."/".$number)) Storage::makeDirectory('private/image/'.Auth::user()->id."/".$number);

        for($i = 1; $i <= 10; $i++){
            if(empty($request->input("image_".$i))) break;

            $data = $request->input("image_".$i);
            $data = explode(',', $data);

            $file_name = "private/image/".Auth::user()->id."/".$number."/".$i.".jpeg";

            $image = base64_decode($data[1]);
            Storage::put($file_name, $image);
            unset($image);
            unset($data);
        }

        $manga->number_of_paper = $i-1;
        $manga->save();

        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return "show ok";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!Auth::user()) return \App::abort(404);
        $image = Manga::where('user_id',Auth::user()->id)
            ->where('number_of_works',$id)
            ->select('number_of_works','number_of_paper')
            ->get()->toArray();
        $image = json_decode(json_encode($image[0]), true);

        for($i = 1; $i <= $image['number_of_paper']; $i++){
            $iamges[$i] = "data:image/jpeg;base64,".base64_encode(Storage::get("private/image/".Auth::user()->id."/".$image['number_of_works']."/".$i.".jpeg"));
        }

        return json_encode($iamges);

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
        if(!Auth::user()) return \App::abort(404);
        if(!preg_match("/^[0-9]+$/", $id)) return \App::abort(404);

        if(!empty($request->thisPublishedFlag)){
            $manga = Manga::where('user_id',Auth::user()->id)->where('number_of_works',$id)->select("id","published_flag")->get()->toArray();
            $id = $manga[0]["id"];
            $pubFlagNow = $manga[0]["published_flag"];
            $pubFlag = ($pubFlagNow) ? 0 : 1;
            $mangaModel = Manga::find($id);
            $mangaModel->published_flag = $pubFlag;
            $mangaModel->save();
            return true;
        }

        $mangaId = Manga::where('user_id',Auth::user()->id)->where('number_of_works',$id)->select("id")->get()->toArray();
        $mangaId = $mangaId[0]["id"];

        $manga = Manga::find($mangaId);
        $manga->title = $request->input("title");

        $requestAll = $request->all();
        foreach($requestAll as $number => $image){
            if(strpos($number,'image_') !== false){
                if(!preg_match("/^[0-9]+$/", str_replace('image_', '', $number))) continue;
                $fileName = str_replace('image_', '', $number).".jpeg";
                if(Storage::exists('private/image/'.Auth::user()->id."/".$id."/".$fileName)){
                    Storage::delete('private/image/'.Auth::user()->id."/".$id."/".$fileName);
                }
                $filePath = 'private/image/'.Auth::user()->id."/".$id."/".$fileName;
                $image = explode(',', $image);
                $image = base64_decode($image[1]);
                Storage::put($filePath, $image);
            }
        }
        $fileCount = count(Storage::files('private/image/'.Auth::user()->id."/".$id));
        $manga->number_of_paper = $fileCount;

        $manga->save();

        return true;
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
        $image = Manga::where('user_id',Auth::user()->id)
        ->where('number_of_works',$id)
        ->select('number_of_works')
        ->get()->toArray();

        if(Storage::exists('private/image/'.Auth::user()->id."/".$image[0]["number_of_works"])){
            Storage::deleteDirectory('private/image/'.Auth::user()->id."/".$image[0]["number_of_works"]);
        }

        Manga::where('user_id',Auth::user()->id)->where('number_of_works',$id)->delete();

        return true;
    }

    public function thumbnailPublic(Request $request)
    {
        $filePath = \DB::table('users')
            ->select('users.id','manga.number_of_works')
            ->join('manga', 'users.id', '=', 'manga.user_id')
            ->where('manga.url',$request->url)
            ->where('published_flag', 1)
            ->get()->toArray();
        $filePath = json_decode(json_encode($filePath), true);
        $base64data = base64_encode(Storage::get("private/image/".$filePath[0]['id']."/".$filePath[0]['number_of_works']."/1.jpeg"));

        return json_encode("data:image/jpeg;base64,".$base64data);
    }

    public function myMangaThumbnaiAll(Request $request)
    {
        if(!Auth::user()) return \App::abort(404);
        $filePath = Manga::where('user_id',Auth::user()->id)
            ->select("number_of_works")
            ->get()->toArray();
        $filePath = json_decode(json_encode($filePath), true);

        foreach($filePath as $file){
            if(Storage::exists("private/image/".Auth::user()->id."/".$file['number_of_works']."/1.jpeg")){
                $iamges[$file['number_of_works']] = "data:image/jpeg;base64,".base64_encode(Storage::get("private/image/".Auth::user()->id."/".$file['number_of_works']."/1.jpeg"));
            };
        }
        if(!empty($iamges)) return json_encode($iamges);
        return true;
    }

    public function publicMangaByFlameNumber(Request $request)
    {
        $url = $request->url;

        $manga = \DB::table('users')
            ->select('users.id','number_of_works','number_of_paper')
            ->join('manga', 'users.id', '=', 'manga.user_id')
            ->where('url', $url)
            ->where('published_flag', 1)
            ->get()->toArray();
        $manga = json_decode(json_encode($manga[0]), true);
        $mangaNumber = $manga['number_of_works'];
        $iamgeCount = $manga['number_of_paper'];
        $userId = $manga['id'];

        for($i = 1; $i <= $iamgeCount; $i++){
            $iamges[$i] = "data:image/jpeg;base64,".base64_encode(Storage::get("private/image/".$userId."/".$mangaNumber."/".$i.".jpeg"));
        }

        return json_encode($iamges);
    }

    public function previewManga(Request $request)
    {
        if(!Auth::user()) return \App::abort(404);
        $url = $request->url;

        $manga = \DB::table('users')
            ->select('number_of_works','number_of_paper')
            ->join('manga', 'users.id', '=', 'manga.user_id')
            ->where('url', $url)
            ->get()->toArray();
        $manga = json_decode(json_encode($manga), true);
        $mangaNumber = $manga[0]['number_of_works'];
        $iamgeCount = $manga[0]['number_of_paper'];

        for($i = 1; $i <= $iamgeCount; $i++){
            $iamges[$i] = "data:image/jpeg;base64,".base64_encode(Storage::get("private/image/".Auth::user()->id."/".$mangaNumber."/".$i.".jpeg"));
        }

        return json_encode($iamges);

    }

}
