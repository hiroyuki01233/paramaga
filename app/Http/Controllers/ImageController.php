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
        $manga = new Manga;

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
        $manga->url = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 30);

        if(!Storage::exists('private/image/'.Auth::user()->id)) Storage::makeDirectory('private/image/'.Auth::user()->id);
        if(!Storage::exists('private/image/'.Auth::user()->id."/".$number)) Storage::makeDirectory('private/image/'.Auth::user()->id."/".$number);

        for($i = 1; $i <= 10; $i++){
            if(empty($request->input("image_".$i))) break;

            $data = $request->input("image_".$i);
            $data = explode(',', $data);

            $hash = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 30);
            $file_name = "private/image/".Auth::user()->id."/".$number."/".$hash.".jpeg";
            $manga->{ "image_".$i } = $file_name;

            $image = base64_decode($data[1]);
            Storage::put($file_name, $image);
            unset($image);
            unset($data);
        }

        $manga->save();

        return true;

        // $file_name = "/storage/test1.jpeg";
        // file_put_contents(".".$file_name, base64_decode($data[1]));

        // $image = new Image;

        // $image->user_id = Auth::user()->name;
        // $image->file_name = $file_name;

        // $image->save();

        // return json_encode($request->input('file'));

        // $image = new Image;

        // $image->user_id = $request;
        // $image->file_name = $request->name;

        // $flight->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $manga = Manga::where('user_id',Auth::user()->id)->where('number_of_works',$id)->select("id","published_flag")->get()->toArray();
        $id = $manga[0]["id"];
        $pubFlagNow = $manga[0]["published_flag"];
        $pubFlag = ($pubFlagNow) ? 0 : 1;
        $mangaModel = Manga::find($id);
        $mangaModel->published_flag = $pubFlag;
        $mangaModel->save();
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
        $imageAll = Manga::where('user_id',Auth::user()->id)
        ->where('number_of_works',$id)
        ->select('image_1', 'image_2', 'image_3','image_4','image_5','image_6','image_7','image_8','image_9','image_10')
        ->get()->toArray();

        if(Storage::exists('private/image/'.Auth::user()->id."/".$id)){
            Storage::deleteDirectory('private/image/'.Auth::user()->id."/".$id);
        }

        Manga::where('user_id',Auth::user()->id)->where('number_of_works',$id)->delete();

        return true;
    }

    public function thumbnailPublic(Request $request)
    {

        $filePath = \DB::table('users')
            ->select('manga.image_1')
            ->join('manga', 'users.id', '=', 'manga.user_id')
            ->where('users.pen_name',$request->penName)
            ->where('manga.number_of_works',$request->number)
            ->where('published_flag', 1)
            ->get()->toArray();
        $filePath = json_decode(json_encode($filePath), true);

        $base64data = base64_encode(Storage::get($filePath[0]["image_1"]));

        return json_encode("data:image/jpeg;base64,".$base64data);

    }

    public function thumbnailForMyself(Request $request)
    {
        $filePath = Manga::where('user_id',Auth::user()->id)
            ->where('number_of_works',$request->number)
            ->select("image_1")
            ->get()->toArray();
        $filePath = json_decode(json_encode($filePath), true);

        $base64data = base64_encode(Storage::get($filePath[0]["image_1"]));

        return json_encode("data:image/jpeg;base64,".$base64data);

    }
}
