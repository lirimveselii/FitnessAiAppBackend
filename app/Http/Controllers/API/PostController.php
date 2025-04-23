<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index(Request $request) {
        $posts = Post::paginate(5);

        return response()->json([
            "status" =>  1  ,
            "message" => "Post Fetched",
            "data"=> $posts
        ]);

    }
}
