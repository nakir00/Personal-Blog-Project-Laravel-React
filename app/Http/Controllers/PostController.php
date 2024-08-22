<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PostController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // showing all the posts
        return Post::with(['user', 'comments'])->latest()->get();
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if($request->hasFile('image')){
            $file = $request->file('photo');
            Log::alert('image added');
            return ['image'=>'image successfully added'];
        }
       /*  $data = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'cannot_comment'=> 'boolean',
            'image' => 'image',
        ]); */


       // $post = $request->user()->posts()->create($data);

        /* return ['post' => $post,
    'user' => $post->user]; */
    return response(['message'=>'message bon controller']);
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // return one post
        return [ 'post' => $post ];
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('modify', $post);
        // update a post
        $data = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        $post->update($data);
        return [ 'post' => $post ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('modify', $post);
        $post->delete();
        return [
            'message' => 'post deleted successfully'
        ];
    }
}
