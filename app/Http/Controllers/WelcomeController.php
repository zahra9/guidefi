<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use WebDevEtc\BlogEtc\Models\Post;

class WelcomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function welcome()
    {
        return view('welcome');
    }


    public function viewPost($slug){
        try{
            $post = Post::where('slug', $slug)->first();
            return view('vendor.blogetc_admin.posts.single_post',compact('post'));
        }catch(Exception $e){
            return back();
        }
    }
}
