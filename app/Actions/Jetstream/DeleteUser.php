<?php

namespace App\Actions\Jetstream;

use Laravel\Jetstream\Contracts\DeletesUsers;
use App\Models\Manga;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Support\Facades\Storage;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     *
     * @param  mixed  $user
     * @return void
     */
    public function delete($user)
    {
        if(Storage::exists('private/image/'.$user->id)){
            Storage::deleteDirectory('private/image/'.$user->id);
        }
        Manga::where('user_id',$user->id)->delete();
        Comment::where("user_id",$user->id)->delete();
        Like::where("user_id",$user->id)->delete();
        
        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();
    }
}
